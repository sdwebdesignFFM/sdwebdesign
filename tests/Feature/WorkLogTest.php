<?php

namespace Tests\Feature;

use App\Filament\Resources\WorkLogs\Pages\ManageWorkLogs;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\User;
use App\Models\WorkLog;
use App\Services\WorkLog\WorkLogService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WorkLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        // Ensure settings exist
        Setting::firstOrCreate(['id' => 1], [
            'company_name' => 'Test Company',
            'email' => 'test@test.de',
            'default_hourly_rate' => 85.00,
        ]);
    }

    public function test_worklog_model_can_be_created(): void
    {
        $client = Client::factory()->create();
        $workLog = WorkLog::factory()->create([
            'client_id' => $client->id,
            'title' => 'Test Arbeit',
            'duration_minutes' => 60,
        ]);

        $this->assertDatabaseHas('work_logs', [
            'client_id' => $client->id,
            'title' => 'Test Arbeit',
            'duration_minutes' => 60,
        ]);
    }

    public function test_worklog_belongs_to_client(): void
    {
        $client = Client::factory()->create();
        $workLog = WorkLog::factory()->create(['client_id' => $client->id]);

        $this->assertEquals($client->id, $workLog->client->id);
    }

    public function test_client_has_worklogs(): void
    {
        $client = Client::factory()->create();
        $workLog = WorkLog::factory()->create(['client_id' => $client->id]);

        $this->assertTrue($client->workLogs->contains($workLog));
    }

    public function test_duration_formatted_attribute(): void
    {
        $workLog = WorkLog::factory()->create(['duration_minutes' => 90]);

        $this->assertEquals('1:30', $workLog->duration_formatted);
    }

    public function test_duration_hours_attribute(): void
    {
        $workLog = WorkLog::factory()->create(['duration_minutes' => 90]);

        $this->assertEquals(1.5, $workLog->duration_hours);
    }

    public function test_effective_hourly_rate_from_worklog(): void
    {
        $workLog = WorkLog::factory()->create([
            'hourly_rate' => 100.00,
        ]);

        $this->assertEquals(100.00, $workLog->effective_hourly_rate);
    }

    public function test_effective_hourly_rate_from_client(): void
    {
        $client = Client::factory()->create(['default_hourly_rate' => 95.00]);
        $workLog = WorkLog::factory()->create([
            'client_id' => $client->id,
            'hourly_rate' => null,
        ]);

        $this->assertEquals(95.00, $workLog->effective_hourly_rate);
    }

    public function test_effective_hourly_rate_from_settings(): void
    {
        $client = Client::factory()->create(['default_hourly_rate' => null]);
        $workLog = WorkLog::factory()->create([
            'client_id' => $client->id,
            'hourly_rate' => null,
        ]);

        $this->assertEquals(85.00, $workLog->effective_hourly_rate);
    }

    public function test_total_amount_attribute(): void
    {
        $workLog = WorkLog::factory()->create([
            'duration_minutes' => 120,
            'hourly_rate' => 100.00,
        ]);

        // 2 hours * 100€ = 200€
        $this->assertEquals(200.00, $workLog->total_amount);
    }

    public function test_unbilled_scope(): void
    {
        WorkLog::factory()->create(['is_billed' => false]);
        WorkLog::factory()->create(['is_billed' => true]);

        $this->assertEquals(1, WorkLog::unbilled()->count());
    }

    public function test_billed_scope(): void
    {
        WorkLog::factory()->create(['is_billed' => false]);
        WorkLog::factory()->create(['is_billed' => true]);

        $this->assertEquals(1, WorkLog::billed()->count());
    }

    public function test_for_client_scope(): void
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        WorkLog::factory()->create(['client_id' => $client1->id]);
        WorkLog::factory()->create(['client_id' => $client2->id]);

        $this->assertEquals(1, WorkLog::forClient($client1->id)->count());
    }

    public function test_for_month_scope(): void
    {
        $client = Client::factory()->create();

        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
        ]);
        WorkLog::factory()->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-02-15'),
        ]);

        $this->assertEquals(1, WorkLog::forMonth(2026, 1)->count());
    }

    public function test_duration_options_generated_correctly(): void
    {
        $options = WorkLog::getDurationOptions();

        $this->assertArrayHasKey(15, $options);
        $this->assertEquals('0:15', $options[15]);

        $this->assertArrayHasKey(60, $options);
        $this->assertEquals('1:00', $options[60]);

        $this->assertArrayHasKey(720, $options);
        $this->assertEquals('12:00', $options[720]);
    }

    public function test_worklogs_list_page_loads(): void
    {
        Livewire::test(ManageWorkLogs::class)
            ->assertOk();
    }

    public function test_worklog_can_be_created_via_filament(): void
    {
        $client = Client::factory()->create();

        Livewire::test(ManageWorkLogs::class)
            ->callAction('create', data: [
                'client_id' => $client->id,
                'worked_on' => now()->format('Y-m-d'),
                'title' => 'Neue Arbeit',
                'duration_minutes' => 60,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('work_logs', [
            'client_id' => $client->id,
            'title' => 'Neue Arbeit',
            'duration_minutes' => 60,
        ]);
    }

    public function test_worklog_service_gets_unbilled_summary(): void
    {
        $client = Client::factory()->create();
        WorkLog::factory()->count(3)->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
            'is_billed' => false,
        ]);

        $service = app(WorkLogService::class);
        $summary = $service->getUnbilledSummary();

        $this->assertEquals(1, $summary->count());
        $this->assertEquals(3, $summary->first()['entries']->count());
    }

    public function test_worklog_service_calculates_billing_totals(): void
    {
        $client = Client::factory()->create(['default_hourly_rate' => 100.00]);
        $workLogs = WorkLog::factory()->count(2)->create([
            'client_id' => $client->id,
            'duration_minutes' => 60,
            'hourly_rate' => null,
        ]);

        $service = app(WorkLogService::class);
        $totals = $service->calculateBillingTotals($workLogs);

        // 2 entries × 1 hour × 100€ = 200€
        $this->assertEquals(120, $totals['total_minutes']);
        $this->assertEquals('2:00', $totals['total_hours']);
        $this->assertEquals(100.00, $totals['hourly_rate']);
        $this->assertEquals(200.00, $totals['subtotal']);
        $this->assertEquals(19.00, $totals['tax_rate']);
        $this->assertEquals(38.00, $totals['tax_amount']);
        $this->assertEquals(238.00, $totals['total']);
    }

    public function test_worklog_service_creates_invoice(): void
    {
        $client = Client::factory()->create([
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'company' => 'Test GmbH',
            'email' => 'max@test.de',
            'default_hourly_rate' => 100.00,
        ]);

        $workLogs = WorkLog::factory()->count(2)->create([
            'client_id' => $client->id,
            'worked_on' => Carbon::parse('2026-01-15'),
            'duration_minutes' => 60,
            'is_billed' => false,
        ]);

        $service = app(WorkLogService::class);
        $invoice = $service->createInvoice(
            $client,
            Carbon::parse('2026-01-01'),
            $workLogs->pluck('id')->toArray()
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertNotNull($invoice->invoice_number);
        $this->assertEquals(200.00, $invoice->subtotal);
        $this->assertEquals(2, $invoice->items->count());

        // Check work logs are marked as billed
        foreach ($workLogs as $workLog) {
            $workLog->refresh();
            $this->assertTrue($workLog->is_billed);
            $this->assertEquals($invoice->id, $workLog->invoice_id);
        }
    }

    public function test_worklog_service_gets_client_statistics(): void
    {
        $client = Client::factory()->create(['default_hourly_rate' => 100.00]);

        WorkLog::factory()->create([
            'client_id' => $client->id,
            'duration_minutes' => 60,
            'is_billed' => false,
        ]);
        WorkLog::factory()->create([
            'client_id' => $client->id,
            'duration_minutes' => 120,
            'is_billed' => true,
        ]);

        $service = app(WorkLogService::class);
        $stats = $service->getClientStatistics($client);

        $this->assertEquals(1.00, $stats['total_hours_unbilled']);
        $this->assertEquals(100.00, $stats['total_amount_unbilled']);
        $this->assertEquals(2.00, $stats['total_hours_billed']);
        $this->assertEquals(200.00, $stats['total_amount_billed']);
    }

    public function test_billed_worklogs_cannot_be_edited(): void
    {
        $workLog = WorkLog::factory()->create(['is_billed' => true]);

        Livewire::test(ManageWorkLogs::class)
            ->assertTableActionHidden('edit', $workLog);
    }

    public function test_billed_worklogs_cannot_be_deleted(): void
    {
        $workLog = WorkLog::factory()->create(['is_billed' => true]);

        Livewire::test(ManageWorkLogs::class)
            ->assertTableActionHidden('delete', $workLog);
    }

    public function test_unbilled_worklogs_can_be_edited(): void
    {
        $workLog = WorkLog::factory()->create(['is_billed' => false]);

        Livewire::test(ManageWorkLogs::class)
            ->assertTableActionVisible('edit', $workLog);
    }
}
