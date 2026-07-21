<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KosmetikerinSaasReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'kosmetikerin-ecommerce-app';

    private const MIGRATION = 'migrations/2026_07_21_210252_overhaul_kosmetikerin_reference_to_saas.php';

    private function seedReferencesOverview(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCES,
            'slug' => ['de' => 'referenzen', 'en' => 'references'],
            'title' => ['de' => 'Referenzen', 'en' => 'References'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Ausgewählte Projekte'],
                    'projects' => [
                        [
                            'icon' => 'cpu',
                            'number' => '01',
                            'title' => 'Normatec — Workforce-Management-Plattform',
                            'client' => 'Normatec',
                            'detail_slug' => 'zeiterfassung-einsatzplanung',
                        ],
                        [
                            'icon' => 'sparkles',
                            'number' => '02',
                            'title' => 'Kosmetikerin.org – E-Commerce Platform + iOS CRM',
                            'client' => 'Selbstständige Kosmetikerin',
                            'detail_slug' => self::SLUG,
                        ],
                        [
                            'icon' => 'shopping-cart',
                            'number' => '03',
                            'title' => 'Gewapur.de – B2C E-Commerce Plattform',
                            'client' => 'Wasseraufbereitungs-Unternehmen',
                            'detail_slug' => 'gewapur-ecommerce',
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function seedOldKosmetikerinDetailPage(Page $overview): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'parent_id' => $overview->id,
            'slug' => ['de' => self::SLUG, 'en' => 'cosmetician-ecommerce-app'],
            'title' => ['de' => 'Kosmetikerin.org – E-Commerce Platform + iOS CRM'],
            'is_active' => true,
            'sort_order' => 3,
            'content' => [
                'de' => [
                    'features' => [
                        ['title' => 'Shop-Katalog', 'items' => ['x'], 'mockup' => 'cosmetics-shop:catalog'],
                    ],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path(self::MIGRATION);
        $migration->up();
    }

    public function test_detail_page_is_overhauled_to_saas(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedOldKosmetikerinDetailPage($overview);

        $this->runMigration();

        $page = Page::firstWhere('slug->de', self::SLUG);
        $this->assertStringContainsString('SaaS', $page->getTranslation('title', 'de'));
        $this->assertStringNotContainsString('E-Commerce', $page->getTranslation('title', 'de'));
        $this->assertSame(0, $page->sort_order);
    }

    public function test_features_use_real_images_not_mockups(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $features = Page::firstWhere('slug->de', self::SLUG)->getTranslation('content', 'de')['features'];

        $this->assertCount(4, $features);
        foreach ($features as $feature) {
            $this->assertArrayNotHasKey('mockup', $feature);
            $this->assertStringStartsWith('/images/references/kosmetikerin/', $feature['image']);
        }
    }

    public function test_section_shapes_are_render_safe(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $content = Page::firstWhere('slug->de', self::SLUG)->getTranslation('content', 'de');

        foreach (['tech_stack', 'technologies', 'impact_results'] as $key) {
            $this->assertNotEmpty($content[$key]);
            foreach ($content[$key] as $item) {
                $this->assertIsString($item);
            }
        }
        foreach ($content['technical_details'] as $detail) {
            $this->assertIsString($detail['title']);
            $this->assertIsString($detail['icon']);
            $this->assertIsArray($detail['items']);
        }
        foreach ($content['results'] as $result) {
            $this->assertArrayHasKey('value', $result);
            $this->assertArrayHasKey('label', $result);
        }
    }

    public function test_kosmetikerin_is_moved_to_first_and_others_renumbered(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];

        $this->assertCount(3, $projects);
        $this->assertSame(self::SLUG, $projects[0]['detail_slug']);
        $this->assertSame('Kosmetikerin.org', $projects[0]['client']);
        $this->assertSame('01', $projects[0]['number']);
        $this->assertStringContainsString('SaaS', $projects[0]['title']);

        // The previously-first Normatec is now second
        $this->assertStringContainsString('Normatec', $projects[1]['title']);
        $this->assertSame('02', $projects[1]['number']);
        $this->assertSame('03', $projects[2]['number']);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();
        $this->runMigration();

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];
        $this->assertCount(3, $projects);
        $entries = array_filter($projects, fn ($p) => ($p['detail_slug'] ?? null) === self::SLUG);
        $this->assertCount(1, $entries);
        $this->assertSame(self::SLUG, $projects[0]['detail_slug']);
    }

    public function test_migration_is_noop_without_overview_page(): void
    {
        $this->runMigration();

        $this->assertNull(Page::where('slug->de', self::SLUG)->first());
    }

    public function test_overview_page_renders_kosmetikerin_first(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedOldKosmetikerinDetailPage($overview);

        $this->runMigration();

        $response = $this->get('/referenzen');
        $response->assertStatus(200);

        $html = $response->getContent();
        $kosPos = strpos($html, 'Kosmetikerin.org');
        $normatecPos = strpos($html, 'Normatec');

        $this->assertNotFalse($kosPos);
        $this->assertNotFalse($normatecPos);
        $this->assertLessThan($normatecPos, $kosPos, 'Kosmetikerin must appear before Normatec in DOM order');
    }

    public function test_detail_page_renders_with_new_saas_content(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('SaaS', false);
        $response->assertSee('Öffentliches Studio-Verzeichnis', false);
        $response->assertSee('Native iOS-App', false);
        $response->assertSee('Kalender &amp; Online-Buchungen', false);
    }
}
