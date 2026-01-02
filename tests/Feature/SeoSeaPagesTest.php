<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoSeaPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create SEO page
        Page::factory()->create([
            'slug' => ['de' => 'suchmaschinenoptimierung', 'en' => 'search-engine-optimization'],
            'title' => ['de' => 'Suchmaschinenoptimierung', 'en' => 'Search Engine Optimization'],
            'type' => Page::TYPE_SEO,
            'is_active' => true,
            'sort_order' => 5,
            'content' => [
                'de' => [
                    'hero' => [
                        'title' => 'Suchmaschinenoptimierung mit technischer Substanz',
                        'intro' => 'SEO ist kein Marketing-Trick.',
                        'icon' => 'magnifying-glass',
                    ],
                    'problem' => [
                        'title' => 'Warum SEO oft nicht funktioniert',
                        'items' => ['Technische Altlasten'],
                    ],
                    'when_useful' => [
                        'title' => 'Wann SEO sinnvoll ist',
                        'conditions' => ['Bedingung 1', 'Bedingung 2'],
                    ],
                    'cta' => [
                        'title' => 'SEO sinnvoll aufsetzen',
                        'button_text' => 'Projekt besprechen',
                    ],
                    'card' => [
                        'subtitle' => 'Nachhaltige Sichtbarkeit',
                        'description' => 'SEO als Teil eines funktionierenden Systems.',
                        'use_cases' => ['Technisches SEO'],
                        'character' => ['Fokus auf nachhaltige Ergebnisse'],
                    ],
                ],
            ],
        ]);

        // Create SEA page
        Page::factory()->create([
            'slug' => ['de' => 'suchmaschinenwerbung', 'en' => 'search-engine-advertising'],
            'title' => ['de' => 'Suchmaschinenwerbung', 'en' => 'Search Engine Advertising'],
            'type' => Page::TYPE_SEA,
            'is_active' => true,
            'sort_order' => 6,
            'content' => [
                'de' => [
                    'hero' => [
                        'title' => 'Suchmaschinenwerbung als steuerbarer Wachstumskanal',
                        'intro' => 'SEA als steuerbarer Wachstumskanal.',
                        'icon' => 'megaphone',
                    ],
                    'problem' => [
                        'title' => 'Warum SEA oft teurer wird als geplant',
                        'items' => ['Landingpages, die nicht passen'],
                    ],
                    'when_useful' => [
                        'title' => 'Wann SEA sinnvoll ist',
                        'conditions' => ['Bedingung 1', 'Bedingung 2'],
                    ],
                    'closing' => [
                        'title' => 'Kein Klicks kaufen, sondern Nachfrage steuern',
                        'text' => 'SEA ist kein Gluecksspiel.',
                    ],
                    'cta' => [
                        'title' => 'SEA in Ihre Strategie integrieren',
                        'button_text' => 'Projekt besprechen',
                    ],
                    'card' => [
                        'subtitle' => 'Gezielte Reichweite',
                        'description' => 'SEA als steuerbarer Wachstumskanal.',
                        'use_cases' => ['Kampagnen-Setup'],
                        'character' => ['Skalierbar & messbar'],
                    ],
                ],
            ],
        ]);

        // Create solutions overview page for solutions route
        Page::factory()->create([
            'slug' => ['de' => 'loesungen', 'en' => 'solutions'],
            'title' => ['de' => 'Lösungen', 'en' => 'Solutions'],
            'type' => Page::TYPE_SOLUTIONS,
            'is_active' => true,
            'content' => ['de' => ['hero' => ['title' => 'Unsere Lösungen']]],
        ]);

        // Create contact page
        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
    }

    public function test_seo_page_is_accessible(): void
    {
        $response = $this->get('/suchmaschinenoptimierung');

        $response->assertStatus(200);
        $response->assertSee('Suchmaschinenoptimierung mit technischer Substanz');
    }

    public function test_sea_page_is_accessible(): void
    {
        $response = $this->get('/suchmaschinenwerbung');

        $response->assertStatus(200);
        $response->assertSee('Suchmaschinenwerbung als steuerbarer Wachstumskanal');
    }

    public function test_old_seo_url_redirects(): void
    {
        $response = $this->get('/seo');
        $response->assertRedirect('/suchmaschinenoptimierung');
    }

    public function test_old_sea_url_redirects(): void
    {
        $response = $this->get('/sea');
        $response->assertRedirect('/suchmaschinenwerbung');
    }

    public function test_seo_page_shows_when_useful_section(): void
    {
        $response = $this->get('/suchmaschinenoptimierung');

        $response->assertStatus(200);
        $response->assertSee('Wann SEO sinnvoll ist');
    }

    public function test_sea_page_shows_closing_section(): void
    {
        $response = $this->get('/suchmaschinenwerbung');

        $response->assertStatus(200);
        $response->assertSee('Kein Klicks kaufen, sondern Nachfrage steuern');
    }

    public function test_seo_and_sea_appear_in_solutions_overview(): void
    {
        $response = $this->get('/loesungen');

        $response->assertStatus(200);
        $response->assertSee('Suchmaschinenoptimierung');
        $response->assertSee('Suchmaschinenwerbung');
    }

    public function test_seo_page_has_correct_url(): void
    {
        $seoPage = Page::where('type', Page::TYPE_SEO)->first();

        $this->assertEquals('/suchmaschinenoptimierung', $seoPage->getUrl());
    }

    public function test_sea_page_has_correct_url(): void
    {
        $seaPage = Page::where('type', Page::TYPE_SEA)->first();

        $this->assertEquals('/suchmaschinenwerbung', $seaPage->getUrl());
    }

    public function test_seo_page_shows_cta_section(): void
    {
        $response = $this->get('/suchmaschinenoptimierung');

        $response->assertStatus(200);
        $response->assertSee('SEO sinnvoll aufsetzen');
        $response->assertSee('Projekt besprechen');
    }

    public function test_sea_page_shows_cta_section(): void
    {
        $response = $this->get('/suchmaschinenwerbung');

        $response->assertStatus(200);
        $response->assertSee('SEA in Ihre Strategie integrieren');
        $response->assertSee('Projekt besprechen');
    }

    public function test_seo_page_appears_in_hub_pages_menu(): void
    {
        $hubPages = Page::getHubPagesForMenu();

        $this->assertTrue($hubPages->contains('type', Page::TYPE_SEO));
    }

    public function test_sea_page_appears_in_hub_pages_menu(): void
    {
        $hubPages = Page::getHubPagesForMenu();

        $this->assertTrue($hubPages->contains('type', Page::TYPE_SEA));
    }
}
