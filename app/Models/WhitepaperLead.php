<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhitepaperLead extends Model
{
    protected $fillable = [
        'whitepaper_slug',
        'email',
        'name',
        'company',
        'role',
        'locale',
        'ip',
        'user_agent',
        'sent_at',
        'newsletter_opt_in',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'newsletter_opt_in' => 'boolean',
        ];
    }
}
