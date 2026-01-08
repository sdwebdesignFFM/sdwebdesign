<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkLog extends Model
{
    /** @use HasFactory<\Database\Factories\WorkLogFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'task_id',
        'invoice_id',
        'worked_on',
        'title',
        'description',
        'duration_minutes',
        'hourly_rate',
        'is_billed',
    ];

    protected function casts(): array
    {
        return [
            'worked_on' => 'date',
            'duration_minutes' => 'integer',
            'hourly_rate' => 'decimal:2',
            'is_billed' => 'boolean',
        ];
    }

    // Relationships

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Scopes

    public function scopeUnbilled(Builder $query): Builder
    {
        return $query->where('is_billed', false);
    }

    public function scopeBilled(Builder $query): Builder
    {
        return $query->where('is_billed', true);
    }

    public function scopeForClient(Builder $query, int $clientId): Builder
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('worked_on', $year)
            ->whereMonth('worked_on', $month);
    }

    // Helpers

    /**
     * Get duration formatted as H:MM
     */
    public function getDurationFormattedAttribute(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        return sprintf('%d:%02d', $hours, $minutes);
    }

    /**
     * Get duration in hours (decimal)
     */
    public function getDurationHoursAttribute(): float
    {
        return round($this->duration_minutes / 60, 2);
    }

    /**
     * Get effective hourly rate (own → client → settings)
     */
    public function getEffectiveHourlyRateAttribute(): float
    {
        if ($this->hourly_rate !== null) {
            return (float) $this->hourly_rate;
        }

        if ($this->client && $this->client->default_hourly_rate !== null) {
            return (float) $this->client->default_hourly_rate;
        }

        $settings = Setting::instance();

        return (float) ($settings->default_hourly_rate ?? 85.00);
    }

    /**
     * Get total amount for this entry
     */
    public function getTotalAmountAttribute(): float
    {
        return round($this->duration_hours * $this->effective_hourly_rate, 2);
    }

    /**
     * Mark as billed and link to invoice
     */
    public function markAsBilled(Invoice $invoice): void
    {
        $this->update([
            'is_billed' => true,
            'invoice_id' => $invoice->id,
        ]);
    }

    /**
     * Generate duration options for select (15-min steps)
     */
    public static function getDurationOptions(): array
    {
        $options = [];
        for ($minutes = 15; $minutes <= 720; $minutes += 15) {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            $label = sprintf('%d:%02d', $hours, $mins);
            $options[$minutes] = $label;
        }

        return $options;
    }
}
