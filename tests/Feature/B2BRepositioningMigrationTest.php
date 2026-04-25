<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifies the 2026_04_25_090505_apply_b2b_platform_repositioning migration
 * applied the new strategic positioning correctly to the homepage hero and
 * the solution-hub ordering. RefreshDatabase runs all migrations during
 * setup, so we just check the resulting state.
 *
 * The migration runs against the production seed data structure, but in tests
 * the seed data is empty — so we seed the minimum needed pages first, then
 * re-run the migration to verify it applies its changes.
 */
class B2BRepositioningMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedHomepage(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => [
                        'title' => 'Old hero title',
                        'subtitle' => 'Old hero subtitle',
                    ],
                ],
            ],
        ]);
    }

    private function seedSolutionHubs(): void
    {
        $hubs = [
            ['websites', 'Websites', 1],
            ['plattformen', 'Plattformen', 2],
            ['e-commerce', 'E-Commerce', 3],
            ['mobile-anwendungen', 'Mobile', 4],
        ];
        foreach ($hubs as [$slug, $title, $sort]) {
            Page::factory()->create([
                'type' => Page::TYPE_SOLUTION_HUB,
                'parent_id' => null,
                'slug' => ['de' => $slug, 'en' => $slug],
                'title' => ['de' => $title, 'en' => $title],
                'is_active' => true,
                'sort_order' => $sort,
                'content' => ['de' => []],
            ]);
        }
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_25_090505_apply_b2b_platform_repositioning.php');
        $migration->up();
    }

    public function test_migration_updates_homepage_hero_with_new_positioning(): void
    {
        $home = $this->seedHomepage();

        $this->runMigration();

        $home->refresh();
        $content = $home->getTranslation('content', 'de');

        $this->assertSame(
            'Maßgeschneiderte B2B-Plattformen für etablierte Mittelständler',
            $content['hero']['title']
        );
        $this->assertStringContainsString(
            'Wenn Standard-Software an Grenzen stößt',
            $content['hero']['subtitle']
        );
    }

    public function test_migration_updates_homepage_meta_title_and_description(): void
    {
        $home = $this->seedHomepage();

        $this->runMigration();

        $home->refresh();
        $this->assertSame(
            'sdWebdesign — B2B-Plattformen für den Mittelstand aus Frankfurt',
            $home->getTranslation('meta_title', 'de')
        );
        $this->assertStringContainsString('B2B-Plattformen', $home->getTranslation('meta_description', 'de'));
    }

    public function test_migration_reorders_solution_hubs_with_plattformen_first(): void
    {
        $this->seedSolutionHubs();

        $this->runMigration();

        $hubs = Page::where('type', Page::TYPE_SOLUTION_HUB)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $this->assertSame('plattformen', $hubs[0]->getTranslation('slug', 'de'));
        $this->assertSame('e-commerce', $hubs[1]->getTranslation('slug', 'de'));
        $this->assertSame('mobile-anwendungen', $hubs[2]->getTranslation('slug', 'de'));
        $this->assertSame('websites', $hubs[3]->getTranslation('slug', 'de'));
    }

    public function test_migration_preserves_other_homepage_sections(): void
    {
        // Set up homepage with extra content blocks beyond hero
        $home = Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Old', 'subtitle' => 'Old'],
                    'why_us' => ['headline' => 'Why us — preserve me'],
                    'process' => ['steps' => ['Step 1', 'Step 2']],
                ],
            ],
        ]);

        $this->runMigration();

        $home->refresh();
        $content = $home->getTranslation('content', 'de');
        $this->assertSame('Why us — preserve me', $content['why_us']['headline']);
        $this->assertSame(['Step 1', 'Step 2'], $content['process']['steps']);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedHomepage();
        $this->seedSolutionHubs();

        $this->runMigration();
        $this->runMigration();

        $hubs = Page::where('type', Page::TYPE_SOLUTION_HUB)->whereNull('parent_id')->orderBy('sort_order')->get();
        $this->assertSame('plattformen', $hubs[0]->getTranslation('slug', 'de'));
    }
}
