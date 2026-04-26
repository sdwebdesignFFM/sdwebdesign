<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopRequest extends Model
{
    protected $fillable = [
        'workshop_slug',
        'trigger_question',
        'industry',
        'workflow_areas',
        'existing_systems',
        'procurement_stage',
        'budget_indication',
        'go_live_timeline',
        'workshop_format',
        'preferred_timing',
        'preferred_daytime',
        'name',
        'email',
        'phone',
        'company',
        'role',
        'company_size',
        'briefing_notes',
        'locale',
        'ip',
        'user_agent',
        'admin_notified_at',
        'confirmation_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'workflow_areas' => 'array',
            'existing_systems' => 'array',
            'admin_notified_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
        ];
    }
}
