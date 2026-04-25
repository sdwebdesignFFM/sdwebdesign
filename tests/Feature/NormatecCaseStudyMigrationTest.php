<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NormatecCaseStudyMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function seedExistingReferencePages(): void
    {
        // The existing Normatec page (anonymized as "Dienstleistungsunternehmen")
        Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'slug' => ['de' => 'zeiterfassung-einsatzplanung', 'en' => 'zeiterfassung-einsatzplanung'],
            'title' => ['de' => 'Zeiterfassung & Einsatzplanung', 'en' => 'Time Tracking'],
            'is_active' => true,
            'sort_order' => 2,
            'content' => [
                'de' => [
                    'hero' => ['category' => 'Web-App', 'tagline' => 'Old tagline'],
                    'meta' => [
                        ['label' => 'Kunde', 'value' => 'Dienstleistungsunternehmen'],
                    ],
                ],
            ],
        ]);

        Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'slug' => ['de' => 'kosmetikerin-ecommerce-app', 'en' => 'kosmetikerin-ecommerce-app'],
            'title' => ['de' => 'Kosmetikerin', 'en' => 'Kosmetikerin'],
            'is_active' => true,
            'sort_order' => 1,
            'content' => ['de' => []],
        ]);

        Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'slug' => ['de' => 'gewapur-ecommerce', 'en' => 'gewapur-ecommerce'],
            'title' => ['de' => 'Gewapur', 'en' => 'Gewapur'],
            'is_active' => true,
            'sort_order' => 3,
            'content' => ['de' => []],
        ]);

        Page::factory()->create([
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'slug' => ['de' => 'digitale-zeiterfassung', 'en' => 'digitale-zeiterfassung'],
            'title' => ['de' => 'Digitale Zeiterfassung', 'en' => 'Digital Time Tracking'],
            'is_active' => true,
            'sort_order' => 4,
            'content' => ['de' => []],
        ]);
    }

    private function runMigration(): void
    {
        $migration = require database_path('migrations/2026_04_25_125246_upgrade_normatec_case_study_to_lead_reference.php');
        $migration->up();
    }

    public function test_normatec_replaces_anonymized_customer_name(): void
    {
        $this->seedExistingReferencePages();

        $this->runMigration();

        $page = Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first();
        $content = $page->getTranslation('content', 'de');

        $kunde = collect($content['meta'])->firstWhere('label', 'Kunde');
        $this->assertSame('Normatec', $kunde['value']);
    }

    public function test_normatec_title_is_updated(): void
    {
        $this->seedExistingReferencePages();

        $this->runMigration();

        $page = Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first();
        $this->assertSame('Normatec — Workforce-Management-Plattform', $page->getTranslation('title', 'de'));
    }

    public function test_normatec_becomes_lead_reference_with_sort_order_1(): void
    {
        $this->seedExistingReferencePages();

        $this->runMigration();

        $references = Page::where('type', Page::TYPE_REFERENCE_DETAIL)
            ->orderBy('sort_order')
            ->get();

        $this->assertSame('zeiterfassung-einsatzplanung', $references[0]->getTranslation('slug', 'de'));
        $this->assertSame(1, $references[0]->sort_order);
    }

    public function test_other_references_are_pushed_back(): void
    {
        $this->seedExistingReferencePages();

        $this->runMigration();

        $gewapur = Page::where('slug->de', 'gewapur-ecommerce')->first();
        $kosmetikerin = Page::where('slug->de', 'kosmetikerin-ecommerce-app')->first();
        $digitalZeit = Page::where('slug->de', 'digitale-zeiterfassung')->first();

        $this->assertSame(2, $gewapur->sort_order);
        $this->assertSame(3, $kosmetikerin->sort_order);
        $this->assertSame(4, $digitalZeit->sort_order);
    }

    public function test_solution_section_lists_specific_tech_capabilities(): void
    {
        $this->seedExistingReferencePages();

        $this->runMigration();

        $page = Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first();
        $content = $page->getTranslation('content', 'de');

        $solutionItems = $content['solution']['items'];
        $itemsString = implode(' ', $solutionItems);

        $this->assertStringContainsString('Smart Availability Checking', $itemsString);
        $this->assertStringContainsString('Microsoft Azure SSO', $itemsString);
        $this->assertStringContainsString('Dropbox Sign', $itemsString);
        $this->assertStringContainsString('CarPool', $itemsString);
        $this->assertStringContainsString('CRA-Compliance', $itemsString);
    }

    public function test_no_confidential_business_details_in_content(): void
    {
        // Owner explicitly said: no confidential business details (employee
        // counts, revenue figures, KPIs). Verify nothing in the seeded content
        // contains those patterns.
        $this->seedExistingReferencePages();

        $this->runMigration();

        $page = Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first();
        $serialized = json_encode($page->getTranslation('content', 'de'), JSON_UNESCAPED_UNICODE);

        // Patterns to forbid: "X Mitarbeiter", "X Mio €", "X Millionen", "X.000 €"
        $this->assertDoesNotMatchRegularExpression('/\d+\s*Mitarbeiter/i', $serialized);
        $this->assertDoesNotMatchRegularExpression('/\d+\s*Mio\.?\s*€/i', $serialized);
        $this->assertDoesNotMatchRegularExpression('/\d+\s*Millionen?\s*€/i', $serialized);
    }

    public function test_normatec_detail_page_renders_without_template_errors(): void
    {
        // The first version of this migration set tech_stack and impact_results
        // as nested arrays — the reference-detail template iterates those as
        // flat strings, which crashed htmlspecialchars(). The fix migration
        // (2026_04_25_125731) corrects the shape. This test guards against
        // regressing it.
        $this->seedExistingReferencePages();
        $this->runMigration();

        $fixMigration = require database_path('migrations/2026_04_25_125731_fix_normatec_section_structures.php');
        $fixMigration->up();

        $response = $this->get('/referenzen/zeiterfassung-einsatzplanung');

        $response->assertStatus(200);
        $response->assertSee('Normatec');
        $response->assertSee('Workforce-Management');
    }

    public function test_migration_is_idempotent(): void
    {
        $this->seedExistingReferencePages();

        $this->runMigration();
        $this->runMigration();

        $page = Page::where('slug->de', 'zeiterfassung-einsatzplanung')->first();
        $content = $page->getTranslation('content', 'de');
        $kunde = collect($content['meta'])->firstWhere('label', 'Kunde');
        $this->assertSame('Normatec', $kunde['value']);
        $this->assertSame(1, $page->sort_order);
    }
}
