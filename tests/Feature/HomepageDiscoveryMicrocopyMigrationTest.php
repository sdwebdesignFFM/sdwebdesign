<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageDiscoveryMicrocopyMigrationTest extends TestCase
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
                    'hero' => ['title' => 'Hero'],
                    'solutions' => ['title' => 'Lösungen'],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_26_102946_link_homepage_solutions_microcopy_to_discovery.php');
        $migration->up();
    }

    public function test_microcopy_now_points_at_discovery_workshop(): void
    {
        $this->seedHomepage();

        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $solutions = $home->getTranslation('content', 'de')['solutions'];

        $this->assertStringContainsString('Discovery-Workshop', $solutions['microcopy']);
        $this->assertStringContainsString('990', $solutions['microcopy']);
        $this->assertSame('Discovery-Workshop ansehen', $solutions['microcopy_button']);
        $this->assertSame('/loesungen/plattformen/plattform-discovery', $solutions['microcopy_link']);
    }

    public function test_homepage_renders_microcopy_as_anchor_to_discovery(): void
    {
        $this->seedHomepage();

        $this->runMigration();

        $body = $this->get('/')->assertStatus(200)->getContent();

        $this->assertStringContainsString('href="/loesungen/plattformen/plattform-discovery"', $body);
        $this->assertStringContainsString('Discovery-Workshop ansehen', $body);
        $this->assertStringContainsString('Discovery-Workshop klären wir das gemeinsam', $body);
    }

    public function test_microcopy_falls_back_to_contact_modal_when_no_link_set(): void
    {
        // No migration — homepage rendered with template defaults
        $this->seedHomepage();

        $body = $this->get('/')->assertStatus(200)->getContent();

        $this->assertStringContainsString("Livewire.dispatch('openContactModal')", $body);
        $this->assertStringNotContainsString('href="/loesungen/plattformen/plattform-discovery"', $body);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedHomepage();

        $this->runMigration();
        $this->runMigration();

        $home = Page::where('type', Page::TYPE_HOME)->first();
        $this->assertSame(
            '/loesungen/plattformen/plattform-discovery',
            $home->getTranslation('content', 'de')['solutions']['microcopy_link']
        );
    }
}
