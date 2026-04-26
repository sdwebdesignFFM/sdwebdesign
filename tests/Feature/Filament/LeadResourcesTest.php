<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\WhitepaperLeads\Pages\ListWhitepaperLeads;
use App\Filament\Resources\WorkshopRequests\Pages\ListWorkshopRequests;
use App\Filament\Resources\WorkshopRequests\Pages\ViewWorkshopRequest;
use App\Filament\Resources\WorkshopRequests\WorkshopRequestResource;
use App\Models\User;
use App\Models\WhitepaperLead;
use App\Models\WorkshopRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LeadResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_workshop_requests_list_page_renders_with_records(): void
    {
        WorkshopRequest::create([
            'workshop_slug' => 'plattform-discovery',
            'name' => 'Maria Beispiel',
            'email' => 'maria@beispielfirma.de',
            'company' => 'Beispielfirma GmbH',
            'phone' => '+49 69 1234567',
        ]);

        Livewire::test(ListWorkshopRequests::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(WorkshopRequest::all());
    }

    public function test_workshop_request_view_page_renders(): void
    {
        $request = WorkshopRequest::create([
            'workshop_slug' => 'plattform-discovery',
            'name' => 'Otto Test',
            'email' => 'otto@test.de',
            'trigger_question' => 'Workflows scheitern an Standard-Software.',
            'industry' => 'Personalvermittlung',
            'workflow_areas' => ['Disposition', 'Kundenportal'],
            'existing_systems' => ['Personio', 'Excel'],
            'procurement_stage' => 'Angebote eingeholt',
            'budget_indication' => '6-stellig',
            'briefing_notes' => 'Zwei vorherige Tools sind gescheitert.',
        ]);

        Livewire::test(ViewWorkshopRequest::class, ['record' => $request->id])
            ->assertSuccessful()
            ->assertSee('Otto Test')
            ->assertSee('otto@test.de')
            ->assertSee('Workflows scheitern an Standard-Software.')
            ->assertSee('Disposition')
            ->assertSee('Personio')
            ->assertSee('Zwei vorherige Tools sind gescheitert.');
    }

    public function test_workshop_resource_disables_create(): void
    {
        $this->assertFalse(WorkshopRequestResource::canCreate());
    }

    public function test_workshop_resource_navigation_badge_counts_unnotified(): void
    {
        WorkshopRequest::create([
            'workshop_slug' => 'plattform-discovery',
            'name' => 'A', 'email' => 'a@a.de',
            'admin_notified_at' => now(),
        ]);
        WorkshopRequest::create([
            'workshop_slug' => 'plattform-discovery',
            'name' => 'B', 'email' => 'b@b.de',
            // admin_notified_at left null — should be the only one counted
        ]);

        $this->assertSame('1', WorkshopRequestResource::getNavigationBadge());
    }

    public function test_whitepaper_leads_list_page_renders(): void
    {
        WhitepaperLead::create([
            'whitepaper_slug' => 'platform-vs-standard',
            'email' => 'lead@firma.de',
            'name' => 'Test Lead',
            'newsletter_opt_in' => true,
        ]);

        Livewire::test(ListWhitepaperLeads::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(WhitepaperLead::all());
    }
}
