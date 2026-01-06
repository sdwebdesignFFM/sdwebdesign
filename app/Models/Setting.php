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
        'bank_name',
        'bank_iban',
        'bank_bic',
        'website_url',
        'imprint_extra',
        'agb_content',
        'default_meta_title',
        'default_meta_description',
        'cta_image',
        'cta_title',
        'cta_subtitle',
        'cta_name',
        'cta_role',
        'cta_button_text',
        'cta_secondary_button_text',
        'admin_signer_name',
        'admin_signer_position',
        'admin_signature_data',
        'default_hourly_rate',
    ];

    protected function casts(): array
    {
        return [
            'default_hourly_rate' => 'decimal:2',
        ];
    }

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

    /**
     * Check if admin signature is configured for auto counter-signing.
     */
    public function hasAdminSignature(): bool
    {
        return ! empty($this->admin_signature_data)
            && ! empty($this->admin_signer_name)
            && ! empty($this->admin_signer_position);
    }
}
