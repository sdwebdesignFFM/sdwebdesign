<?php

namespace App\Models;

use Database\Factories\BlogArticleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BlogArticle extends Model
{
    /** @use HasFactory<BlogArticleFactory> */
    use HasFactory;

    use HasTranslations;

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'intro',
        'sections',
        'conclusion',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'intro',
        'sections',
        'conclusion',
        'read_time',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        $locale = app()->getLocale();

        return $query->where("category->{$locale}", $category);
    }

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        $locale = app()->getLocale();

        return $query->where("slug->{$locale}", $slug);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->published_at?->translatedFormat('d. F Y') ?? '';
    }

    public function getReadTimeTextAttribute(): string
    {
        return $this->read_time.' Min.';
    }
}
