<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (InvoiceItem $item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
