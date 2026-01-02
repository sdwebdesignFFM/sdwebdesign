<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'hits',
        'last_hit_at',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_hit_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function findByPath(string $path): ?self
    {
        $normalizedPath = '/'.ltrim($path, '/');

        return static::active()
            ->where('from_url', $normalizedPath)
            ->first();
    }

    public function recordHit(): void
    {
        $this->increment('hits');
        $this->update(['last_hit_at' => now()]);
    }
}
