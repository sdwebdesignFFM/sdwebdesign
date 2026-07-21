<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RosinusPartnerReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'rosinus-partner-anwaltskanzlei-website';

    private const MIGRATION = 'migrations/2026_07_21_191526_add_rosinus_partner_wordpress_reference.php';

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
                            'icon' => 'book-open',
                            'number' => '01',
                            'title' => 'change active – AKADEMIE',
                            'client' => 'change active – AKADEMIE (Peter Reitz)',
                            'detail_slug' => 'change-active-akademie-heilpraktikerschule',
                        ],
                        [
                            'icon' => 'calendar',
                            'number' => '02',
                            'title' => 'portfolio vermögensmanagement',
                            'client' => 'portfolio vermögensmanagement',
                            'detail_slug' => 'portfolio-vermoegensmanagement-wordpress-theme',
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
            'slug' => ['de' => 'portfolio-vermoegensmanagement-wordpress-theme', 'en' => 'portfolio-vermoegensmanagement-wordpress-theme'],
            'title' => ['de' => 'portfolio vermögensmanagement'],
            'is_active' => true,
            'sort_order' => 7,
            'content' => ['de' => []],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path(self::MIGRATION);
        $migration->up();
    }

    private function runMigrationDown(): void
    {
        $migration = require database_path(self::MIGRATION);
        $migration->down();
    }

    public function test_migration_creates_detail_page(): void
    {
        $overview = $this->seedReferencesOverview();

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->where('slug->de', self::SLUG)
            ->first();

        $this->assertNotNull($page);
        $this->assertSame($overview->id, $page->parent_id);
        $this->assertTrue($page->is_active);
        $this->assertStringContainsString('Rosinus', $page->getTranslation('title', 'de'));
    }

    public function test_detail_sort_order_is_after_existing_references(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $this->assertSame(8, Page::firstWhere('slug->de', self::SLUG)->sort_order);
    }

    public function test_detail_section_shapes_are_render_safe(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $content = Page::firstWhere('slug->de', self::SLUG)->getTranslation('content', 'de');

        foreach (['tech_stack', 'technologies', 'impact_results'] as $key) {
            $this->assertNotEmpty($content[$key]);
            foreach ($content[$key] as $item) {
                $this->assertIsString($item, "{$key} must be a flat list of strings");
            }
        }

        $this->assertCount(4, $content['features']);
        foreach ($content['features'] as $feature) {
            $this->assertIsString($feature['title']);
            $this->assertIsArray($feature['items']);
            $this->assertStringStartsWith('/images/references/rosinus-partner/', $feature['image']);
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

    public function test_is_appended_as_last_overview_project(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];

        $last = end($projects);

        $this->assertSame(self::SLUG, $last['detail_slug']);
        $this->assertStringContainsString('Rosinus', $last['client']);
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
        $entries = array_filter($projects, fn ($p) => ($p['detail_slug'] ?? null) === self::SLUG);
        $this->assertCount(1, $entries);
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
        $entries = array_filter($projects, fn ($p) => ($p['detail_slug'] ?? null) === self::SLUG);
        $this->assertCount(0, $entries);
    }

    public function test_overview_page_renders_rosinus_last(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $response = $this->get('/referenzen');
        $response->assertStatus(200);

        $html = $response->getContent();
        $pvmPos = strpos($html, 'portfolio vermögensmanagement');
        $rosinusPos = strpos($html, 'Rosinus');

        $this->assertNotFalse($rosinusPos, 'Rosinus must appear on the overview page');
        $this->assertNotFalse($pvmPos);
        $this->assertGreaterThan($pvmPos, $rosinusPos, 'Rosinus must appear after portfolio vm in DOM order');
    }

    public function test_detail_page_renders_with_core_sections(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('Rosinus', false);
        $response->assertSee('Custom-Theme nach Designer-Vorlage', false);
        $response->assertSee('WPML', false);
        $response->assertSee('Elementor Pro', false);
    }
}
