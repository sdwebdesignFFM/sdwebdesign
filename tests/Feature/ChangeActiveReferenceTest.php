<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeActiveReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'change-active-akademie-heilpraktikerschule';

    private const MIGRATION = 'migrations/2026_07_21_091442_add_change_active_akademie_wordpress_reference.php';

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
                            'icon' => 'code',
                            'number' => '02',
                            'title' => 'MTH Software — WordPress-Plattform mit Custom-Plugins',
                            'client' => 'MTH Software GmbH & Co. KG',
                            'detail_slug' => 'mth-software-wordpress-plattform',
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
            'slug' => ['de' => 'mth-software-wordpress-plattform', 'en' => 'mth-software-wordpress-platform'],
            'title' => ['de' => 'MTH Software'],
            'is_active' => true,
            'sort_order' => 5,
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
        $this->assertSame('change-active-academy-naturopath-school', $page->getTranslation('slug', 'en'));
        $this->assertStringContainsString('change active', $page->getTranslation('title', 'de'));
    }

    public function test_detail_sort_order_is_after_existing_references(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $this->assertSame(6, Page::firstWhere('slug->de', self::SLUG)->sort_order);
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
            $this->assertStringStartsWith('/images/references/change-active/', $feature['image']);
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
        $this->assertStringContainsString('change active', $last['client']);
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

    public function test_overview_page_renders_change_active_last(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $response = $this->get('/referenzen');
        $response->assertStatus(200);

        $html = $response->getContent();
        $mthPos = strpos($html, 'MTH Software');
        $caPos = strpos($html, 'change active');

        $this->assertNotFalse($caPos, 'change active must appear on the overview page');
        $this->assertNotFalse($mthPos);
        $this->assertGreaterThan($mthPos, $caPos, 'change active must appear after MTH in DOM order');
    }

    public function test_detail_page_renders_with_core_sections(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('change active');
        $response->assertSee('Bezahlschranke &amp; geschützter Kursbereich', false);
        $response->assertSee('WP Courseware', false);
        $response->assertSee('Bricks', false);
    }
}
