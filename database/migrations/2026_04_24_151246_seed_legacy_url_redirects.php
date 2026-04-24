<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed 301 redirects for URLs that still appear in Google's index and
     * in rank-tracker data but currently 404 on live. Leaving them as 404
     * bleeds any remaining link equity and wastes impressions.
     *
     * Uses upsert so this runs safely even if operators have already entered
     * these redirects manually via the Filament admin.
     */
    public function up(): void
    {
        $now = now();

        $rows = [
            [
                'from_url' => '/services/wordpress-webdesign-frankfurt',
                'to_url' => '/in/frankfurt-am-main',
                'notes' => 'Legacy WordPress service page — still in SERanking, now 404.',
            ],
            [
                'from_url' => '/services/webdesign-frankfurt',
                'to_url' => '/in/frankfurt-am-main',
                'notes' => 'Legacy WordPress service page — still in SERanking, now 404.',
            ],
            [
                'from_url' => '/lp/webdesign-agentur-frankfurt',
                'to_url' => '/in/frankfurt-am-main',
                'notes' => 'Legacy Ads landing page — still in SERanking, now 404.',
            ],
            [
                'from_url' => '/services/professionelles-webdesign-fuer-unternehmen',
                'to_url' => '/loesungen/websites',
                'notes' => 'Legacy WordPress service page — now 404.',
            ],
            [
                'from_url' => '/google-adwords-optimieren',
                'to_url' => '/suchmaschinenwerbung',
                'notes' => 'Legacy AdWords page — now 404.',
            ],
        ];

        $payload = array_map(fn ($r) => [
            'from_url' => $r['from_url'],
            'to_url' => $r['to_url'],
            'status_code' => 301,
            'is_active' => true,
            'notes' => $r['notes'],
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows);

        DB::table('redirects')->upsert(
            $payload,
            ['from_url'],
            ['to_url', 'status_code', 'is_active', 'notes', 'updated_at']
        );
    }

    public function down(): void
    {
        DB::table('redirects')->whereIn('from_url', [
            '/services/wordpress-webdesign-frankfurt',
            '/services/webdesign-frankfurt',
            '/lp/webdesign-agentur-frankfurt',
            '/services/professionelles-webdesign-fuer-unternehmen',
            '/google-adwords-optimieren',
        ])->delete();
    }
};
