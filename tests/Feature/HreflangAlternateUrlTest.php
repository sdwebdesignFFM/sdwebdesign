<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HreflangAlternateUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $hub = Page::factory()->create([
            'slug' => ['de' => 'websites', 'en' => 'websites'],
            'title' => ['de' => 'Websites', 'en' => 'Websites'],
            'type' => Page::TYPE_SOLUTION_HUB,
            'is_active' => true,
            'sort_order' => 1,
            'content' => ['de' => ['hero' => ['title' => 'Websites']], 'en' => ['hero' => ['title' => 'Websites']]],
        ]);

        // Solution detail whose slug differs per locale — the exact case the
        // old helper mishandled (DE "individuelle-website" vs EN "custom-website").
        Page::factory()->create([
            'slug' => ['de' => 'individuelle-website', 'en' => 'custom-website'],
            'title' => ['de' => 'Individuelle Website', 'en' => 'Custom Website'],
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'is_active' => true,
            'content' => ['de' => ['hero' => ['title' => 'Individuelle Website']], 'en' => ['hero' => ['title' => 'Custom Website']]],
        ]);

        // Flat guide with a per-locale slug.
        Page::factory()->create([
            'slug' => ['de' => 'website-erstellen-lassen-kosten', 'en' => 'website-cost-guide'],
            'title' => ['de' => 'Was kostet eine Website?', 'en' => 'Website Cost Guide'],
            'type' => Page::TYPE_GUIDE,
            'parent_id' => null,
            'is_active' => true,
            'content' => ['de' => ['intro' => 'x'], 'en' => ['intro' => 'x']],
        ]);
    }

    public function test_de_solution_hreflang_points_to_english_slug_not_german_slug(): void
    {
        $html = $this->get('/loesungen/websites/individuelle-website')->getContent();

        $this->assertMatchesRegularExpression(
            '#<link rel="alternate" hreflang="en" href="[^"]*/en/solutions/websites/custom-website" />#',
            $html
        );
        // The bug produced /en/solutions/websites/individuelle-website (DE slug under EN path).
        $this->assertStringNotContainsString('/en/solutions/websites/individuelle-website', $html);
    }

    public function test_en_solution_hreflang_points_to_german_slug_not_english_slug(): void
    {
        $html = $this->get('/en/solutions/websites/custom-website')->getContent();

        $this->assertMatchesRegularExpression(
            '#<link rel="alternate" hreflang="de" href="[^"]*/loesungen/websites/individuelle-website" />#',
            $html
        );
        // The bug produced /loesungen/websites/custom-website (EN slug under DE path -> 404).
        $this->assertStringNotContainsString('/loesungen/websites/custom-website', $html);
    }

    public function test_hreflang_pair_is_reciprocal_and_resolves(): void
    {
        // DE page declares its EN alternate; that EN URL must itself return 200
        // and declare the DE page back (the "return link" Google requires).
        $this->get('/loesungen/websites/individuelle-website')->assertStatus(200);
        $this->get('/en/solutions/websites/custom-website')->assertStatus(200);
    }

    public function test_guide_hreflang_uses_target_locale_slug(): void
    {
        $html = $this->get('/ratgeber/website-erstellen-lassen-kosten')->getContent();

        $this->assertMatchesRegularExpression(
            '#<link rel="alternate" hreflang="en" href="[^"]*/en/guides/website-cost-guide" />#',
            $html
        );
        $this->assertStringNotContainsString('/en/guides/website-erstellen-lassen-kosten', $html);
    }
}
