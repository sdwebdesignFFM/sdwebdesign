<?php

namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifies the 2026_04_24_151246_seed_legacy_url_redirects migration seeded
 * the expected rows. RefreshDatabase runs all migrations, so these rows must
 * exist in the test DB after setup — a plain assertion is enough.
 */
class LegacyRedirectsMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, array{string, string}>
     */
    public static function legacyUrls(): array
    {
        return [
            ['/services/wordpress-webdesign-frankfurt', '/in/frankfurt-am-main'],
            ['/services/webdesign-frankfurt', '/in/frankfurt-am-main'],
            ['/lp/webdesign-agentur-frankfurt', '/in/frankfurt-am-main'],
            ['/services/professionelles-webdesign-fuer-unternehmen', '/loesungen/websites'],
            ['/google-adwords-optimieren', '/suchmaschinenwerbung'],
        ];
    }

    /**
     * @dataProvider legacyUrls
     */
    public function test_legacy_url_has_a_301_redirect_to_expected_target(string $from, string $to): void
    {
        $redirect = Redirect::where('from_url', $from)->first();

        $this->assertNotNull($redirect, "No redirect seeded for {$from}");
        $this->assertSame($to, $redirect->to_url);
        $this->assertSame(301, $redirect->status_code);
        $this->assertTrue($redirect->is_active);
    }

    public function test_hitting_legacy_url_gets_301_redirect(): void
    {
        $response = $this->get('/services/wordpress-webdesign-frankfurt');

        $response->assertStatus(301);
        $response->assertRedirect('/in/frankfurt-am-main');
    }
}
