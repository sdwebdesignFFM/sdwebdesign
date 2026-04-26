<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoveryHeroCtaMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedHubAndDiscovery(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'is_active' => true,
            'sort_order' => 1,
            'content' => ['de' => []],
        ]);
        $createDiscovery = require database_path('migrations/2026_04_25_174042_create_plattform_discovery_lead_magnet.php');
        $createDiscovery->up();
        $route = require database_path('migrations/2026_04_26_111206_route_discovery_cta_to_workshop_modal.php');
        $route->up();
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_26_125714_add_hero_cta_and_hide_maintenance_on_discovery.php');
        $migration->up();
    }

    public function test_migration_adds_hero_cta_text_and_subtext(): void
    {
        $this->seedHubAndDiscovery();

        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $hero = $page->getTranslation('content', 'de')['hero'];

        $this->assertSame('Workshop anfragen', $hero['cta_text']);
        $this->assertStringContainsString('990', $hero['cta_subtext']);
    }

    public function test_migration_sets_hide_maintenance_flag(): void
    {
        $this->seedHubAndDiscovery();

        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $this->assertTrue($page->getTranslation('content', 'de')['hide_maintenance_block']);
    }

    public function test_discovery_page_renders_two_workshop_ctas(): void
    {
        $this->seedHubAndDiscovery();
        $this->runMigration();

        $body = $this->get('/loesungen/plattformen/plattform-discovery')->assertStatus(200)->getContent();

        // Hero CTA + bottom CTA = both should dispatch the workshop modal
        $matches = substr_count($body, 'data-modal-event="openWorkshopRequestModal"');
        $this->assertSame(2, $matches, 'Discovery page must surface CTA both at top and bottom');
    }

    public function test_discovery_page_does_not_render_maintenance_block(): void
    {
        $this->seedHubAndDiscovery();
        $this->runMigration();

        $body = $this->get('/loesungen/plattformen/plattform-discovery')->getContent();

        // Maintenance block always says "dauerhaft stabil, sicher und aktuell"
        $this->assertStringNotContainsString('dauerhaft stabil, sicher und aktuell', $body);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedHubAndDiscovery();

        $this->runMigration();
        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $this->assertSame('Workshop anfragen', $page->getTranslation('content', 'de')['hero']['cta_text']);
        $this->assertTrue($page->getTranslation('content', 'de')['hide_maintenance_block']);
    }
}
