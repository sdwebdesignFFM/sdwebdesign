<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioVmReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'portfolio-vermoegensmanagement-wordpress-theme';

    private const MIGRATION = 'migrations/2026_07_21_184049_add_portfolio_vm_wordpress_reference.php';

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
                            'icon' => 'code',
                            'number' => '01',
                            'title' => 'MTH Software — WordPress-Plattform mit Custom-Plugins',
                            'client' => 'MTH Software GmbH & Co. KG',
                            'detail_slug' => 'mth-software-wordpress-plattform',
                        ],
                        [
                            'icon' => 'book-open',
                            'number' => '02',
                            'title' => 'change active – AKADEMIE',
                            'client' => 'change active – AKADEMIE (Peter Reitz)',
                            'detail_slug' => 'change-active-akademie-heilpraktikerschule',
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
            'slug' => ['de' => 'change-active-akademie-heilpraktikerschule', 'en' => 'change-active-academy-naturopath-school'],
            'title' => ['de' => 'change active – AKADEMIE'],
            'is_active' => true,
            'sort_order' => 6,
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
        $this->assertStringContainsString('portfolio vermögensmanagement', $page->getTranslation('title', 'de'));
    }

    public function test_detail_sort_order_is_after_existing_references(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $this->assertSame(7, Page::firstWhere('slug->de', self::SLUG)->sort_order);
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
            $this->assertStringStartsWith('/images/references/portfolio-vm/', $feature['image']);
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
        $this->assertSame('portfolio vermögensmanagement', $last['client']);
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

    public function test_overview_page_renders_portfolio_vm_last(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $response = $this->get('/referenzen');
        $response->assertStatus(200);

        $html = $response->getContent();
        $changeActivePos = strpos($html, 'change active');
        $pvmPos = strpos($html, 'portfolio vermögensmanagement');

        $this->assertNotFalse($pvmPos, 'portfolio vermögensmanagement must appear on the overview page');
        $this->assertNotFalse($changeActivePos);
        $this->assertGreaterThan($changeActivePos, $pvmPos, 'portfolio vm must appear after change active in DOM order');
    }

    public function test_detail_page_renders_with_core_sections(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('portfolio vermögensmanagement');
        $response->assertSee('Konferenzplattform mit Tabs &amp; Agenda', false);
        $response->assertSee('Mailchimp', false);
        $response->assertSee('WCAG', false);
    }
}
