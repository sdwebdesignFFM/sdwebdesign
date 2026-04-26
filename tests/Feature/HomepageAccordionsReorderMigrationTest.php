<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageAccordionsReorderMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedHomepageWithLegacyAccordions(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Hero'],
                    'solutions' => [
                        'badge' => 'Lösungsübersicht',
                        'title' => 'Unsere Lösungen',
                        'subtitle' => 'subtitle here',
                        'growth_title' => 'Wachstum',
                        'microcopy' => 'leave me alone',
                        'accordions' => [
                            ['number' => '01', 'title' => 'Unternehmenswebsites mit Substanz', 'link' => '/loesungen/websites'],
                            ['number' => '02', 'title' => 'Digitale Plattformen & Webanwendungen', 'link' => '/loesungen/plattformen'],
                            ['number' => '03', 'title' => 'E-Commerce', 'link' => '/loesungen/e-commerce'],
                            ['number' => '04', 'title' => 'Mobile', 'link' => '/loesungen/mobile-anwendungen'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_26_102329_reorder_homepage_solutions_accordions_to_lead_with_plattformen.php');
        $migration->up();
    }

    public function test_migration_reorders_accordions_so_plattformen_is_first(): void
    {
        $home = $this->seedHomepageWithLegacyAccordions();

        $this->runMigration();

        $home->refresh();
        $accordions = $home->getTranslation('content', 'de')['solutions']['accordions'];

        $this->assertSame('Digitale Plattformen & Webanwendungen', $accordions[0]['title']);
        $this->assertSame('E-Commerce & Online-Shops', $accordions[1]['title']);
        $this->assertSame('Mobile Anwendungen (iOS / Android / PWA)', $accordions[2]['title']);
        $this->assertSame('Unternehmenswebsites mit Substanz', $accordions[3]['title']);
    }

    public function test_migration_sharpens_plattformen_card_for_b2b_mittelstand(): void
    {
        $this->seedHomepageWithLegacyAccordions();

        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $plattformen = $home->getTranslation('content', 'de')['solutions']['accordions'][0];

        $this->assertStringContainsString('Maßgeschneiderte B2B-Plattformen', $plattformen['subtitle']);
        $this->assertStringContainsString('Mittelständler', $plattformen['subtitle']);
        $this->assertStringContainsString('Personio', $plattformen['description']);
        $this->assertContains('Workforce-Management & Disposition', $plattformen['suitable_for']);
        $this->assertContains('Eingebetteter Product Owner statt Ticket-System', $plattformen['character']);
    }

    public function test_migration_does_not_touch_other_solutions_keys(): void
    {
        $this->seedHomepageWithLegacyAccordions();

        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $solutions = $home->getTranslation('content', 'de')['solutions'];

        $this->assertSame('Lösungsübersicht', $solutions['badge']);
        $this->assertSame('Unsere Lösungen', $solutions['title']);
        $this->assertSame('subtitle here', $solutions['subtitle']);
        $this->assertSame('Wachstum', $solutions['growth_title']);
        $this->assertSame('leave me alone', $solutions['microcopy']);
    }

    public function test_homepage_renders_new_order_after_migration(): void
    {
        $this->seedHomepageWithLegacyAccordions();

        $this->runMigration();

        $body = $this->get('/')->assertStatus(200)->getContent();

        $platformenPos = strpos($body, 'Digitale Plattformen &amp; Webanwendungen');
        $eCommercePos = strpos($body, 'E-Commerce &amp; Online-Shops');
        $mobilePos = strpos($body, 'Mobile Anwendungen (iOS / Android / PWA)');
        $websitesPos = strpos($body, 'Unternehmenswebsites mit Substanz');

        $this->assertNotFalse($platformenPos);
        $this->assertLessThan($eCommercePos, $platformenPos);
        $this->assertLessThan($mobilePos, $eCommercePos);
        $this->assertLessThan($websitesPos, $mobilePos);

        $this->assertStringContainsString('Maßgeschneiderte B2B-Plattformen', $body);
    }

    public function test_migration_skips_when_homepage_missing(): void
    {
        $this->runMigration();
        $this->assertNull(Page::where('type', Page::TYPE_HOME)->first());
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedHomepageWithLegacyAccordions();

        $this->runMigration();
        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $accordions = $home->getTranslation('content', 'de')['solutions']['accordions'];

        $this->assertCount(4, $accordions);
        $this->assertSame('Digitale Plattformen & Webanwendungen', $accordions[0]['title']);
    }
}
