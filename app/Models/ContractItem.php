<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'service_id',
        'name',
        'description',
        'detailed_terms',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'recurring_price',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'recurring_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ContractItem $item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function hasDetailedTerms(): bool
    {
        return ! empty($this->detailed_terms);
    }
}
