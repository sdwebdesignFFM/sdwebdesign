<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SteffenPersonaMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedAboutPage(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_ABOUT,
            'slug' => ['de' => 'ueber-uns', 'en' => 'about-us'],
            'title' => ['de' => 'Über uns', 'en' => 'About'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Technischer Partner...', 'badge' => 'Über uns'],
                    'haltung' => [
                        'title' => 'Haltung & Anspruch',
                        'paragraphs' => [
                            'Wir entwickeln digitale Systeme.',
                            'sdWebdesign ist ein technischer Umsetzungspartner.',
                        ],
                    ],
                    'team' => [
                        'members' => [
                            [
                                'name' => 'Steffen Fasselt',
                                'role' => 'Gründer · Webentwickler',
                                'description' => 'Planung, Architektur und Entwicklung digitaler Systeme sind seit vielen Jahren mein Schwerpunkt.',
                                'icon' => 'user',
                            ],
                            [
                                'name' => 'Daniel Neubauer',
                                'role' => 'SEO & Sichtbarkeit',
                                'description' => 'SEO-Spezialist',
                                'icon' => 'trending-up',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_25_102157_update_steffen_persona_on_about.php');
        $migration->up();
    }

    public function test_migration_updates_steffen_role_and_description(): void
    {
        $about = $this->seedAboutPage();

        $this->runMigration();

        $about->refresh();
        $content = $about->getTranslation('content', 'de');

        $steffen = collect($content['team']['members'])->firstWhere('name', 'Steffen Fasselt');

        $this->assertSame('Senior Product Owner & Plattform-Architekt', $steffen['role']);
        $this->assertStringContainsString('20 Jahren Unternehmer', $steffen['description']);
        $this->assertStringContainsString('über 10 Jahren als Product Owner', $steffen['description']);
    }

    public function test_migration_does_not_touch_other_team_members(): void
    {
        $about = $this->seedAboutPage();

        $this->runMigration();

        $about->refresh();
        $content = $about->getTranslation('content', 'de');

        $daniel = collect($content['team']['members'])->firstWhere('name', 'Daniel Neubauer');
        $this->assertSame('SEO & Sichtbarkeit', $daniel['role']);
        $this->assertSame('SEO-Spezialist', $daniel['description']);
    }

    public function test_migration_appends_steffen_bio_to_haltung_paragraphs(): void
    {
        $about = $this->seedAboutPage();

        $this->runMigration();

        $about->refresh();
        $content = $about->getTranslation('content', 'de');

        $paragraphs = $content['haltung']['paragraphs'];
        $appended = collect($paragraphs)->first(fn ($p) => str_contains($p, 'Persönlich von Steffen Fasselt'));

        $this->assertNotNull($appended);
        $this->assertStringContainsString('Bestellplattformen', $appended);
        $this->assertStringContainsString('Vermittlungsplattformen', $appended);
        $this->assertStringContainsString('mitwachsen', $appended);
    }

    public function test_migration_preserves_existing_haltung_paragraphs(): void
    {
        $about = $this->seedAboutPage();

        $this->runMigration();

        $about->refresh();
        $paragraphs = $about->getTranslation('content', 'de')['haltung']['paragraphs'];

        $this->assertContains('Wir entwickeln digitale Systeme.', $paragraphs);
        $this->assertContains('sdWebdesign ist ein technischer Umsetzungspartner.', $paragraphs);
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedAboutPage();

        $this->runMigration();
        $this->runMigration();

        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        $paragraphs = $about->getTranslation('content', 'de')['haltung']['paragraphs'];

        $bioCount = collect($paragraphs)->filter(fn ($p) => str_contains($p, 'Persönlich von Steffen Fasselt'))->count();
        $this->assertSame(1, $bioCount, 'Bio paragraph must not be appended twice when migration runs again');
    }

    public function test_migration_down_restores_previous_steffen_data(): void
    {
        $about = $this->seedAboutPage();

        $this->runMigration();

        $migration = require database_path('migrations/2026_04_25_102157_update_steffen_persona_on_about.php');
        $migration->down();

        $about->refresh();
        $content = $about->getTranslation('content', 'de');

        $steffen = collect($content['team']['members'])->firstWhere('name', 'Steffen Fasselt');
        $this->assertSame('Gründer · Webentwickler', $steffen['role']);

        $paragraphs = $content['haltung']['paragraphs'];
        $stillHasBio = collect($paragraphs)->first(fn ($p) => str_contains($p, 'Persönlich von Steffen Fasselt'));
        $this->assertNull($stillHasBio);
    }
}
