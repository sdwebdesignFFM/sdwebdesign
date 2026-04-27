<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NormatecReferenceTechSectionsRemovalTest extends TestCase
{
    use RefreshDatabase;

    private function seedNormatecWithSensitiveTechSections(): Page
    {
        // References live under a TYPE_REFERENCES parent.
        $referencesHub = Page::factory()->create([
            'type' => Page::TYPE_REFERENCES,
            'slug' => ['de' => 'referenzen', 'en' => 'references'],
            'title' => ['de' => 'Referenzen', 'en' => 'References'],
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'parent_id' => $referencesHub->id,
            'slug' => ['de' => 'zeiterfassung-einsatzplanung', 'en' => 'time-tracking-scheduling'],
            'title' => ['de' => 'Normatec', 'en' => 'Normatec'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Normatec', 'tagline' => 'Workforce-Management'],
                    'description' => 'Beschreibung.',
                    'challenge' => ['title' => 'Herausforderung', 'text' => 'X'],
                    'solution' => ['title' => 'Lösung', 'text' => 'Y'],
                    'tech_stack' => ['Laravel · Backend', 'Filament · Admin', 'Azure SSO', 'Dropbox Sign'],
                    'technologies' => ['Laravel 11', 'Vue 3', 'Hetzner Cloud', 'Azure AD'],
                    'technical_details' => [
                        ['title' => 'Architektur', 'description' => '127+ Migrations', 'items' => ['x']],
                        ['title' => 'Compliance', 'description' => 'AÜG / DSGVO', 'items' => ['y']],
                        ['title' => 'DevOps', 'description' => 'CI/CD', 'items' => ['z']],
                    ],
                    'impact_results' => ['Old impact 1', 'Old impact 2'],
                    'features' => [
                        ['title' => 'Disposition', 'description' => 'D'],
                        ['title' => 'Reporting', 'description' => 'R'],
                    ],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_27_103339_remove_sensitive_tech_details_from_normatec_reference.php');
        $migration->up();
    }

    public function test_migration_empties_three_sensitive_sections(): void
    {
        $page = $this->seedNormatecWithSensitiveTechSections();

        $this->runMigration();

        $page->refresh();
        $content = $page->getTranslation('content', 'de');

        $this->assertSame([], $content['tech_stack']);
        $this->assertSame([], $content['technologies']);
        $this->assertSame([], $content['technical_details']);
    }

    public function test_migration_refreshes_impact_results_with_narrative_value(): void
    {
        $this->seedNormatecWithSensitiveTechSections();

        $this->runMigration();

        $page = Page::firstWhere('slug->de', 'zeiterfassung-einsatzplanung');
        $impacts = $page->getTranslation('content', 'de')['impact_results'];

        $this->assertGreaterThanOrEqual(5, count($impacts));
        $allText = implode(' ', $impacts);
        $this->assertStringContainsString('Excel', $allText);
        $this->assertStringContainsString('eingebetteter product owner', strtolower($allText));
        $this->assertStringContainsString('Vendor', $allText);
    }

    public function test_migration_does_not_touch_other_sections(): void
    {
        $this->seedNormatecWithSensitiveTechSections();

        $this->runMigration();

        $page = Page::firstWhere('slug->de', 'zeiterfassung-einsatzplanung');
        $content = $page->getTranslation('content', 'de');

        $this->assertSame('Herausforderung', $content['challenge']['title']);
        $this->assertSame('Lösung', $content['solution']['title']);
        $this->assertSame('Beschreibung.', $content['description']);
        $this->assertCount(2, $content['features']);
    }

    public function test_migration_skips_when_normatec_page_missing(): void
    {
        $this->runMigration();

        $this->assertNull(Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first());
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedNormatecWithSensitiveTechSections();

        $this->runMigration();
        $this->runMigration();

        $page = Page::firstWhere('slug->de', 'zeiterfassung-einsatzplanung');
        $this->assertSame([], $page->getTranslation('content', 'de')['tech_stack']);
    }
}
