<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class HomepageSchemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('app.url', 'https://sdwebdesign.de');

        Page::factory()->create([
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'type' => Page::TYPE_HOME,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        Setting::firstOrCreate(['id' => 1], [
            'company_name' => 'sdWebdesign',
            'email' => 'info@sdwebdesign.de',
            'mobile' => '+4915253822114',
            'street' => 'Hannah-Arendt-Str. 29',
            'postal_code' => '60438',
            'city' => 'Frankfurt am Main',
            'country' => 'Deutschland',
            'linkedin_url' => 'https://www.linkedin.com/company/sdwebdesign',
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function extractSchemaBlock(string $html, string $type): ?array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
        foreach ($matches[1] as $jsonText) {
            $data = json_decode(trim($jsonText), true);
            if (isset($data['@type']) && $data['@type'] === $type) {
                return $data;
            }
        }

        return null;
    }

    public function test_homepage_emits_organization_schema_with_canonical_id(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $org = $this->extractSchemaBlock($response->getContent(), 'Organization');

        $this->assertNotNull($org, 'Homepage must emit an Organization schema block');
        $this->assertSame('https://sdwebdesign.de/#organization', $org['@id']);
        $this->assertSame('sdWebdesign', $org['name']);
        $this->assertSame('https://sdwebdesign.de', $org['url']);
        $this->assertSame('Hannah-Arendt-Str. 29', $org['address']['streetAddress']);
        $this->assertSame('60438', $org['address']['postalCode']);
        $this->assertSame('Frankfurt am Main', $org['address']['addressLocality']);
        $this->assertSame('DE', $org['address']['addressCountry']);
        $this->assertContains('https://www.linkedin.com/company/sdwebdesign', $org['sameAs']);
    }

    public function test_homepage_emits_website_schema_with_searchaction(): void
    {
        $response = $this->get('/');

        $website = $this->extractSchemaBlock($response->getContent(), 'WebSite');

        $this->assertNotNull($website, 'Homepage must emit a WebSite schema block');
        $this->assertSame('https://sdwebdesign.de/#website', $website['@id']);
        $this->assertSame('https://sdwebdesign.de/#organization', $website['publisher']['@id']);
        $this->assertSame('SearchAction', $website['potentialAction']['@type']);
        $this->assertStringContainsString('{search_term_string}', $website['potentialAction']['target']['urlTemplate']);
    }

    public function test_homepage_uses_https_even_when_app_url_is_http(): void
    {
        Config::set('app.url', 'http://sdwebdesign.de');

        $response = $this->get('/');

        $org = $this->extractSchemaBlock($response->getContent(), 'Organization');

        $this->assertNotNull($org);
        $this->assertStringStartsWith('https://', $org['@id']);
        $this->assertStringStartsWith('https://', $org['url']);
    }

    public function test_kontakt_page_emits_organization_schema(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/kontakt');

        $response->assertStatus(200);

        $org = $this->extractSchemaBlock($response->getContent(), 'Organization');

        $this->assertNotNull($org, 'Contact page must emit an Organization schema block');
        $this->assertSame('https://sdwebdesign.de/#organization', $org['@id']);
    }

    public function test_kontakt_page_displays_full_street_address(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $response = $this->get('/kontakt');

        $response->assertStatus(200);
        $response->assertSee('Hannah-Arendt-Str. 29');
        $response->assertSee('60438 Frankfurt am Main');
    }

    public function test_local_page_uses_correct_riedberg_geo_coordinates(): void
    {
        Page::factory()->create([
            'slug' => ['de' => 'frankfurt-am-main', 'en' => 'frankfurt-am-main'],
            'title' => ['de' => 'Webagentur Frankfurt am Main', 'en' => 'Web Agency Frankfurt'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => ['de' => ['city' => 'Frankfurt am Main']],
        ]);

        $response = $this->get('/in/frankfurt-am-main');

        $ls = $this->extractSchemaBlock($response->getContent(), 'ProfessionalService');

        $this->assertNotNull($ls);
        // Hannah-Arendt-Str. 29 is in Kalbach-Riedberg, not the city centre.
        // The previous values (50.1109, 8.6821) pointed at the Frankfurt core
        // which doesn't match the GBP pin Google would expect.
        $this->assertEqualsWithDelta(50.1843, $ls['geo']['latitude'], 0.001);
        $this->assertEqualsWithDelta(8.6587, $ls['geo']['longitude'], 0.001);
    }
}
