<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'intro_text',
        'terms_text',
        'footer_text',
        'default_validity_days',
        'default_items',
        'default_min_term_months',
        'default_billing_cycle',
        'default_auto_renewal',
        'default_notice_period_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => ServiceType::class,
            'default_items' => 'array',
            'default_auto_renewal' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'template_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRecurring(Builder $query): Builder
    {
        return $query->where('type', ServiceType::Recurring);
    }

    public function scopeOneTime(Builder $query): Builder
    {
        return $query->where('type', ServiceType::OneTime);
    }

    public function isRecurring(): bool
    {
        return $this->type === ServiceType::Recurring;
    }

    public function getBillingCycleEnum(): ?BillingCycle
    {
        return $this->default_billing_cycle
            ? BillingCycle::tryFrom($this->default_billing_cycle)
            : null;
    }
}
