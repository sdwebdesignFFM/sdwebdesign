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

        $this->assertEquals('/in/bad-homburg', $page->getUrl());
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

    public function test_meta_title_does_not_double_brand_suffix(): void
    {
        // Editors historically typed "| sdWebdesign" into meta_title; the package
        // appends it too. The controller must strip the manual suffix so the final
        // <title> doesn't end with "| sdWebdesign | sdWebdesign".
        $page = Page::where('slug->de', 'bad-homburg')->first();
        $page->update([
            'meta_title' => ['de' => 'Webagentur Bad Homburg | sdWebdesign'],
        ]);

        $response = $this->get('/in/bad-homburg');

        $html = $response->getContent();
        preg_match('#<title>(.*?)</title>#', $html, $m);
        $title = $m[1] ?? '';

        $this->assertStringNotContainsString('sdWebdesign | sdWebdesign', $title);
        $this->assertStringContainsString('Webagentur Bad Homburg', $title);
    }

    public function test_local_page_does_not_emit_english_hreflang(): void
    {
        // /in/* pages have no English equivalent — hreflang tags pointing at
        // /en would be an unconfirmed pair in GSC, so they must be absent.
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $this->assertStringNotContainsString('hreflang="en"', $response->getContent());
    }

    /**
     * Merge extra content keys into the Bad Homburg page's DE content,
     * preserving the translatable wrapper. Direct `content` assignment
     * bypasses the HasTranslations trait's locale handling.
     */
    private function mergeBadHomburgContent(array $extras): void
    {
        $page = Page::where('slug->de', 'bad-homburg')->first();
        $existing = $page->getTranslation('content', 'de') ?? [];
        $page->setTranslation('content', 'de', array_merge($existing, $extras));
        $page->save();
    }

    public function test_noindex_flag_emits_robots_noindex_meta(): void
    {
        $this->mergeBadHomburgContent(['meta' => ['noindex' => true]]);

        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $this->assertMatchesRegularExpression(
            '#<meta[^>]*name="robots"[^>]*content="[^"]*noindex[^"]*"#i',
            $response->getContent()
        );
    }

    public function test_page_without_noindex_flag_is_indexable(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $this->assertDoesNotMatchRegularExpression(
            '#<meta[^>]*name="robots"[^>]*content="[^"]*noindex[^"]*"#i',
            $response->getContent()
        );
    }

    public function test_trust_bar_renders_when_values_are_set(): void
    {
        $this->mergeBadHomburgContent([
            'trust' => [
                'project_count' => '50+',
                'years_in_business' => 'seit 2015',
                'rating_label' => '4,9/5 auf Google',
            ],
        ]);

        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('50+');
        $response->assertSee('seit 2015');
        $response->assertSee('4,9/5 auf Google');
    }

    public function test_trust_bar_is_hidden_when_values_are_empty(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertDontSee('uppercase tracking-wider">Projekte<', false);
    }

    public function test_price_anchor_renders_only_when_set(): void
    {
        $this->mergeBadHomburgContent([
            'trust' => [
                'price_anchor_label' => 'Websites ab 3.000 €',
                'price_anchor_note' => 'Transparent kalkuliert',
            ],
        ]);

        $response = $this->get('/in/bad-homburg');

        $response->assertSee('Websites ab 3.000 €');
        $response->assertSee('Transparent kalkuliert');
    }

    public function test_cases_block_renders_named_references(): void
    {
        $this->mergeBadHomburgContent([
            'cases' => [
                'items' => [
                    ['name' => 'Acme Finanz GmbH', 'industry' => 'Finanzberatung', 'description' => 'Unternehmenswebsite'],
                    ['name' => 'Beta Consulting', 'industry' => 'Beratung', 'description' => 'Portal mit Kundenbereich'],
                ],
            ],
        ]);

        $response = $this->get('/in/bad-homburg');

        $response->assertSee('Acme Finanz GmbH');
        $response->assertSee('Finanzberatung');
        $response->assertSee('Beta Consulting');
        $response->assertSee('Ausgewählte Projekte aus Bad Homburg');
    }

    public function test_city_usp_block_renders_when_set(): void
    {
        $this->mergeBadHomburgContent([
            'city_usp' => [
                'headline' => 'Erfahrung im Hochtaunus-Mittelstand',
                'text' => 'Viele unserer Bad Homburg-Kunden kommen aus Finanz- und Beratungsumfeld.',
            ],
        ]);

        $response = $this->get('/in/bad-homburg');

        $response->assertSee('Erfahrung im Hochtaunus-Mittelstand');
        $response->assertSee('Viele unserer Bad Homburg-Kunden');
    }

    public function test_local_page_emits_complete_local_business_schema(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);

        $html = $response->getContent();
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        // Find the ProfessionalService block (not FAQPage, not BreadcrumbList)
        $localBusiness = null;
        foreach ($matches[1] as $jsonText) {
            $data = json_decode(trim($jsonText), true);
            if (isset($data['@type']) && $data['@type'] === 'ProfessionalService') {
                $localBusiness = $data;
                break;
            }
        }

        $this->assertNotNull($localBusiness, 'Local page must emit a ProfessionalService schema block');

        // Verify all the previously-lost fields are now present.
        $this->assertArrayHasKey('@id', $localBusiness);
        $this->assertStringEndsWith('#organization', $localBusiness['@id']);
        $this->assertArrayHasKey('geo', $localBusiness);
        $this->assertArrayHasKey('latitude', $localBusiness['geo']);
        $this->assertArrayHasKey('longitude', $localBusiness['geo']);
        $this->assertArrayHasKey('openingHoursSpecification', $localBusiness);
        $this->assertArrayHasKey('hasOfferCatalog', $localBusiness);
        $this->assertArrayHasKey('areaServed', $localBusiness);
        $this->assertSame('Bad Homburg', $localBusiness['areaServed']['name']);
        $this->assertArrayHasKey('logo', $localBusiness);
    }

    public function test_local_page_links_work(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('/loesungen/websites');
        $response->assertSee('/loesungen/plattformen');
        $response->assertSee('/loesungen/e-commerce');
        $response->assertSee('/loesungen/mobile-anwendungen');
        $response->assertSee('/suchmaschinenoptimierung');
        $response->assertSee('/suchmaschinenwerbung');
    }

    public function test_local_page_links_back_to_hub(): void
    {
        $response = $this->get('/in/bad-homburg');

        $response->assertStatus(200);
        $response->assertSee('href="/in"', false);
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

    public function test_local_hub_groups_wiesbaden_and_kassel(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'in', 'en' => 'in'],
            'title' => ['de' => 'Lokale Expertise', 'en' => 'Local Expertise'],
            'type' => Page::TYPE_LOCAL_HUB,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'wiesbaden', 'en' => 'wiesbaden'],
            'title' => ['de' => 'Webagentur Wiesbaden', 'en' => 'Web Agency Wiesbaden'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => ['de' => ['city' => 'Wiesbaden', 'region' => 'Rhein-Main-Gebiet']],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'kassel', 'en' => 'kassel'],
            'title' => ['de' => 'Webagentur Kassel', 'en' => 'Web Agency Kassel'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => ['de' => ['city' => 'Kassel', 'region' => 'Nordhessen']],
        ]);

        $response = $this->get('/in');

        $response->assertStatus(200);
        $response->assertSee('Mainz &amp; Wiesbaden', false);
        $response->assertSee('Nordhessen');
        $response->assertSee('/in/wiesbaden');
        $response->assertSee('/in/kassel');
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

        $this->assertEquals('/in', $page->getUrl());
    }

    public function test_local_hub_without_page_returns_404(): void
    {
        // No hub page created
        $response = $this->get('/in');

        $response->assertStatus(404);
    }
}
