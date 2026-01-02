<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create local landing page
        Page::factory()->create([
            'slug' => ['de' => 'bad-homburg', 'en' => 'bad-homburg'],
            'title' => ['de' => 'Webagentur Bad Homburg', 'en' => 'Web Agency Bad Homburg'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => [
                'de' => [
                    'city' => 'Bad Homburg',
                    'region' => 'Rhein-Main-Gebiet',
                    'local_context' => [
                        'text' => 'In Bad Homburg arbeiten wir häufig mit mittelständischen Unternehmen, Dienstleistern und wachsenden Betrieben, die eine stabile digitale Basis aufbauen möchten.',
                    ],
                ],
            ],
            'meta_title' => ['de' => 'Webagentur Bad Homburg: Websites, Shops & Systeme | sdWebdesign'],
            'meta_description' => ['de' => 'Webagentur für Bad Homburg: Unternehmenswebsites, Online-Shops, Plattformen und mobile Lösungen.'],
        ]);

        // Create another local page
        Page::factory()->create([
            'slug' => ['de' => 'frankfurt-am-main', 'en' => 'frankfurt-am-main'],
            'title' => ['de' => 'Webagentur Frankfurt am Main', 'en' => 'Web Agency Frankfurt am Main'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => [
                'de' => [
                    'city' => 'Frankfurt am Main',
                    'region' => 'Rhein-Main-Gebiet',
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

        // Create solution hub pages for linking
        Page::factory()->create([
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'type' => Page::TYPE_SOLUTION_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
    }

    public function test_local_page_is_accessible(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Bad Homburg');
    }

    public function test_local_page_shows_city_in_headline(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Webagentur Bad Homburg');
    }

    public function test_local_page_shows_region(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Rhein-Main-Gebiet');
    }

    public function test_local_page_shows_local_context(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('In Bad Homburg arbeiten wir häufig mit mittelständischen Unternehmen');
    }

    public function test_local_page_shows_solution_links(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Unternehmenswebsites');
        $response->assertSee('Digitale Plattformen');
        $response->assertSee('E-Commerce');
        $response->assertSee('Mobile Anwendungen');
        $response->assertSee('SEO');
        $response->assertSee('SEA');
    }

    public function test_local_page_shows_why_section(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('So arbeiten wir');
        $response->assertSee('Saubere Technik');
    }

    public function test_local_page_shows_local_signal(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Regional verankert');
    }

    public function test_local_page_shows_cta(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Projekt besprechen');
    }

    public function test_local_page_has_correct_url(): void
    {
        $page = Page::where('type', Page::TYPE_LOCAL)->first();

        $this->assertEquals('/in/bad-homburg/', $page->getUrl());
    }

    public function test_nonexistent_local_page_returns_404(): void
    {
        $response = $this->get('/in/nonexistent-city');

        $response->assertStatus(404);
    }

    public function test_inactive_local_page_returns_404(): void
    {
        $page = Page::where('slug->de', 'bad-homburg')->first();
        $page->update(['is_active' => false]);

        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(404);
    }

    public function test_multiple_local_pages_work(): void
    {
        $response = $this->get('/in/frankfurt-am-main');

        $response->assertStatus(200);
        $response->assertSee('Frankfurt am Main');
    }

    public function test_local_page_has_correct_meta_title(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('Webagentur Bad Homburg: Websites, Shops &amp; Systeme', false);
    }

    public function test_local_page_links_work(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('/loesungen/websites/');
        $response->assertSee('/loesungen/plattformen/');
        $response->assertSee('/loesungen/e-commerce/');
        $response->assertSee('/loesungen/mobile-anwendungen/');
        $response->assertSee('/seo/');
        $response->assertSee('/sea/');
    }

    public function test_local_page_links_back_to_hub(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('/in/');
        $response->assertSee('Weitere Standorte im Rhein-Main-Gebiet');
    }

    public function test_local_hub_is_accessible(): void
    {
        // Create hub page
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
    }

    public function test_local_hub_shows_headline(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => [
                        'title' => 'Webagentur im Rhein-Main-Gebiet',
                    ],
                ],
            ],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
        $response->assertSee('Webagentur im Rhein-Main-Gebiet');
    }

    public function test_local_hub_shows_regions(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        // Create a city in Südhessen region
        Page::factory()->create([
            'slug' => ['de' => 'darmstadt', 'en' => 'darmstadt'],
            'title' => ['de' => 'Webagentur Darmstadt', 'en' => 'Web Agency Darmstadt'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => ['de' => ['city' => 'Darmstadt', 'region' => 'Südhessen']],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
        $response->assertSee('Frankfurt &amp; Umgebung', false);
        $response->assertSee('Südhessen');
    }

    public function test_local_hub_shows_city_links(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
        // Bad Homburg and Frankfurt are created in setUp
        $response->assertSee('Bad Homburg');
        $response->assertSee('Frankfurt am Main');
        $response->assertSee('/in/bad-homburg');
        $response->assertSee('/in/frankfurt-am-main');
    }

    public function test_local_hub_shows_why_local_section(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
        $response->assertSee('Warum lokale Nähe');
        $response->assertSee('Persönliche Abstimmungen');
    }

    public function test_local_hub_shows_cta(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
        $response->assertSee('Projekt besprechen');
    }

    public function test_local_hub_has_correct_url(): void
    {
        $page = Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $this->assertEquals('/in/', $page->getUrl());
    }

    public function test_local_hub_without_page_returns_404(): void
    {
        // No hub page created
        $response = $this->get('/in');

        $response->assertStatus(404);
    }
}
