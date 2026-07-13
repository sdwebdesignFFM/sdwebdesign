<?php

namespace Tests\Feature;

use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PageSeeder::class);
    }

    public function test_homepage_sends_referrer_and_permissions_policy_headers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_hsts_header_is_set_on_https_requests(): void
    {
        $response = $this->get('https://sdwebdesign.test/');

        $response->assertStatus(200);
        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    public function test_hsts_header_is_not_set_on_plain_http_requests(): void
    {
        $response = $this->get('http://sdwebdesign.test/');

        $response->assertStatus(200);
        $response->assertHeaderMissing('Strict-Transport-Security');
    }

    public function test_no_legacy_x_xss_protection_header_is_sent(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeaderMissing('X-XSS-Protection');
    }
}
