<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlattformDiscoveryMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedPlattformenHub(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'is_active' => true,
            'sort_order' => 1,
            'content' => ['de' => []],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_25_174042_create_plattform_discovery_lead_magnet.php');
        $migration->up();
    }

    public function test_migration_creates_discovery_page_under_plattformen_hub(): void
    {
        $hub = $this->seedPlattformenHub();

        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();

        $this->assertNotNull($page);
        $this->assertSame(Page::TYPE_SOLUTION_DETAIL, $page->type);
        $this->assertSame($hub->id, $page->parent_id);
        $this->assertTrue($page->is_active);
        $this->assertSame(1, $page->sort_order);
    }

    public function test_discovery_page_has_correct_title_and_meta(): void
    {
        $this->seedPlattformenHub();

        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $this->assertStringContainsString('Plattform-Discovery', $page->getTranslation('title', 'de'));
        $this->assertStringContainsString('990', $page->getTranslation('meta_title', 'de'));
        $this->assertStringContainsString('990', $page->getTranslation('meta_description', 'de'));
    }

    public function test_discovery_content_advertises_fixed_price_and_workshop_format(): void
    {
        $this->seedPlattformenHub();

        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $content = $page->getTranslation('content', 'de');

        $metaItems = collect($content['meta']);
        $price = $metaItems->firstWhere('label', 'Preis');
        $format = $metaItems->firstWhere('label', 'Format');

        $this->assertStringContainsString('990 €', $price['value']);
        $this->assertStringContainsString('Festpreis', $price['value']);
        $this->assertStringContainsString('2-Stunden-Workshop', $format['value']);
    }

    public function test_discovery_explains_what_buyers_get_documented_output(): void
    {
        $this->seedPlattformenHub();

        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $whyNative = $page->getTranslation('content', 'de')['why_native'];

        $items = implode(' ', $whyNative['items']);
        $this->assertStringContainsString('Anforderungs-Liste', $items);
        $this->assertStringContainsString('Tech-Stack', $items);
        $this->assertStringContainsString('Aufwand-Schätzung', $items);
        $this->assertStringContainsString('Roadmap', $items);
        $this->assertStringContainsString('Risiko', $items);
    }

    public function test_discovery_page_renders_at_hub_child_url(): void
    {
        $this->seedPlattformenHub();

        $this->runMigration();

        $response = $this->get('/loesungen/plattformen/plattform-discovery');

        $response->assertStatus(200);
        $response->assertSee('Plattform-Discovery');
        $response->assertSee('990');
        $response->assertSee('Workshop anfragen');
        $response->assertSee('Was Sie aus dem Workshop mitnehmen');
    }

    public function test_migration_skips_when_plattformen_hub_missing(): void
    {
        // No hub seeded — migration should not crash, just skip
        $this->runMigration();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $this->assertNull($page);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedPlattformenHub();

        $this->runMigration();
        $this->runMigration();

        $count = Page::where('slug->de', 'plattform-discovery')->count();
        $this->assertSame(1, $count);
    }

    public function test_migration_down_removes_discovery_page(): void
    {
        $this->seedPlattformenHub();
        $this->runMigration();

        $migration = require database_path('migrations/2026_04_25_174042_create_plattform_discovery_lead_magnet.php');
        $migration->down();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $this->assertNull($page);
    }
}
