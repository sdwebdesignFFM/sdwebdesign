<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class GuideCategory extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'name',
        'slug',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    /**
     * @return HasMany<Page, $this>
     */
    public function guides(): HasMany
    {
        return $this->hasMany(Page::class, 'guide_category_id')
            ->where('type', Page::TYPE_GUIDE)
            ->orderBy('sort_order');
    }
}
