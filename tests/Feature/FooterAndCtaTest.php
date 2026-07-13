<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterAndCtaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'company_name' => 'sdWebdesign',
            'email' => 'info@sdwebdesign.de',
            'phone' => '+49 152 538 2211 4',
            'street' => 'Hannah-Arendt-Str. 29',
            'postal_code' => '60438',
            'city' => 'Frankfurt am Main',
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'frankfurt-am-main', 'en' => 'frankfurt-am-main'],
            'title' => ['de' => 'Webagentur Frankfurt am Main', 'en' => 'Web Agency Frankfurt am Main'],
            'type' => Page::TYPE_LOCAL,
            'is_active' => true,
            'content' => ['de' => ['city' => 'Frankfurt am Main', 'region' => 'Rhein-Main-Gebiet']],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => [], 'en' => []],
        ]);

        Page::factory()->create([
            'slug' => ['de' => 'loesungen', 'en' => 'solutions'],
            'title' => ['de' => 'Unsere Lösungen', 'en' => 'Our Solutions'],
            'type' => Page::TYPE_SOLUTIONS,
            'is_active' => true,
            'content' => ['de' => ['hero' => ['title' => 'Unsere Lösungen']], 'en' => ['hero' => ['title' => 'Our Solutions']]],
        ]);
    }

    public function test_de_footer_shows_plaintext_nap_with_tel_and_mailto_links(): void
    {
        $response = $this->get('/in/frankfurt-am-main');

        $response->assertStatus(200);
        $response->assertSee('Hannah-Arendt-Str. 29');
        $response->assertSee('60438 Frankfurt am Main');
        $response->assertSee('tel:+4915253822114', false);
        $response->assertSee('+49 152 53822114');
        $response->assertSee('mailto:info@sdwebdesign.de', false);
        // Obfuscation attributes must be gone from the footer contact.
        $response->assertDontSee('data-v=', false);
    }

    public function test_en_footer_also_shows_plaintext_nap(): void
    {
        $response = $this->get('/en/contact');

        $response->assertStatus(200);
        $response->assertSee('60438 Frankfurt am Main');
        $response->assertSee('tel:+4915253822114', false);
        $response->assertSee('mailto:info@sdwebdesign.de', false);
    }

    public function test_de_footer_shows_region_links(): void
    {
        $response = $this->get('/in/frankfurt-am-main');

        $response->assertStatus(200);
        $response->assertSee('Webdesign in Ihrer Region');
        $response->assertSee('Webdesign Frankfurt');
        $response->assertSee('/in/frankfurt-am-main', false);
        $response->assertSee('/in/offenbach', false);
        $response->assertSee('/in/bad-homburg', false);
        $response->assertSee('/in/darmstadt', false);
        $response->assertSee('/in/mainz', false);
        $response->assertSee('/in/hanau', false);
        $response->assertSee('/in/bensheim', false);
        $response->assertSee('Alle Standorte');
    }

    public function test_en_footer_hides_region_links(): void
    {
        $response = $this->get('/en/contact');

        $response->assertStatus(200);
        $response->assertDontSee('Webdesign in Ihrer Region');
        $response->assertDontSee('/in/frankfurt-am-main', false);
        $response->assertDontSee('/in/offenbach', false);
    }

    public function test_city_page_more_locations_link_points_to_hub_not_dead_anchor(): void
    {
        $response = $this->get('/in/frankfurt-am-main');

        $response->assertStatus(200);
        $response->assertSee('Weitere Standorte im Rhein-Main-Gebiet');
        $response->assertSee('href="/in"', false);
        // The footer contact must no longer rely on the JS-obfuscated (href="#") anchor.
        $response->assertDontSee('data-t="phone"', false);
        $response->assertDontSee('data-t="email"', false);
    }

    public function test_solutions_hero_has_contact_cta(): void
    {
        $response = $this->get('/loesungen');

        $response->assertStatus(200);
        $response->assertSee('Kostenloses Erstgespräch');
        $response->assertSee('openContactModal', false);
    }

    public function test_solutions_hero_has_contact_cta_in_english(): void
    {
        $response = $this->get('/en/solutions');

        $response->assertStatus(200);
        $response->assertSee('Free consultation');
    }

    public function test_mobile_header_shows_compact_cta_de(): void
    {
        $response = $this->get('/in/frankfurt-am-main');

        $response->assertStatus(200);
        $response->assertSee('Anfrage');
        $response->assertSee('class="btn-primary text-sm py-2 px-4"', false);
    }

    public function test_mobile_header_shows_compact_cta_en(): void
    {
        $response = $this->get('/en/contact');

        $response->assertStatus(200);
        $response->assertSee('Inquiry');
    }
}
