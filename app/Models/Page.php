<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'type',
        'content',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public const TYPE_HOME = 'home';

    public const TYPE_SOLUTIONS = 'solutions';

    public const TYPE_SOLUTION_DETAIL = 'solution-detail';

    public const TYPE_REFERENCES = 'references';

    public const TYPE_REFERENCE_DETAIL = 'reference-detail';

    public const TYPE_ABOUT = 'about';

    public const TYPE_CONTACT = 'contact';

    public const TYPE_IMPRINT = 'imprint';

    public const TYPE_PRIVACY = 'privacy';

    public static function getTypes(): array
    {
        return [
            self::TYPE_HOME => 'Startseite',
            self::TYPE_SOLUTIONS => 'Lösungen Übersicht',
            self::TYPE_SOLUTION_DETAIL => 'Lösungs-Detail',
            self::TYPE_REFERENCES => 'Referenzen',
            self::TYPE_REFERENCE_DETAIL => 'Referenz-Detail',
            self::TYPE_ABOUT => 'Über uns',
            self::TYPE_CONTACT => 'Kontakt',
            self::TYPE_IMPRINT => 'Impressum',
            self::TYPE_PRIVACY => 'Datenschutz',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public static function findBySlug(string $slug): ?self
    {
        return Cache::remember(
            "page.{$slug}",
            now()->addHours(24),
            fn () => self::active()->where('slug', $slug)->first()
        );
    }

    public static function findByType(string $type): ?self
    {
        return Cache::remember(
            "page.type.{$type}",
            now()->addHours(24),
            fn () => self::active()->ofType($type)->first()
        );
    }

    public static function clearCache(string $slug): void
    {
        Cache::forget("page.{$slug}");
    }

    protected static function booted(): void
    {
        static::saved(function (Page $page) {
            Cache::forget("page.{$page->slug}");
            Cache::forget("page.type.{$page->type}");
        });

        static::deleted(function (Page $page) {
            Cache::forget("page.{$page->slug}");
            Cache::forget("page.type.{$page->type}");
        });
    }

    /**
     * Helper to get content section with default fallback
     *
     * @param  array<string, mixed>  $default
     * @return array<string, mixed>|mixed
     */
    public function getSection(string $key, mixed $default = []): mixed
    {
        return data_get($this->content, $key, $default);
    }
}
