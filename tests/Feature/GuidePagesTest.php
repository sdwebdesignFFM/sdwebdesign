<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuidePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create guide overview page
        Page::factory()->create([
            'slug' => ['de' => 'ratgeber', 'en' => 'guides'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Wissen',
                        'subtitle' => 'Entscheidungshilfen fuer Websites und Shops',
                    ],
                    'intro' => [
                        'text' => 'Unsere Ratgeber helfen Ihnen.',
                    ],
                    'cta' => [
                        'title' => 'Unsicher?',
                        'subtitle' => 'Wir helfen.',
                        'button_text' => 'Projekt besprechen',
                    ],
                ],
            ],
        ]);

        // Create a guide page
        Page::factory()->create([
            'slug' => ['de' => 'website-vs-webanwendung', 'en' => 'website-vs-web-application'],
            'title' => ['de' => 'Website oder Webanwendung?', 'en' => 'Website or Web Application?'],
            'type' => Page::TYPE_GUIDE,
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Websites',
                        'subtitle' => 'Wann reicht eine Website?',
                    ],
                    'intro' => [
                        'text' => 'Die Grenze ist fliessend.',
                    ],
                    'sections' => [
                        [
                            'title' => 'Die Ausgangsfrage',
                            'content' => '<p>Eine Website informiert.</p>',
                        ],
                    ],
                    'comparison' => [
                        'title' => 'Vergleich',
                        'items' => [
                            [
                                'name' => 'Website',
                                'pros' => "Schnell\nGuenstig",
                                'cons' => 'Begrenzt',
                            ],
                        ],
                    ],
                    'related_solutions' => ['websites'],
                    'cta' => [
                        'subtitle' => 'Wir klaeren.',
                        'button_text' => 'Projekt besprechen',
                    ],
                ],
            ],
        ]);

        // Create a second guide
        Page::factory()->create([
            'slug' => ['de' => 'app-oder-pwa', 'en' => 'app-or-pwa'],
            'title' => ['de' => 'App oder PWA?', 'en' => 'App or PWA?'],
            'type' => Page::TYPE_GUIDE,
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => [
                        'badge' => 'Mobile',
                        'subtitle' => 'Die richtige mobile Strategie',
                    ],
                    'intro' => [
                        'text' => 'Nicht jedes Projekt braucht eine native App.',
                    ],
                ],
            ],
        ]);

        // Create contact page for routes
        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
    }

    public function test_guide_overview_page_is_accessible(): void
    {
        $response = $this->get('/ratgeber');

        $response->assertStatus(200);
        $response->assertSee('Ratgeber');
    }

    public function test_guide_overview_shows_all_guides(): void
    {
        $response = $this->get('/ratgeber');

        $response->assertStatus(200);
        $response->assertSee('Website oder Webanwendung?');
        $response->assertSee('App oder PWA?');
    }

    public function test_guide_detail_page_is_accessible(): void
    {
        $response = $this->get('/ratgeber/website-vs-webanwendung');

        $response->assertStatus(200);
        $response->assertSee('Website oder Webanwendung?');
    }

    public function test_guide_detail_shows_content_sections(): void
    {
        $response = $this->get('/ratgeber/website-vs-webanwendung');

        $response->assertStatus(200);
        $response->assertSee('Die Ausgangsfrage');
        $response->assertSee('Eine Website informiert.');
    }

    public function test_guide_detail_shows_comparison(): void
    {
        $response = $this->get('/ratgeber/website-vs-webanwendung');

        $response->assertStatus(200);
        $response->assertSee('Vergleich');
        $response->assertSee('Website');
    }

    public function test_guide_overview_shows_cta(): void
    {
        $response = $this->get('/ratgeber');

        $response->assertStatus(200);
        $response->assertSee('Unsicher?');
        $response->assertSee('Projekt besprechen');
    }

    public function test_guide_has_correct_url(): void
    {
        $guide = Page::where('type', Page::TYPE_GUIDE)->first();

        $this->assertEquals('/ratgeber/website-vs-webanwendung', $guide->getUrl());
    }

    public function test_guide_overview_has_correct_url(): void
    {
        $overview = Page::where('type', Page::TYPE_GUIDE_OVERVIEW)->first();

        $this->assertEquals('/ratgeber', $overview->getUrl());
    }

    public function test_nonexistent_guide_returns_404(): void
    {
        $response = $this->get('/ratgeber/nonexistent-guide');

        $response->assertStatus(404);
    }

    public function test_inactive_guide_returns_404(): void
    {
        $guide = Page::where('slug->de', 'website-vs-webanwendung')->first();
        $guide->update(['is_active' => false]);

        $response = $this->get('/ratgeber/website-vs-webanwendung');

        $response->assertStatus(404);
    }

    public function test_english_guide_routes_work(): void
    {
        $response = $this->get('/en/guides');
        $response->assertStatus(200);

        $response = $this->get('/en/guides/website-vs-web-application');
        $response->assertStatus(200);
    }

    public function test_guide_overview_paginates_guides(): void
    {
        // Create 15 more guides (total: 17 with 2 from setUp)
        for ($i = 1; $i <= 15; $i++) {
            Page::factory()->create([
                'slug' => ['de' => "test-guide-{$i}", 'en' => "test-guide-{$i}"],
                'title' => ['de' => "Test Ratgeber {$i}", 'en' => "Test Guide {$i}"],
                'type' => Page::TYPE_GUIDE,
                'is_active' => true,
                'sort_order' => $i + 10,
                'content' => ['de' => ['intro' => ['text' => "Test content {$i}"]]],
            ]);
        }

        // First page should show 12 guides
        $response = $this->get('/ratgeber');
        $response->assertStatus(200);

        // Should have pagination links since 17 > 12
        $response->assertSee('page=2');

        // Second page should work
        $response = $this->get('/ratgeber?page=2');
        $response->assertStatus(200);
    }

    public function test_guide_overview_shows_no_pagination_with_few_guides(): void
    {
        // With only 2 guides from setUp, no pagination should appear
        $response = $this->get('/ratgeber');
        $response->assertStatus(200);
        $response->assertDontSee('page=2');
    }

    public function test_guide_page_emits_blog_posting_schema(): void
    {
        $response = $this->get('/ratgeber/website-vs-webanwendung');

        $response->assertStatus(200);

        $html = $response->getContent();
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        $blogPosting = null;
        foreach ($matches[1] as $jsonText) {
            $data = json_decode(trim($jsonText), true);
            if (isset($data['@type']) && $data['@type'] === 'BlogPosting') {
                $blogPosting = $data;
                break;
            }
        }

        $this->assertNotNull($blogPosting, 'Guide page must emit a BlogPosting schema block');
        $this->assertArrayHasKey('headline', $blogPosting);
        $this->assertArrayHasKey('datePublished', $blogPosting);
        $this->assertArrayHasKey('dateModified', $blogPosting);
        $this->assertArrayHasKey('author', $blogPosting);
        $this->assertArrayHasKey('publisher', $blogPosting);
        $this->assertSame('Organization', $blogPosting['publisher']['@type']);
        $this->assertArrayHasKey('logo', $blogPosting['publisher']);
    }
}
