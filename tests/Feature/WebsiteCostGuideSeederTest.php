<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\WebsiteCostGuideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteCostGuideSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_cost_guide_page(): void
    {
        $this->seed(WebsiteCostGuideSeeder::class);

        $page = Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'website-erstellen-lassen-kosten')
            ->first();

        $this->assertNotNull($page);
        $this->assertTrue($page->is_active);
        $this->assertSame(3, $page->sort_order);
        $this->assertStringContainsString('Was kostet eine professionelle Website?', $page->getTranslation('title', 'de'));
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(WebsiteCostGuideSeeder::class);
        $this->seed(WebsiteCostGuideSeeder::class);

        $this->assertSame(1, Page::where('type', Page::TYPE_GUIDE)
            ->where('slug->de', 'website-erstellen-lassen-kosten')
            ->count());
    }

    public function test_guide_renders_with_price_table_and_internal_links(): void
    {
        $this->seed(WebsiteCostGuideSeeder::class);

        $response = $this->get('/ratgeber/website-erstellen-lassen-kosten');

        $response->assertStatus(200);
        $response->assertSee('Was kostet eine professionelle Website?');
        $response->assertSee('realistische Preisspannen 2026');
        $response->assertSee('1.500–5.000');
        $response->assertSee('/loesungen/websites/barrierefreies-webdesign', false);
        $response->assertSee('/betrieb-hosting-wartung', false);
        $response->assertSee('/loesungen/websites/starter-website', false);
        $response->assertSee('Kostenloses Erstgespräch anfragen');
    }

    public function test_guide_emits_blog_posting_schema_with_plain_headline(): void
    {
        $this->seed(WebsiteCostGuideSeeder::class);

        $html = $this->get('/ratgeber/website-erstellen-lassen-kosten')->getContent();
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        $blogPosting = null;
        foreach ($matches[1] as $jsonText) {
            $data = json_decode(trim($jsonText), true);
            if (isset($data['@type']) && $data['@type'] === 'BlogPosting') {
                $blogPosting = $data;
                break;
            }
        }

        $this->assertNotNull($blogPosting, 'Cost guide must emit a BlogPosting schema block');
        $this->assertSame('Was kostet eine professionelle Website? Realistische Preise 2026', $blogPosting['headline']);
        $this->assertSame('Person', $blogPosting['author']['@type']);
    }

    public function test_guide_links_related_solution_pages_when_they_exist(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'type' => Page::TYPE_SOLUTION_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
        $hub = Page::where('slug->de', 'websites')->first();
        Page::factory()->create([
            'slug' => ['de' => 'starter-website', 'en' => 'starter-website'],
            'title' => ['de' => 'Starter-Website', 'en' => 'Starter Website'],
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'is_active' => true,
            'content' => ['de' => ['hero' => ['tagline' => 'Professioneller Webauftritt']]],
        ]);

        $this->seed(WebsiteCostGuideSeeder::class);

        $response = $this->get('/ratgeber/website-erstellen-lassen-kosten');

        $response->assertStatus(200);
        $response->assertSee('Starter-Website');
    }
}
