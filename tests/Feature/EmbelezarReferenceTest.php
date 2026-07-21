<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmbelezarReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'embelezar-kosmetikinstitut-nextjs-seo';

    private const MIGRATION = 'migrations/2026_07_21_211943_add_embelezar_reference.php';

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
                            'icon' => 'shopping-cart',
                            'number' => '01',
                            'title' => 'PureHeat-Online — WooCommerce-B2B-Shop',
                            'client' => 'PureHeat-Online',
                            'detail_slug' => 'pureheat-online-woocommerce-b2b-shop',
                        ],
                        [
                            'icon' => 'trending-up',
                            'number' => '02',
                            'title' => 'Dino Hair — Friseursalon-Website',
                            'client' => 'Dino Hair',
                            'detail_slug' => 'dino-hair-friseursalon-frankfurt',
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
            'slug' => ['de' => 'dino-hair-friseursalon-frankfurt', 'en' => 'dino-hair-friseursalon-frankfurt'],
            'title' => ['de' => 'Dino Hair'],
            'is_active' => true,
            'sort_order' => 10,
            'content' => ['de' => []],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path(self::MIGRATION);
        $migration->up();
    }

    public function test_migration_creates_detail_page(): void
    {
        $overview = $this->seedReferencesOverview();

        $this->runMigration();

        $page = Page::firstWhere('slug->de', self::SLUG);
        $this->assertNotNull($page);
        $this->assertSame($overview->id, $page->parent_id);
        $this->assertTrue($page->is_active);
        $this->assertStringContainsString('Embelezar', $page->getTranslation('title', 'de'));
    }

    public function test_detail_sort_order_is_after_existing_references(): void
    {
        $overview = $this->seedReferencesOverview();
        $this->seedExistingDetailPage($overview);

        $this->runMigration();

        $this->assertSame(11, Page::firstWhere('slug->de', self::SLUG)->sort_order);
    }

    public function test_detail_section_shapes_are_render_safe(): void
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

        $this->assertCount(4, $content['features']);
        foreach ($content['features'] as $feature) {
            $this->assertIsString($feature['title']);
            $this->assertIsArray($feature['items']);
            $this->assertStringStartsWith('/images/references/embelezar/', $feature['image']);
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
        $this->assertSame('Embelezar Kosmetikinstitut', $last['client']);
        $this->assertSame('03', $last['number']);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();
        $this->runMigration();

        $count = Page::where('type', Page::TYPE_REFERENCE_DETAIL)->where('slug->de', self::SLUG)->count();
        $this->assertSame(1, $count);

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

    public function test_detail_page_renders_with_core_sections(): void
    {
        $this->seedReferencesOverview();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('Embelezar', false);
        $response->assertSee('Next.js', false);
        $response->assertSee('SEO-Landingpages', false);
        $response->assertSee('Gutschein-Shop', false);
    }
}
