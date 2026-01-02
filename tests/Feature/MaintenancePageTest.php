<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenancePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create maintenance page
        Page::factory()->create([
            'slug' => ['de' => 'betrieb-hosting-wartung', 'en' => 'hosting-maintenance'],
            'title' => ['de' => 'Betrieb, Hosting & Wartung', 'en' => 'Hosting & Maintenance'],
            'type' => Page::TYPE_MAINTENANCE,
            'is_active' => true,
            'sort_order' => 7,
            'content' => [
                'de' => [
                    'hero' => [
                        'number' => '07',
                        'title' => 'Betrieb, Hosting & Wartung',
                        'intro' => 'Wir sorgen dafür, dass Ihre digitale Lösung dauerhaft stabil bleibt.',
                        'icon' => 'server-stack',
                    ],
                    'when_useful' => [
                        'title' => 'Wann Betrieb & Wartung sinnvoll sind',
                        'conditions' => ['Zuverlässig erreichbar', 'Updates automatisch'],
                    ],
                    'approach' => [
                        'title' => 'Wie wir Betrieb aufsetzen',
                        'steps' => [
                            ['number' => '01', 'title' => 'Bestandsaufnahme', 'text' => 'Analyse'],
                            ['number' => '02', 'title' => 'Setup', 'text' => 'Migration'],
                        ],
                    ],
                    'infrastructure' => [
                        'title' => 'Infrastruktur & Tooling',
                        'items' => [
                            ['title' => 'Hetzner', 'text' => 'Europäische VPS'],
                        ],
                    ],
                    'services' => [
                        'title' => 'Was wir im Betrieb übernehmen',
                        'categories' => [
                            ['title' => 'Updates', 'items' => ['CMS-Updates']],
                        ],
                    ],
                    'models' => [
                        'title' => 'Betriebsmodelle',
                        'items' => [
                            ['title' => 'Basis', 'description' => 'Für kleinere Websites', 'features' => ['Hosting']],
                        ],
                    ],
                    'differentiation' => [
                        'title' => 'Kein Standard-Hosting',
                        'text' => 'Wir sind Ihr technischer Partner.',
                    ],
                    'cta' => [
                        'title' => 'Lassen Sie uns über Ihren Betrieb sprechen',
                        'text' => 'Wir beraten Sie gerne.',
                        'button_text' => 'Projekt besprechen',
                    ],
                ],
            ],
        ]);

        // Create contact page for CTA link
        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        // Create solutions page for breadcrumb
        Page::factory()->create([
            'slug' => ['de' => 'loesungen', 'en' => 'solutions'],
            'title' => ['de' => 'Lösungen', 'en' => 'Solutions'],
            'type' => Page::TYPE_SOLUTIONS,
            'is_active' => true,
            'content' => ['de' => ['hero' => ['title' => 'Lösungen']]],
        ]);
    }

    public function test_maintenance_page_is_accessible(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Betrieb, Hosting', false);
        $response->assertSee('Wartung', false);
    }

    public function test_maintenance_page_shows_hero_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Wir sorgen dafür, dass Ihre digitale Lösung dauerhaft stabil bleibt.');
    }

    public function test_maintenance_page_shows_when_useful_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Wann Betrieb', false);
        $response->assertSee('Wartung sinnvoll sind', false);
    }

    public function test_maintenance_page_shows_approach_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Wie wir Betrieb aufsetzen');
        $response->assertSee('Bestandsaufnahme');
    }

    public function test_maintenance_page_shows_infrastructure_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Infrastruktur', false);
        $response->assertSee('Tooling', false);
        $response->assertSee('Hetzner');
    }

    public function test_maintenance_page_shows_services_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Was wir im Betrieb übernehmen');
    }

    public function test_maintenance_page_shows_models_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Betriebsmodelle');
        $response->assertSee('Basis');
    }

    public function test_maintenance_page_shows_differentiation_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Kein Standard-Hosting');
    }

    public function test_maintenance_page_shows_cta_section(): void
    {
        $response = $this->get('/betrieb-hosting-wartung');

        $response->assertStatus(200);
        $response->assertSee('Lassen Sie uns über Ihren Betrieb sprechen');
        $response->assertSee('Projekt besprechen');
    }

    public function test_maintenance_page_has_correct_url(): void
    {
        $maintenancePage = Page::where('type', Page::TYPE_MAINTENANCE)->first();

        $this->assertEquals('/betrieb-hosting-wartung', $maintenancePage->getUrl());
    }

    public function test_english_maintenance_page_is_accessible(): void
    {
        $response = $this->get('/en/hosting-maintenance');

        $response->assertStatus(200);
    }
}
