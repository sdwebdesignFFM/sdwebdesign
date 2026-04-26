<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlattformDiscoveryRestructureMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedHubAndDiscoveryWithBrokenShape(): void
    {
        $hub = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'is_active' => true,
            'sort_order' => 1,
            'content' => ['de' => []],
        ]);

        $original = require database_path('migrations/2026_04_25_174042_create_plattform_discovery_lead_magnet.php');
        $original->up();

        $this->assertSame(
            $hub->id,
            Page::where('slug->de', 'plattform-discovery')->first()->parent_id
        );
    }

    private function runRestructure(): void
    {
        $migration = require database_path('migrations/2026_04_26_084318_restructure_plattform_discovery_sections.php');
        $migration->up();
    }

    public function test_restructure_moves_phases_to_process_steps(): void
    {
        $this->seedHubAndDiscoveryWithBrokenShape();

        $this->runRestructure();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $process = $page->getTranslation('content', 'de')['process'];

        $this->assertSame('So läuft der Discovery-Workshop ab', $process['title']);
        $this->assertCount(3, $process['steps']);
        $this->assertSame('Vor dem Workshop', $process['steps'][0]['title']);
        $this->assertSame('Im Workshop (2 Stunden)', $process['steps'][1]['title']);
        $this->assertSame('Nach dem Workshop (Aufbereitung)', $process['steps'][2]['title']);
        $this->assertNotEmpty($process['steps'][2]['items']);
    }

    public function test_restructure_replaces_features_with_flat_string_preview(): void
    {
        $this->seedHubAndDiscoveryWithBrokenShape();

        $this->runRestructure();

        $page = Page::where('slug->de', 'plattform-discovery')->first();
        $features = $page->getTranslation('content', 'de')['features'];

        $this->assertSame('Was im Workshop passiert', $features['title']);
        $this->assertNotEmpty($features['items']);
        foreach ($features['items'] as $item) {
            $this->assertIsString($item);
        }
    }

    public function test_plattformen_hub_renders_after_restructure(): void
    {
        $this->seedHubAndDiscoveryWithBrokenShape();

        $this->runRestructure();

        $response = $this->get('/loesungen/plattformen');

        $response->assertStatus(200);
        $response->assertSee('Plattform-Discovery');
    }

    public function test_discovery_detail_page_renders_process_steps_with_items(): void
    {
        $this->seedHubAndDiscoveryWithBrokenShape();

        $this->runRestructure();

        $response = $this->get('/loesungen/plattformen/plattform-discovery');

        $response->assertStatus(200);
        $response->assertSee('So läuft der Discovery-Workshop ab');
        $response->assertSee('Vor dem Workshop');
        $response->assertSee('Briefing-Template per E-Mail');
        $response->assertSee('Discovery-Dokument als PDF');
    }

    public function test_hub_does_not_crash_when_child_features_are_objects(): void
    {
        // Regression guard for the Phase C bug: child page with `features`
        // as a list of nested objects must not 500 the hub.
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'slug' => ['de' => 'beispiel-hub', 'en' => 'example-hub'],
            'title' => ['de' => 'Hub', 'en' => 'Hub'],
            'is_active' => true,
            'content' => ['de' => ['hero' => ['title' => 'H']]],
        ]);
        $hub = Page::where('slug->de', 'beispiel-hub')->whereNull('parent_id')->first();

        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'slug' => ['de' => 'kaputt', 'en' => 'broken'],
            'title' => ['de' => 'Kaputt', 'en' => 'Broken'],
            'is_active' => true,
            'sort_order' => 1,
            'content' => [
                'de' => [
                    'hero' => ['tagline' => 'x', 'description' => 'y'],
                    'features' => [
                        ['title' => 'Phase 1', 'description' => 'd', 'items' => ['a', 'b']],
                        ['title' => 'Phase 2', 'description' => 'd', 'items' => ['c']],
                    ],
                ],
            ],
        ]);

        $response = $this->get('/loesungen/beispiel-hub');

        $response->assertStatus(200);
    }
}
