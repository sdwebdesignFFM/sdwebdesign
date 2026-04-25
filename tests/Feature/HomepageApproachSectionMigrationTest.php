<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageApproachSectionMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedHomepage(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Hero', 'subtitle' => 'Sub'],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_25_140644_add_homepage_approach_section.php');
        $migration->up();
    }

    public function test_migration_adds_approach_section_with_full_payload(): void
    {
        $home = $this->seedHomepage();

        $this->runMigration();

        $home->refresh();
        $content = $home->getTranslation('content', 'de');

        $this->assertArrayHasKey('approach', $content);
        $this->assertSame('Technischer Partner für digitale Systeme und Prozesse', $content['approach']['title']);
        $this->assertStringContainsString('Steffen Fasselt', $content['approach']['text']);
        $this->assertStringContainsString('20 Jahren', $content['approach']['text']);
        $this->assertSame('/ueber-uns', $content['approach']['cta_link']);
    }

    public function test_migration_includes_normatec_case_teaser(): void
    {
        $this->seedHomepage();

        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $teaser = $home->getTranslation('content', 'de')['approach']['case_teaser'];

        $this->assertStringContainsString('Normatec', $teaser['title']);
        $this->assertSame('/referenzen/zeiterfassung-einsatzplanung', $teaser['link']);
        $this->assertContains('Laravel', $teaser['tags']);
        $this->assertContains('Filament', $teaser['tags']);
    }

    public function test_migration_does_not_touch_other_homepage_sections(): void
    {
        $home = Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'My hero'],
                    'problem' => ['title' => 'Don\'t touch me', 'items' => ['a', 'b']],
                    'solutions' => ['title' => 'Existing solutions'],
                ],
            ],
        ]);

        $this->runMigration();

        $home->refresh();
        $content = $home->getTranslation('content', 'de');
        $this->assertSame('My hero', $content['hero']['title']);
        $this->assertSame('Don\'t touch me', $content['problem']['title']);
        $this->assertSame(['a', 'b'], $content['problem']['items']);
        $this->assertSame('Existing solutions', $content['solutions']['title']);
    }

    public function test_homepage_renders_approach_section_after_migration(): void
    {
        $this->seedHomepage();

        $this->runMigration();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Technischer Partner für digitale Systeme und Prozesse');
        $response->assertSee('Was uns ausmacht');
        $response->assertSee('Normatec');
        $response->assertSee('Aktuelle Plattform-Begleitung');
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedHomepage();

        $this->runMigration();
        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $approach = $home->getTranslation('content', 'de')['approach'];
        $this->assertSame('Technischer Partner für digitale Systeme und Prozesse', $approach['title']);
    }

    public function test_migration_down_removes_approach_section(): void
    {
        $this->seedHomepage();
        $this->runMigration();

        $migration = require database_path('migrations/2026_04_25_140644_add_homepage_approach_section.php');
        $migration->down();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $this->assertArrayNotHasKey('approach', $home->getTranslation('content', 'de'));
    }
}
