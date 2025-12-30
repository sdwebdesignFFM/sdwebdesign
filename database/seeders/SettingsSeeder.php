<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'sdWebdesign',
                'owner_name' => 'Steffen Fasselt',
                'tagline' => 'Digitale Systeme für Unternehmen',
                'email' => 'info@sdwebdesign.de',
                'phone' => '+49 152 538 2211 4',
                'mobile' => null,
                'street' => 'Hannah-Arendt-Str. 29',
                'postal_code' => '60438',
                'city' => 'Frankfurt am Main',
                'country' => 'Deutschland',
                'business_hours' => 'Mo - Fr 8:00 bis 12:00',
            ]
        );
    }
}
