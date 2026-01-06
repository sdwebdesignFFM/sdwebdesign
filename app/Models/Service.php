<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'detailed_terms',
        'payment_terms',
        'category',
        'type',
        'default_price',
        'default_unit',
        'default_billing_cycle',
        'billing_cycles',
        'prices_by_cycle',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => ServiceType::class,
            'default_price' => 'decimal:2',
            'default_billing_cycle' => BillingCycle::class,
            'billing_cycles' => 'array',
            'prices_by_cycle' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function contractItems(): HasMany
    {
        return $this->hasMany(ContractItem::class);
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

    public function getPriceForCycle(string $cycle): ?float
    {
        if ($this->type !== ServiceType::Recurring) {
            return $this->default_price;
        }

        return $this->prices_by_cycle[$cycle] ?? null;
    }

    public function isRecurring(): bool
    {
        return $this->type === ServiceType::Recurring;
    }

    public function hasDetailedTerms(): bool
    {
        return ! empty($this->detailed_terms);
    }
}
