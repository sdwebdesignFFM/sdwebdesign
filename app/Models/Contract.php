<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\ContractStatus;
use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'quote_id',
        'type',
        'client_name',
        'client_company',
        'client_email',
        'client_phone',
        'client_address',
        'title',
        'subject',
        'terms_text',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'billing_cycle',
        'min_term_months',
        'auto_renewal',
        'notice_period_days',
        'start_date',
        'min_term_end_date',
        'current_period_start',
        'current_period_end',
        'next_billing_date',
        'status',
        'cancelled_at',
        'cancellation_effective_date',
        'cancellation_reason',
        'accepted_name',
        'accepted_at',
        'accepted_ip',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'type' => ServiceType::class,
            'billing_cycle' => BillingCycle::class,
            'status' => ContractStatus::class,
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'auto_renewal' => 'boolean',
            'start_date' => 'date',
            'min_term_end_date' => 'date',
            'current_period_start' => 'date',
            'current_period_end' => 'date',
            'next_billing_date' => 'date',
            'cancellation_effective_date' => 'date',
            'cancelled_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ContractItem::class)->orderBy('sort_order');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->orderByDesc('issue_date');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(QuoteActivity::class)->orderByDesc('created_at');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ContractStatus::Active);
    }

    public function scopeRecurring(Builder $query): Builder
    {
        return $query->where('type', ServiceType::Recurring);
    }

    public function scopeNeedsBilling(Builder $query): Builder
    {
        return $query->active()
            ->recurring()
            ->whereNotNull('next_billing_date')
            ->where('next_billing_date', '<=', now());
    }

    public function scopeExpiringMinTerm(Builder $query, int $days = 30): Builder
    {
        return $query->active()
            ->recurring()
            ->whereNotNull('min_term_end_date')
            ->whereBetween('min_term_end_date', [now(), now()->addDays($days)]);
    }

    // Helpers
    public function isRecurring(): bool
    {
        return $this->type === ServiceType::Recurring;
    }

    public function isInMinTerm(): bool
    {
        if (! $this->min_term_end_date) {
            return false;
        }

        return now()->lt($this->min_term_end_date);
    }

    public function canBeCancelled(): bool
    {
        return $this->status === ContractStatus::Active;
    }

    public function getEarliestCancellationDate(): ?\Carbon\Carbon
    {
        if (! $this->canBeCancelled()) {
            return null;
        }

        $noticePeriod = $this->notice_period_days ?? 30;
        $earliestFromNotice = now()->addDays($noticePeriod);

        // Wenn noch in Mindestlaufzeit, dann Ende der Mindestlaufzeit
        if ($this->min_term_end_date && $this->min_term_end_date->gt($earliestFromNotice)) {
            return $this->min_term_end_date;
        }

        return $earliestFromNotice;
    }

    public function getNextRenewalDate(): ?\Carbon\Carbon
    {
        if (! $this->isRecurring() || ! $this->auto_renewal) {
            return null;
        }

        return $this->current_period_end;
    }

    public function advanceBillingPeriod(): void
    {
        if (! $this->isRecurring() || ! $this->billing_cycle) {
            return;
        }

        $months = $this->billing_cycle->getMonths();

        $this->current_period_start = $this->current_period_end;
        $this->current_period_end = $this->current_period_start->addMonths($months);
        $this->next_billing_date = $this->current_period_start;
        $this->save();
    }

    public function getMonthlyValue(): float
    {
        if (! $this->isRecurring() || ! $this->billing_cycle) {
            return 0;
        }

        return $this->total / $this->billing_cycle->getMonths();
    }

    public function getClientFullName(): string
    {
        if ($this->client_company) {
            return $this->client_company.' ('.$this->client_name.')';
        }

        return $this->client_name;
    }
}
