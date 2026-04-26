<?php

namespace Tests\Feature;

use App\Models\BlogArticle;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_valid_xml_and_served_with_correct_content_type(): void
    {
        $this->seedMinimalPages();

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'Sitemap output must be valid XML');
    }

    public function test_sitemap_contains_homepage_and_local_landing_page(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');
        $this->seedMinimalPages();

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('<loc>https://sdwebdesign.de/</loc>', false);
        $response->assertSee('<loc>https://sdwebdesign.de/in/frankfurt-am-main</loc>', false);
    }

    public function test_sitemap_contains_whitepaper_landing_page(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');
        $this->seedMinimalPages();

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee(
            '<loc>https://sdwebdesign.de/whitepaper/eigene-plattform-vs-standard-software</loc>',
            false
        );
    }

    public function test_sitemap_forces_https_on_production_hostname_even_when_app_url_is_http(): void
    {
        // Misconfigured production env: APP_URL still on http
        Config::set('app.url', 'http://sdwebdesign.de');
        $this->seedMinimalPages();

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringNotContainsString('http://sdwebdesign.de', $response->getContent());
        $response->assertSee('<loc>https://sdwebdesign.de/</loc>', false);
    }

    public function test_sitemap_contains_no_trailing_slashes_on_local_pages(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');
        $this->seedMinimalPages();

        $response = $this->get('/sitemap.xml');

        $this->assertStringNotContainsString('/in/frankfurt-am-main/</loc>', $response->getContent());
        $this->assertStringNotContainsString('/in/</loc>', $response->getContent());
    }

    public function test_sitemap_includes_published_blog_articles_but_not_drafts(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');
        $this->seedMinimalPages();

        BlogArticle::factory()->create([
            'slug' => ['de' => 'veroeffentlichter-artikel', 'en' => 'published-article'],
            'title' => ['de' => 'Veröffentlicht', 'en' => 'Published'],
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        BlogArticle::factory()->create([
            'slug' => ['de' => 'entwurfs-artikel', 'en' => 'draft-article'],
            'title' => ['de' => 'Entwurf', 'en' => 'Draft'],
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertSee('/ratgeber/veroeffentlichter-artikel', false);
        $this->assertStringNotContainsString('entwurfs-artikel', $response->getContent());
    }

    public function test_sitemap_excludes_inactive_pages(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');
        $this->seedMinimalPages();

        Page::factory()->create([
            'slug' => ['de' => 'hidden-city', 'en' => 'hidden-city'],
            'title' => ['de' => 'Versteckt', 'en' => 'Hidden'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => false,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/sitemap.xml');

        $this->assertStringNotContainsString('hidden-city', $response->getContent());
    }

    public function test_sitemap_emits_english_url_for_pages_with_en_version(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');

        Page::factory()->create([
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'type' => Page::TYPE_HOME,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'loesungen', 'en' => 'solutions'],
            'title' => ['de' => 'Lösungen', 'en' => 'Solutions'],
            'type' => Page::TYPE_SOLUTIONS,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertSee('<loc>https://sdwebdesign.de/loesungen</loc>', false);
        $response->assertSee('<loc>https://sdwebdesign.de/en/solutions</loc>', false);
    }

    public function test_sitemap_does_not_emit_english_url_for_local_pages(): void
    {
        Config::set('app.url', 'https://sdwebdesign.de');
        $this->seedMinimalPages();

        $response = $this->get('/sitemap.xml');

        $this->assertStringNotContainsString('/en/in/frankfurt-am-main', $response->getContent());
    }

    private function seedMinimalPages(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'type' => Page::TYPE_HOME,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'frankfurt-am-main', 'en' => 'frankfurt-am-main'],
            'title' => ['de' => 'Webagentur Frankfurt am Main', 'en' => 'Web Agency Frankfurt'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => ['de' => ['city' => 'Frankfurt am Main']],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Standorte', 'en' => 'Locations'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
    }
}
