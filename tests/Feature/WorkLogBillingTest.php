<?php

namespace Tests\Feature;

use App\Filament\Pages\WorkLogBilling;
use App\Filament\Resources\WorkLogs\Pages\ManageWorkLogs;
use App\Models\Client;
use App\Models\Setting;
use App\Models\User;
use App\Models\WorkLog;
use App\Services\WorkLog\WorkLogService;
use Carbon\Carbon;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WorkLogBillingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        Setting::firstOrCreate(['id' => 1], [
            'company_name' => 'Test Company',
            'email' => 'test@test.de',
            'default_hourly_rate' => 85.00,
        ]);
    }

    public function test_service_mark_as_billed_without_invoice(): void
    {
        $client = Client::factory()->create();
        $workLogs = WorkLog::factory()->count(3)->create([
            'client_id' => $client->id,
            'is_billed' => false,
        ]);

        $service = app(WorkLogService::class);
        $count = $service->markAsBilled($workLogs->pluck('id')->toArray());

        $this->assertEquals(3, $count);

        foreach ($workLogs as $workLog) {
            $workLog->refresh();
            $this->assertTrue($workLog->is_billed);
            $this->assertNull($workLog->invoice_id);
        }
    }

    public function test_service_mark_as_billed_skips_already_billed(): void
    {
        $client = Client::factory()->create();
        $unbilled = WorkLog::factory()->create([
            'client_id' => $client->id,
            'is_billed' => false,
        ]);
        $billed = WorkLog::factory()->create([
            'client_id' => $client->id,
            'is_billed' => true,
        ]);

        $service = app(WorkLogService::class);
        $count = $service->markAsBilled([$unbilled->id, $billed->id]);

        $this->assertEquals(1, $count);
    }

    public function test_table_row_action_marks_single_entry_as_billed(): void
    {
        $workLog = WorkLog::factory()->create(['is_billed' => false]);

        Livewire::test(ManageWorkLogs::class)
            ->callTableAction('mark_as_billed', $workLog);

        $workLog->refresh();
        $this->assertTrue($workLog->is_billed);
        $this->assertNull($workLog->invoice_id);
    }

    public function test_table_row_action_hidden_for_billed_entries(): void
    {
        $workLog = WorkLog::factory()->create(['is_billed' => true]);

        Livewire::test(ManageWorkLogs::class)
            ->assertTableActionHidden('mark_as_billed', $workLog);
    }

    public function test_table_bulk_action_marks_entries_as_billed(): void
    {
        $workLogs = WorkLog::factory()->count(3)->create(['is_billed' => false]);

        Livewire::test(ManageWorkLogs::class)
            ->selectTableRecords($workLogs->pluck('id')->toArray())
            ->callAction(TestAction::make('mark_as_billed')->table()->bulk());

        foreach ($workLogs as $workLog) {
            $workLog->refresh();
            $this->assertTrue($workLog->is_billed);
            $this->assertNull($workLog->invoice_id);
        }
    }

    public function test_billing_page_mark_as_billed_without_invoice(): void
    {
        $client = Client::factory()->create();
        $workLogs = WorkLog::factory()->count(2)->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
            'is_billed' => false,
        ]);

        Livewire::test(WorkLogBilling::class)
            ->call('selectGroup', $client->id, '2026-01')
            ->call('markAsBilled');

        foreach ($workLogs as $workLog) {
            $workLog->refresh();
            $this->assertTrue($workLog->is_billed);
            $this->assertNull($workLog->invoice_id);
        }
    }

    public function test_billing_page_mark_as_billed_clears_selection(): void
    {
        $client = Client::factory()->create();
        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
            'is_billed' => false,
        ]);

        Livewire::test(WorkLogBilling::class)
            ->call('selectGroup', $client->id, '2026-01')
            ->call('markAsBilled')
            ->assertSet('selectedClientId', null)
            ->assertSet('selectedMonth', null)
            ->assertSet('selectedWorkLogIds', []);
    }

    public function test_billing_page_month_filter(): void
    {
        $client = Client::factory()->create();

        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
            'is_billed' => false,
        ]);
        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-02-15'),
            'is_billed' => false,
        ]);

        // Without filter: both months visible, can select either group
        Livewire::test(WorkLogBilling::class)
            ->assertSet('filterMonth', null)
            ->call('selectGroup', $client->id, '2026-01')
            ->assertSet('selectedClientId', $client->id)
            ->assertSet('selectedMonth', '2026-01');

        // With filter: set to January, then selecting February group
        // still works (filter only affects sidebar display)
        Livewire::test(WorkLogBilling::class)
            ->set('filterMonth', '2026-01')
            ->call('selectGroup', $client->id, '2026-01')
            ->assertSet('selectedClientId', $client->id);
    }

    public function test_billing_page_month_filter_filters_summary(): void
    {
        $client = Client::factory()->create();

        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
            'is_billed' => false,
        ]);
        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-02-15'),
            'is_billed' => false,
        ]);

        // Test the service/page logic directly
        $page = new WorkLogBilling;
        $page->filterMonth = null;

        $allSummary = $page->getUnbilledSummary();
        $this->assertEquals(2, $allSummary->count());

        $page->filterMonth = '2026-01';
        $filteredSummary = $page->getUnbilledSummary();
        $this->assertEquals(1, $filteredSummary->count());
        $this->assertEquals('2026-01', $filteredSummary->first()['month']->format('Y-m'));
    }

    public function test_billing_page_mark_as_billed_with_no_selection_shows_warning(): void
    {
        Livewire::test(WorkLogBilling::class)
            ->call('markAsBilled')
            ->assertNotified('Keine Einträge ausgewählt');
    }
}
