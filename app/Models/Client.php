<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salutation',
        'title',
        'first_name',
        'last_name',
        'company',
        'email',
        'phone',
        'street',
        'zip',
        'city',
        'country',
        'notes',
        'default_hourly_rate',
    ];

    protected function casts(): array
    {
        return [
            'default_hourly_rate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class)->orderByDesc('worked_on');
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->title,
            $this->first_name,
            $this->last_name,
        ]);

        return implode(' ', $parts);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->company) {
            return "{$this->company} ({$this->full_name})";
        }

        return $this->full_name;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            trim($this->zip.' '.$this->city),
            $this->country !== 'Deutschland' ? $this->country : null,
        ]);

        return implode("\n", $parts);
    }
}
