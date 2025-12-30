<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogArticle extends Model
{
    /** @use HasFactory<\Database\Factories\BlogArticleFactory> */
    use HasFactory;

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
            'sections' => 'array',
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
        return $query->where('category', $category);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->published_at?->translatedFormat('d. F Y') ?? '';
    }

    public function getReadTimeTextAttribute(): string
    {
        return $this->read_time . ' Min.';
    }
}
