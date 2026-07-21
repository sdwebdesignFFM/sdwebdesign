<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DinoHairReframeReferenceTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'dino-hair-friseursalon-frankfurt';

    private const MIGRATION = 'migrations/2026_07_21_213139_reframe_dino_hair_reference_website_only.php';

    private function seedOverviewWithOldDino(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCES,
            'slug' => ['de' => 'referenzen', 'en' => 'references'],
            'title' => ['de' => 'Referenzen', 'en' => 'References'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'projects' => [
                        [
                            'icon' => 'shopping-cart',
                            'number' => '08',
                            'title' => 'PureHeat-Online',
                            'client' => 'PureHeat-Online',
                            'detail_slug' => 'pureheat-online-woocommerce-b2b-shop',
                        ],
                        [
                            'icon' => 'trending-up',
                            'number' => '09',
                            'title' => 'Dino Hair — Friseursalon-Website mit SEO- & Google-Ads-Betreuung',
                            'client' => 'Dino Hair',
                            'detail_slug' => self::SLUG,
                            'categories' => ['WordPress', 'SEO & Google Ads', 'Friseursalon'],
                        ],
                        [
                            'icon' => 'sparkles',
                            'number' => '10',
                            'title' => 'Embelezar Kosmetikinstitut',
                            'client' => 'Embelezar Kosmetikinstitut',
                            'detail_slug' => 'embelezar-kosmetikinstitut-nextjs-seo',
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function seedOldDinoDetail(Page $overview): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'parent_id' => $overview->id,
            'slug' => ['de' => self::SLUG, 'en' => self::SLUG],
            'title' => ['de' => 'Dino Hair — Friseursalon-Website mit SEO- & Google-Ads-Betreuung'],
            'is_active' => true,
            'sort_order' => 10,
            'content' => ['de' => ['tech_stack' => ['Google Ads · laufende SEA-Betreuung']]],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path(self::MIGRATION);
        $migration->up();
    }

    public function test_detail_title_drops_seo_and_ads(): void
    {
        $overview = $this->seedOverviewWithOldDino();
        $this->seedOldDinoDetail($overview);

        $this->runMigration();

        $title = Page::firstWhere('slug->de', self::SLUG)->getTranslation('title', 'de');
        $this->assertStringContainsString('WordPress-Website', $title);
        $this->assertStringNotContainsString('SEO', $title);
        $this->assertStringNotContainsString('Google-Ads', $title);
    }

    public function test_content_has_no_seo_or_ads_references(): void
    {
        $this->seedOverviewWithOldDino();

        $this->runMigration();

        $content = Page::firstWhere('slug->de', self::SLUG)->getTranslation('content', 'de');
        $flat = json_encode($content, JSON_UNESCAPED_UNICODE);

        $this->assertStringNotContainsString('Google Ads', $flat);
        $this->assertStringNotContainsString('Google-Ads', $flat);
        $this->assertStringNotContainsString('SEA', $flat);
        $this->assertStringNotContainsString('Suchmaschinenoptimierung', $flat);
        $this->assertStringNotContainsString('SEO', $flat);
    }

    public function test_features_keep_real_images(): void
    {
        $this->seedOverviewWithOldDino();

        $this->runMigration();

        $features = Page::firstWhere('slug->de', self::SLUG)->getTranslation('content', 'de')['features'];
        $this->assertCount(3, $features);
        foreach ($features as $feature) {
            $this->assertStringStartsWith('/images/references/dino-hair/', $feature['image']);
        }
    }

    public function test_overview_entry_updated_in_place(): void
    {
        $this->seedOverviewWithOldDino();

        $this->runMigration();

        $projects = Page::where('type', Page::TYPE_REFERENCES)->first()
            ->getTranslation('content', 'de')['projects'];

        $this->assertCount(3, $projects);
        $dino = $projects[1];
        $this->assertSame(self::SLUG, $dino['detail_slug']);
        $this->assertSame('09', $dino['number']); // position preserved
        $this->assertStringContainsString('WordPress-Website', $dino['title']);
        $this->assertStringNotContainsString('SEO', $dino['title']);
        $this->assertNotContains('SEO & Google Ads', $dino['categories']);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedOverviewWithOldDino();

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

    public function test_detail_page_renders_website_only(): void
    {
        $this->seedOverviewWithOldDino();

        $this->runMigration();

        $response = $this->get('/referenzen/'.self::SLUG);
        $response->assertStatus(200);

        $response->assertSee('WordPress-Website', false);
        $response->assertSee('Online-Terminbuchung', false);
        $response->assertSee('Sachsenhausen', false);
        $response->assertDontSee('Google-Ads-Betreuung', false);
    }
}
