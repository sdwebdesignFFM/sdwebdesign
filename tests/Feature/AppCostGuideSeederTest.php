<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\AppCostGuideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppCostGuideSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_app_cost_guide(): void
    {
        $this->seed(AppCostGuideSeeder::class);

        $page = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'app-entwicklung-kosten')
            ->first();

        $this->assertNotNull($page);
        $this->assertTrue($page->is_active);
        $this->assertSame(4, $page->sort_order);
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(AppCostGuideSeeder::class);
        $this->seed(AppCostGuideSeeder::class);

        $this->assertSame(1, Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'app-entwicklung-kosten')
            ->count());
    }

    public function test_guide_renders_with_cost_table_and_pwa_links(): void
    {
        $this->seed(AppCostGuideSeeder::class);

        $response = $this->get('/ratgeber/app-entwicklung-kosten');

        $response->assertStatus(200);
        $response->assertSee('Was kostet eine App?');
        $response->assertSee('Progressive Web App');
        $response->assertSee('/loesungen/mobile-anwendungen/pwa', false);
        $response->assertSee('/betrieb-hosting-wartung', false);
        $response->assertSee('/ratgeber/app-oder-pwa', false);
        $response->assertSee('Kostenloses Erstgespräch anfragen');
    }

    public function test_guide_emits_blog_posting_schema(): void
    {
        $this->seed(AppCostGuideSeeder::class);

        $html = $this->get('/ratgeber/app-entwicklung-kosten')->getContent();
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        $blogPosting = null;
        foreach ($matches[1] as $jsonText) {
            $data = json_decode(trim($jsonText), true);
            if (isset($data['@type']) && $data['@type'] === 'BlogPosting') {
                $blogPosting = $data;
                break;
            }
        }

        $this->assertNotNull($blogPosting, 'App cost guide must emit a BlogPosting schema block');
        $this->assertSame('Was kostet eine App? Entwicklungskosten realistisch kalkuliert', $blogPosting['headline']);
    }
}
