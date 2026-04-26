<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhitepaperVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function seedPlattformenHub(array $extraContent = []): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'is_active' => true,
            'sort_order' => 1,
            'content' => [
                'de' => array_merge([
                    'hero' => ['title' => 'Hub'],
                    'cta' => ['title' => 'CTA'],
                ], $extraContent),
            ],
        ]);
    }

    public function test_whitepaper_promo_block_renders_when_set_on_hub(): void
    {
        $this->seedPlattformenHub([
            'whitepaper_promo' => [
                'label' => 'Vorab-Lektüre',
                'title' => 'Mein Whitepaper-Titel',
                'text' => 'Beschreibung.',
                'button_text' => 'Anfordern',
                'link' => '/whitepaper/test',
            ],
        ]);

        $body = $this->get('/loesungen/plattformen')->assertStatus(200)->getContent();

        $this->assertStringContainsString('Vorab-Lektüre', $body);
        $this->assertStringContainsString('Mein Whitepaper-Titel', $body);
        $this->assertStringContainsString('Beschreibung.', $body);
        $this->assertStringContainsString('href="/whitepaper/test"', $body);
        $this->assertStringContainsString('Anfordern', $body);
    }

    public function test_whitepaper_promo_block_absent_when_not_set(): void
    {
        $this->seedPlattformenHub();

        $body = $this->get('/loesungen/plattformen')->assertStatus(200)->getContent();

        $this->assertStringNotContainsString('Vorab-Lektüre', $body);
    }

    public function test_migration_sets_whitepaper_promo_on_plattformen_hub(): void
    {
        $hub = $this->seedPlattformenHub();

        $migration = require database_path('migrations/2026_04_26_132640_add_whitepaper_promo_to_plattformen_hub.php');
        $migration->up();

        $hub->refresh();
        $promo = $hub->getTranslation('content', 'de')['whitepaper_promo'];

        $this->assertSame('Vorab-Lektüre · Whitepaper', $promo['label']);
        $this->assertStringContainsString('Eigene Plattform oder Standard-Software', $promo['title']);
        $this->assertSame('/whitepaper/eigene-plattform-vs-standard-software', $promo['link']);
    }

    public function test_migration_skips_when_hub_missing(): void
    {
        $migration = require database_path('migrations/2026_04_26_132640_add_whitepaper_promo_to_plattformen_hub.php');
        $migration->up();

        $this->assertNull(Page::where('slug->de', 'plattformen')->whereNull('parent_id')->first());
    }

    public function test_footer_links_to_whitepaper_on_german_pages(): void
    {
        // Any frontend page renders the footer.
        Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => ['de' => ['hero' => ['title' => 'Hi']]],
        ]);

        $body = $this->get('/')->assertStatus(200)->getContent();

        $this->assertStringContainsString('href="/whitepaper/eigene-plattform-vs-standard-software"', $body);
        $this->assertStringContainsString('Whitepaper', $body);
    }
}
