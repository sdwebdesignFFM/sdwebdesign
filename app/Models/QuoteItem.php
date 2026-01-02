<?php

namespace App\Models;

use App\Enums\BillingCycle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'service_id',
        'name',
        'description',
        'detailed_terms',
        'quantity',
        'unit',
        'billing_cycle',
        'unit_price',
        'total_price',
        'is_optional',
        'is_selected',
        'option_group',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'billing_cycle' => BillingCycle::class,
            'is_optional' => 'boolean',
            'is_selected' => 'boolean',
        ];
    }

    public function isRecurring(): bool
    {
        return $this->billing_cycle !== null;
    }

    protected static function booted(): void
    {
        static::saving(function (QuoteItem $item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function calculateTotal(): float
    {
        return $this->quantity * $this->unit_price;
    }

    public function isIncludedInTotal(): bool
    {
        if (! $this->is_optional) {
            return true;
        }

        return $this->is_selected;
    }

    public function hasDetailedTerms(): bool
    {
        return ! empty($this->detailed_terms);
    }
}
