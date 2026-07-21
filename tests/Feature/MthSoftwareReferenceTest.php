<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MthSoftwareReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'mth-software-wordpress-plattform';

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
                            'icon' => 'shopping-cart',
                            'number' => '02',
                            'title' => 'Gewapur.de – B2C E-Commerce Plattform',
                            'client' => 'Wasseraufbereitungs-Unternehmen',
                            'detail_slug' => 'gewapur-ecommerce',
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function seedExistingDetailPage(Page $overview): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'parent_id' => $overview->id,
            'slug' => ['de' => 'zeiterfassung-einsatzplanung', 'en' => 'zeiterfassung-einsatzplanung'],
            'title' => ['de' => 'Normatec — Workforce-Management-Plattform'],
            'is_active' => true,
            'sort_order' => 4,
            'content' => ['de' => []],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_07_20_205946_add_mth_software_wordpress_reference.php');
        $migration->up();
    }

    private function runMigrationDown(): void
    {
        $migration = require database_path('migrations/2026_07_20_205946_add_mth_software_wordpress_reference.php');
        $migration->down();
    }

    public function test_migration_creates_mth_detail_page(): void
    {
        $overview = $this->seedReferencesOverview();

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG)
            ->first();

        $this->assertNotNull($page);
        $this->assertSame($overview->id, $page->parent_id);
        $this->assertTrue($page->is_active);
        $this->assertSame('mth-software-wordpress-platform', $page->getTranslation('slug', 'en'));
        $this->assertStringContainsString('MTH Software', $page->getTranslation('title', 'de'));
    }

    public function test_detail_sort_order_is_after_existing_references(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $page = Page::firstWhere('slug->de', self::SLUG);

        $this->assertSame(5, $page->sort_order);
    }

    public function test_detail_section_shapes_are_render_safe(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $content = Page::firstWhere('slug->de', self::SLUG)->getTranslation('content', 'de');

        // Flat string lists — the blade passes each item straight into e()/{{ }}.
        foreach (['tech_stack', 'technologies', 'impact_results'] as $key) {
            $this->assertNotEmpty($content[$key]);
            foreach ($content[$key] as $item) {
                $this->assertIsString($item, "{$key} must be a flat list of strings");
            }
        }

        // Object lists — must expose the keys the blade reads.
        $this->assertCount(4, $content['features']);
        foreach ($content['features'] as $feature) {
            $this->assertIsString($feature['title']);
            $this->assertIsArray($feature['items']);
            $this->assertStringStartsWith('/images/references/mth-software/', $feature['image']);
        }
        foreach ($content['technical_details'] as $detail) {
            $this->assertIsString($detail['title']);
            $this->assertIsString($detail['icon']);
            $this->assertIsArray($detail['items']);
        }

        // Results grid — {value, label} objects.
        foreach ($content['results'] as $result) {
            $this->assertArrayHasKey('value', $result);
            $this->assertArrayHasKey('label', $result);
        }
    }

    public function test_mth_is_appended_as_last_overview_project(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];

        $last = end($projects);

        $this->assertSame(self::SLUG, $last['detail_slug']);
        $this->assertSame('MTH Software GmbH & Co. KG', $last['client']);
        $this->assertSame('03', $last['number']);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();
        $this->runMigration();

        $detailCount = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG)
            ->count();
        $this->assertSame(1, $detailCount);

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];
        $mthEntries = array_filter($projects, fn ($p) => ($p['detail_slug'] ?? null) === self::SLUG);
        $this->assertCount(1, $mthEntries);
    }

    public function test_migration_is_noop_without_overview_page(): void
    {
        $this->runMigration();

        $this->assertNull(Page::where('slug->de', self::SLUG)->first());
    }

    public function test_down_removes_the_reference(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();
        $this->runMigrationDown();

        $this->assertNull(Page::where('slug->de', self::SLUG)->first());

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];
        $mthEntries = array_filter($projects, fn ($p) => ($p['detail_slug'] ?? null) === self::SLUG);
        $this->assertCount(0, $mthEntries);
    }

    public function test_overview_page_renders_mth_last(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $response = $this->get('/referenzen');
        $response->assertStatus(200);

        $html = $response->getContent();
        $normatecPos = strpos($html, 'Normatec');
        $mthPos = strpos($html, 'MTH Software');

        $this->assertNotFalse($mthPos, 'MTH Software must appear on the overview page');
        $this->assertNotFalse($normatecPos);
        $this->assertGreaterThan($normatecPos, $mthPos, 'MTH must appear after Normatec in DOM order');
    }

    public function test_detail_page_renders_with_core_sections(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('MTH Software');
        $response->assertSee('Bestellsystem mit Conditional Logic');
        $response->assertSee('Custom-Plugin: Roadmap');
        $response->assertSee('Meta Box', false);
    }
}
