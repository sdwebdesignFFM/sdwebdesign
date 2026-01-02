<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'tagline',
        'cta_title',
        'cta_subtitle',
        'cta_button_text',
        'cta_secondary_button_text',
        'default_meta_title',
        'default_meta_description',
    ];

    protected $fillable = [
        'company_name',
        'owner_name',
        'tagline',
        'email',
        'phone',
        'mobile',
        'street',
        'postal_code',
        'city',
        'country',
        'business_hours',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'twitter_url',
        'xing_url',
        'github_url',
        'vat_id',
        'tax_number',
        'imprint_extra',
        'default_meta_title',
        'default_meta_description',
        'cta_image',
        'cta_title',
        'cta_subtitle',
        'cta_name',
        'cta_role',
        'cta_button_text',
        'cta_secondary_button_text',
    ];

    /**
     * Get the singleton instance of settings.
     */
    public static function instance(): self
    {
        return Cache::rememberForever('settings', function () {
            return self::firstOrCreate(['id' => 1]);
        });
    }

    /**
     * Clear the settings cache when updated.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('settings');
        });
    }

    /**
     * Get the full address as a formatted string.
     */
    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->street,
            "{$this->postal_code} {$this->city}",
            $this->country,
        ])->filter()->implode("\n");
    }
}
