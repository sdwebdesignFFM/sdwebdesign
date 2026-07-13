<?php

namespace Tests\Feature;

use Database\Seeders\PageSeeder;
use Database\Seeders\RedirectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PageSeeder::class);
        $this->seed(RedirectSeeder::class);
    }

    public function test_glossar_rest_endpoints_redirects_to_api_guide(): void
    {
        $response = $this->get('/glossar/wordpress-rest-endpoints');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber/api-first-architektur');
    }

    public function test_glossar_wordpress_transients_redirects_to_wordpress_guide(): void
    {
        $response = $this->get('/glossar/wordpress-transients');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber/wordpress-oder-individuell');
    }

    public function test_glossar_post_meta_redirects_to_wordpress_guide(): void
    {
        $response = $this->get('/glossar/post-meta');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber/wordpress-oder-individuell');
    }

    public function test_glossar_unmapped_slug_redirects_to_guides_overview(): void
    {
        $response = $this->get('/glossar/modal-window');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber');
    }

    public function test_glossar_unknown_slug_falls_back_to_guides_overview_via_wildcard(): void
    {
        $response = $this->get('/glossar/irgendein-unbekannter-begriff');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber');
    }

    public function test_glossar_index_redirects_to_guides_overview_via_wildcard(): void
    {
        $response = $this->get('/glossar');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber');
    }

    public function test_barrierefreies_webdesign_redirects_to_new_offer_page(): void
    {
        $response = $this->get('/loesungen/barrierefreies-webdesign');

        $response->assertStatus(301);
        $response->assertRedirect('/loesungen/websites/barrierefreies-webdesign');
    }

    public function test_homepage_footer_has_no_dead_solution_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('/loesungen/ios-app-entwicklung');
        $response->assertDontSee('/loesungen/prozessdigitalisierung');
    }

    public function test_homepage_footer_links_to_corrected_solution_slugs(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/loesungen/ios-apps');
        $response->assertSee('/loesungen/prozessautomatisierung');
    }
}
