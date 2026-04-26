<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SteffenLinkedinMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedAbout(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_ABOUT,
            'slug' => ['de' => 'ueber-uns', 'en' => 'about'],
            'title' => ['de' => 'Über uns', 'en' => 'About'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'team' => [
                        'members' => [
                            ['name' => 'Steffen Fasselt', 'role' => 'X', 'description' => 'Y', 'icon' => 'user'],
                            ['name' => 'Other Person', 'role' => 'Z', 'description' => 'W', 'icon' => 'users'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_26_103344_add_linkedin_anchor_for_steffen_on_about.php');
        $migration->up();
    }

    public function test_migration_sets_linkedin_field_only_on_steffen(): void
    {
        $this->seedAbout();

        $this->runMigration();

        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        $members = $about->getTranslation('content', 'de')['team']['members'];

        $this->assertSame('https://www.linkedin.com/in/steffenfasselt/', $members[0]['linkedin']);
        $this->assertArrayNotHasKey('linkedin', $members[1]);
    }

    public function test_migration_seeds_settings_linkedin_url_when_empty(): void
    {
        $this->seedAbout();
        Setting::create(['company_name' => 'sdWebdesign']);

        $this->runMigration();

        $this->assertSame(
            'https://www.linkedin.com/in/steffenfasselt/',
            Setting::first()->linkedin_url
        );
    }

    public function test_migration_does_not_overwrite_editor_set_settings_linkedin(): void
    {
        $this->seedAbout();
        Setting::create(['linkedin_url' => 'https://linkedin.com/in/editor-set/']);

        $this->runMigration();

        $this->assertSame(
            'https://linkedin.com/in/editor-set/',
            Setting::first()->linkedin_url
        );
    }

    public function test_migration_does_not_overwrite_existing_member_linkedin(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_ABOUT,
            'slug' => ['de' => 'ueber-uns', 'en' => 'about'],
            'title' => ['de' => 'Über uns', 'en' => 'About'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'team' => [
                        'members' => [
                            ['name' => 'Steffen Fasselt', 'linkedin' => 'https://linkedin.com/in/already-set/'],
                        ],
                    ],
                ],
            ],
        ]);

        $this->runMigration();

        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        $this->assertSame(
            'https://linkedin.com/in/already-set/',
            $about->getTranslation('content', 'de')['team']['members'][0]['linkedin']
        );
    }

    public function test_migration_skips_when_about_page_missing(): void
    {
        $this->runMigration();
        $this->assertNull(Page::where('type', Page::TYPE_ABOUT)->first());
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedAbout();

        $this->runMigration();
        $this->runMigration();

        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        $members = $about->getTranslation('content', 'de')['team']['members'];
        $this->assertSame('https://www.linkedin.com/in/steffenfasselt/', $members[0]['linkedin']);
    }
}
