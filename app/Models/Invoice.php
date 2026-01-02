<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'contract_id',
        'quote_id',
        'client_name',
        'client_company',
        'client_email',
        'client_address',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'period_start',
        'period_end',
        'status',
        'issue_date',
        'due_date',
        'sent_at',
        'paid_at',
        'payment_method',
        'payment_reference',
        'payment_intent_id',
        'cancellation_number',
        'cancellation_pdf_path',
        'cancelled_at',
        'cancellation_reason',
        'pdf_path',
        'reminder_count',
        'last_reminder_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => InvoiceStatus::class,
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
            'issue_date' => 'date',
            'due_date' => 'date',
            'sent_at' => 'datetime',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'last_reminder_at' => 'datetime',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(QuoteActivity::class)->orderByDesc('created_at');
    }

    // Scopes
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::Draft);
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::Sent);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::Paid);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::Overdue);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', [InvoiceStatus::Sent, InvoiceStatus::Overdue]);
    }

    public function scopeNeedsReminder(Builder $query): Builder
    {
        return $query->open()
            ->where('due_date', '<', now())
            ->where(function ($q) {
                $q->whereNull('last_reminder_at')
                    ->orWhere('last_reminder_at', '<', now()->subDays(7));
            });
    }

    // Helpers
    public function isPaid(): bool
    {
        return $this->status === InvoiceStatus::Paid;
    }

    public function isOverdue(): bool
    {
        if ($this->isPaid() || $this->status === InvoiceStatus::Cancelled) {
            return false;
        }

        return $this->due_date && $this->due_date->isPast();
    }

    public function isCancelled(): bool
    {
        return $this->status === InvoiceStatus::Cancelled;
    }

    public function canBePaid(): bool
    {
        return in_array($this->status, [InvoiceStatus::Sent, InvoiceStatus::Overdue]);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [InvoiceStatus::Draft, InvoiceStatus::Sent, InvoiceStatus::Overdue]);
    }

    public function markAsPaid(?string $paymentMethod = null, ?string $reference = null): void
    {
        $this->update([
            'status' => InvoiceStatus::Paid,
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference,
        ]);
    }

    public function getDaysOverdue(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        return $this->due_date->diffInDays(now());
    }

    public function getPeriodLabel(): ?string
    {
        if (! $this->period_start || ! $this->period_end) {
            return null;
        }

        return $this->period_start->format('d.m.Y').' - '.$this->period_end->format('d.m.Y');
    }

    public function getClientFullName(): string
    {
        if ($this->client_company) {
            return $this->client_company;
        }

        return $this->client_name;
    }
}
