<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'slug',
        'title',
        'content',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'slug',
        'title',
        'type',
        'content',
        'meta_title',
        'meta_description',
        'is_active',
        'parent_id',
        'guide_category_id',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public const TYPE_HOME = 'home';

    public const TYPE_SOLUTIONS = 'solutions';

    public const TYPE_SOLUTION_HUB = 'solution-hub';

    public const TYPE_SOLUTION_DETAIL = 'solution-detail';

    public const TYPE_REFERENCES = 'references';

    public const TYPE_REFERENCE_DETAIL = 'reference-detail';

    public const TYPE_ABOUT = 'about';

    public const TYPE_CONTACT = 'contact';

    public const TYPE_IMPRINT = 'imprint';

    public const TYPE_PRIVACY = 'privacy';

    public const TYPE_GUIDE_OVERVIEW = 'guide-overview';

    public const TYPE_GUIDE = 'guide';

    public const TYPE_SEO = 'seo';

    public const TYPE_SEA = 'sea';

    public const TYPE_LOCAL = 'local';

    public const TYPE_LOCAL_HUB = 'local-hub';

    public const TYPE_MAINTENANCE = 'maintenance';

    public static function getTypes(): array
    {
        return [
            self::TYPE_HOME => 'Startseite',
            self::TYPE_SOLUTIONS => 'Lösungen Übersicht',
            self::TYPE_SOLUTION_HUB => 'Lösungs-Hub',
            self::TYPE_SOLUTION_DETAIL => 'Lösungs-Detail',
            self::TYPE_REFERENCES => 'Referenzen',
            self::TYPE_REFERENCE_DETAIL => 'Referenz-Detail',
            self::TYPE_ABOUT => 'Über uns',
            self::TYPE_CONTACT => 'Kontakt',
            self::TYPE_IMPRINT => 'Impressum',
            self::TYPE_PRIVACY => 'Datenschutz',
            self::TYPE_GUIDE_OVERVIEW => 'Ratgeber Übersicht',
            self::TYPE_GUIDE => 'Ratgeber',
            self::TYPE_SEO => 'SEO',
            self::TYPE_SEA => 'SEA',
            self::TYPE_LOCAL => 'Lokale Landingpage',
            self::TYPE_LOCAL_HUB => 'Lokale Expertise Hub',
            self::TYPE_MAINTENANCE => 'Betrieb & Wartung',
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
        $locale = app()->getLocale();

        return Cache::remember(
            "page.{$locale}.{$slug}",
            now()->addHours(24),
            fn () => self::active()
                ->where("slug->{$locale}", $slug)
                ->first()
        );
    }

    public static function findByType(string $type): ?self
    {
        $locale = app()->getLocale();

        return Cache::remember(
            "page.{$locale}.type.{$type}",
            now()->addHours(24),
            fn () => self::active()->ofType($type)->first()
        );
    }

    public static function clearCache(string $slug): void
    {
        foreach (config('app.available_locales', ['de', 'en']) as $locale) {
            Cache::forget("page.{$locale}.{$slug}");
        }
    }

    protected static function booted(): void
    {
        static::saved(function (Page $page) {
            foreach (config('app.available_locales', ['de', 'en']) as $locale) {
                $slug = $page->getTranslation('slug', $locale, false);
                if ($slug) {
                    Cache::forget("page.{$locale}.{$slug}");
                }
                Cache::forget("page.{$locale}.type.{$page->type}");
                Cache::forget("page.{$locale}.hub_pages_menu");
            }
        });

        static::deleted(function (Page $page) {
            foreach (config('app.available_locales', ['de', 'en']) as $locale) {
                $slug = $page->getTranslation('slug', $locale, false);
                if ($slug) {
                    Cache::forget("page.{$locale}.{$slug}");
                }
                Cache::forget("page.{$locale}.type.{$page->type}");
                Cache::forget("page.{$locale}.hub_pages_menu");
            }
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function guideCategory(): BelongsTo
    {
        return $this->belongsTo(GuideCategory::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->active()
            ->orderBy('sort_order');
    }

    /**
     * Get all ancestor pages from root to parent
     *
     * @return Collection<int, Page>
     */
    public function ancestors(): Collection
    {
        $ancestors = collect();
        $page = $this->parent;

        while ($page) {
            $ancestors->prepend($page);
            $page = $page->parent;
        }

        return $ancestors;
    }

    /**
     * Get the full slug path including ancestors
     */
    public function getFullSlugAttribute(): string
    {
        $slugs = $this->ancestors()->pluck('slug')->push($this->slug);

        return $slugs->implode('/');
    }

    /**
     * Get breadcrumbs array for navigation
     *
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];

        foreach ($this->ancestors() as $ancestor) {
            $breadcrumbs[$ancestor->getUrl()] = $ancestor->title;
        }

        $breadcrumbs[$this->getUrl()] = $this->title;

        return $breadcrumbs;
    }

    /**
     * Get the URL for this page based on its type and hierarchy
     */
    public function getUrl(): string
    {
        $locale = app()->getLocale();
        $prefix = $locale === 'en' ? '/en' : '';

        return match ($this->type) {
            self::TYPE_HOME => $prefix.'/',
            self::TYPE_SOLUTIONS => $prefix.($locale === 'en' ? '/solutions' : '/loesungen'),
            self::TYPE_SOLUTION_HUB, self::TYPE_SOLUTION_DETAIL => $prefix.($locale === 'en' ? '/solutions/' : '/loesungen/').$this->full_slug,
            self::TYPE_REFERENCES => $prefix.($locale === 'en' ? '/references' : '/referenzen'),
            self::TYPE_REFERENCE_DETAIL => $prefix.($locale === 'en' ? '/references/' : '/referenzen/').$this->slug,
            self::TYPE_ABOUT => $prefix.($locale === 'en' ? '/about-us' : '/ueber-uns'),
            self::TYPE_CONTACT => $prefix.($locale === 'en' ? '/contact' : '/kontakt'),
            self::TYPE_IMPRINT => $prefix.($locale === 'en' ? '/imprint' : '/impressum'),
            self::TYPE_PRIVACY => $prefix.($locale === 'en' ? '/privacy' : '/datenschutz'),
            self::TYPE_GUIDE_OVERVIEW => $prefix.($locale === 'en' ? '/guides' : '/ratgeber'),
            self::TYPE_GUIDE => $prefix.($locale === 'en' ? '/guides/' : '/ratgeber/').$this->slug,
            self::TYPE_SEO => $prefix.($locale === 'en' ? '/search-engine-optimization' : '/suchmaschinenoptimierung'),
            self::TYPE_SEA => $prefix.($locale === 'en' ? '/search-engine-advertising' : '/suchmaschinenwerbung'),
            self::TYPE_MAINTENANCE => $prefix.($locale === 'en' ? '/hosting-maintenance' : '/betrieb-hosting-wartung'),
            self::TYPE_LOCAL_HUB => '/in/',
            self::TYPE_LOCAL => '/in/'.$this->slug.'/',
            default => $prefix.'/'.$this->slug,
        };
    }

    /**
     * Find a page by hierarchical slug path
     */
    public static function findByHierarchicalSlug(string $fullPath): ?self
    {
        $segments = explode('/', trim($fullPath, '/'));
        $locale = app()->getLocale();
        $page = null;
        $parentId = null;

        foreach ($segments as $segment) {
            $page = self::active()
                ->where("slug->{$locale}", $segment)
                ->where('parent_id', $parentId)
                ->first();

            if (! $page) {
                return null;
            }

            $parentId = $page->id;
        }

        return $page;
    }

    /**
     * Get hub pages for navigation menu
     *
     * @return Collection<int, Page>
     */
    public static function getHubPagesForMenu(): Collection
    {
        $locale = app()->getLocale();

        return Cache::remember(
            "page.{$locale}.hub_pages_menu",
            now()->addHours(24),
            fn () => self::active()
                ->whereIn('type', [self::TYPE_SOLUTION_HUB, self::TYPE_SEO, self::TYPE_SEA])
                ->whereNull('parent_id')
                ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get()
        );
    }
}
