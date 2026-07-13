<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param  Builder<Redirect>  $query
     * @return Builder<Redirect>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Resolve the redirect for a given request path.
     *
     * Exact matches take priority. If none is found, wildcard redirects
     * (whose `from_url` ends with `/*`, e.g. `/glossar/*`) are matched by
     * prefix, preferring the most specific (longest) prefix.
     */
    public static function findByPath(string $path): ?self
    {
        $normalizedPath = '/'.ltrim($path, '/');

        $exact = static::active()
            ->where('from_url', $normalizedPath)
            ->first();

        if ($exact) {
            return $exact;
        }

        return static::active()
            ->where('from_url', 'like', '%/*')
            ->get()
            ->filter(fn (self $redirect): bool => $redirect->matchesWildcard($normalizedPath))
            ->sortByDesc(fn (self $redirect): int => strlen($redirect->from_url))
            ->first();
    }

    /**
     * Determine whether a wildcard `from_url` (ending in `/*`) matches the path.
     *
     * Both `/glossar` and `/glossar/anything` match a `/glossar/*` rule.
     */
    public function matchesWildcard(string $path): bool
    {
        if (! str_ends_with($this->from_url, '/*')) {
            return false;
        }

        $prefix = substr($this->from_url, 0, -1);

        return str_starts_with($path, $prefix) || $path.'/' === $prefix;
    }

    public function recordHit(): void
    {
        $this->increment('hits');
        $this->update(['last_hit_at' => now()]);
    }
}
