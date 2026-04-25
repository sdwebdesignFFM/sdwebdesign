<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferencesOverviewMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedReferencesOverviewWithLegacyProjects(): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_REFERENCES,
            'slug' => ['de' => 'referenzen', 'en' => 'references'],
            'title' => ['de' => 'Referenzen', 'en' => 'References'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'projects' => [
                        [
                            'icon' => 'sparkles',
                            'number' => '01',
                            'title' => 'Kosmetikerin.org – E-Commerce + iOS CRM',
                            'client' => 'Selbstständige Kosmetikerin',
                            'detail_slug' => 'kosmetikerin-ecommerce-app',
                        ],
                        [
                            'icon' => 'clock',
                            'number' => '02',
                            'title' => 'Zeiterfassungs- & Einsatzplanungs-Webapp',
                            'client' => 'Mittelständisches Dienstleistungsunternehmen',
                            'detail_slug' => 'zeiterfassung-einsatzplanung',
                            'tech_stack' => [
                                'Frontend: React, TypeScript',
                                'Backend: Node.js, Express',
                                'Datenbank: PostgreSQL',
                            ],
                            'results' => [
                                'Zeitersparnis von ca. 15 Stunden/Woche',
                                'Fehlerquote bei Abrechnungen auf 0% reduziert',
                            ],
                        ],
                        [
                            'icon' => 'shopping-cart',
                            'number' => '03',
                            'title' => 'Gewapur.de – B2C E-Commerce Plattform',
                            'client' => 'Wasseraufbereitungs-Unternehmen',
                            'detail_slug' => 'gewapur-ecommerce',
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_25_130120_update_references_overview_with_normatec_lead.php');
        $migration->up();
    }

    public function test_normatec_is_first_project_after_migration(): void
    {
        $page = $this->seedReferencesOverviewWithLegacyProjects();

        $this->runMigration();

        $page->refresh();
        $projects = $page->getTranslation('content', 'de')['projects'];

        $this->assertSame('Normatec — Workforce-Management-Plattform', $projects[0]['title']);
        $this->assertSame('Normatec', $projects[0]['client']);
        $this->assertSame('zeiterfassung-einsatzplanung', $projects[0]['detail_slug']);
    }

    public function test_normatec_tech_stack_is_corrected_to_real_stack(): void
    {
        $this->seedReferencesOverviewWithLegacyProjects();

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_REFERENCES)->first();
        $normatec = $page->getTranslation('content', 'de')['projects'][0];

        $stackText = implode(' ', $normatec['tech_stack']);
        $this->assertStringContainsString('Laravel', $stackText);
        $this->assertStringContainsString('Filament', $stackText);
        $this->assertStringContainsString('Inertia', $stackText);
        $this->assertStringNotContainsString('React', $stackText);
        $this->assertStringNotContainsString('Node.js', $stackText);
    }

    public function test_normatec_results_have_no_concrete_confidential_numbers(): void
    {
        $this->seedReferencesOverviewWithLegacyProjects();

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_REFERENCES)->first();
        $normatec = $page->getTranslation('content', 'de')['projects'][0];
        $resultsText = implode(' ', $normatec['results']);

        // Owner-marked-as-confidential patterns
        $this->assertDoesNotMatchRegularExpression('/\d+\s*Stunden\s*\//i', $resultsText);
        $this->assertDoesNotMatchRegularExpression('/Fehlerquote.*\d+\s*%/i', $resultsText);
        $this->assertDoesNotMatchRegularExpression('/\d+\s*Mitarbeiter/i', $resultsText);
    }

    public function test_gewapur_and_kosmetikerin_remain_with_renumbered_positions(): void
    {
        $this->seedReferencesOverviewWithLegacyProjects();

        $this->runMigration();

        $page = Page::where('type', Page::TYPE_REFERENCES)->first();
        $projects = $page->getTranslation('content', 'de')['projects'];

        $this->assertCount(3, $projects);
        $this->assertStringContainsString('Gewapur', $projects[1]['title']);
        $this->assertSame('02', $projects[1]['number']);
        $this->assertStringContainsString('Kosmetikerin', $projects[2]['title']);
        $this->assertSame('03', $projects[2]['number']);
    }

    public function test_overview_page_renders_normatec_first_after_migration(): void
    {
        $this->seedReferencesOverviewWithLegacyProjects();

        // Need a TYPE_REFERENCE_DETAIL page so the controller doesn't fail loading the list
        Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'slug' => ['de' => 'zeiterfassung-einsatzplanung', 'en' => 'zeiterfassung-einsatzplanung'],
            'title' => ['de' => 'Normatec — Workforce-Management-Plattform'],
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $this->runMigration();

        $response = $this->get('/referenzen');
        $response->assertStatus(200);

        $html = $response->getContent();
        $normatecPos = strpos($html, 'Normatec');
        $kosmetikerinPos = strpos($html, 'Kosmetikerin');

        $this->assertNotFalse($normatecPos, 'Normatec must appear on overview page');
        $this->assertNotFalse($kosmetikerinPos, 'Kosmetikerin must still appear on overview page');
        $this->assertLessThan($kosmetikerinPos, $normatecPos, 'Normatec must appear before Kosmetikerin in DOM order');
    }
}
