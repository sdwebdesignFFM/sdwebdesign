<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\RelationManagers\WorkLogsRelationManager;
use App\Filament\Resources\Tasks\TaskResource;
use App\Models\Client;
use App\Models\Setting;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkLog;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskTest extends TestCase
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

    public function test_task_model_can_be_created(): void
    {
        $client = Client::factory()->create();
        $task = Task::factory()->create([
            'client_id' => $client->id,
            'title' => 'Test Aufgabe',
        ]);

        $this->assertDatabaseHas('tasks', [
            'client_id' => $client->id,
            'title' => 'Test Aufgabe',
        ]);
    }

    public function test_task_belongs_to_client(): void
    {
        $client = Client::factory()->create();
        $task = Task::factory()->create(['client_id' => $client->id]);

        $this->assertEquals($client->id, $task->client->id);
    }

    public function test_task_can_have_worklogs(): void
    {
        $task = Task::factory()->create();
        $workLog = WorkLog::factory()->create([
            'client_id' => $task->client_id,
            'task_id' => $task->id,
        ]);

        $this->assertTrue($task->workLogs->contains($workLog));
    }

    public function test_total_logged_minutes_attribute(): void
    {
        $task = Task::factory()->create();
        WorkLog::factory()->create([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'duration_minutes' => 60,
        ]);
        WorkLog::factory()->create([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'duration_minutes' => 30,
        ]);

        $this->assertEquals(90, $task->total_logged_minutes);
    }

    public function test_total_logged_formatted_attribute(): void
    {
        $task = Task::factory()->create();
        WorkLog::factory()->create([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'duration_minutes' => 90,
        ]);

        $this->assertEquals('1:30', $task->total_logged_formatted);
    }

    public function test_estimated_formatted_attribute(): void
    {
        $task = Task::factory()->create(['estimated_minutes' => 120]);

        $this->assertEquals('2:00', $task->estimated_formatted);
    }

    public function test_estimated_formatted_returns_null_when_no_estimate(): void
    {
        $task = Task::factory()->create(['estimated_minutes' => null]);

        $this->assertNull($task->estimated_formatted);
    }

    public function test_remaining_minutes_attribute(): void
    {
        $task = Task::factory()->create(['estimated_minutes' => 120]);
        WorkLog::factory()->create([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'duration_minutes' => 30,
        ]);

        $this->assertEquals(90, $task->remaining_minutes);
    }

    public function test_progress_percentage_attribute(): void
    {
        $task = Task::factory()->create(['estimated_minutes' => 100]);
        WorkLog::factory()->create([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'duration_minutes' => 50,
        ]);

        $this->assertEquals(50, $task->progress_percentage);
    }

    public function test_progress_percentage_caps_at_100(): void
    {
        $task = Task::factory()->create(['estimated_minutes' => 60]);
        WorkLog::factory()->create([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'duration_minutes' => 120,
        ]);

        $this->assertEquals(100, $task->progress_percentage);
    }

    public function test_is_overdue_returns_true_for_past_due_date(): void
    {
        $task = Task::factory()->overdue()->create();

        $this->assertTrue($task->isOverdue());
    }

    public function test_is_overdue_returns_false_for_completed_task(): void
    {
        $task = Task::factory()->completed()->create([
            'due_date' => now()->subDay(),
        ]);

        $this->assertFalse($task->isOverdue());
    }

    public function test_is_due_today_returns_true_for_today(): void
    {
        $task = Task::factory()->dueToday()->create();

        $this->assertTrue($task->isDueToday());
    }

    public function test_mark_as_completed_updates_status(): void
    {
        $task = Task::factory()->create(['status' => TaskStatus::InProgress]);

        $task->markAsCompleted();

        $this->assertEquals(TaskStatus::Completed, $task->status);
        $this->assertNotNull($task->completed_at);
    }

    public function test_mark_as_in_progress_from_pending(): void
    {
        $task = Task::factory()->create(['status' => TaskStatus::Pending]);

        $task->markAsInProgress();

        $this->assertEquals(TaskStatus::InProgress, $task->status);
    }

    public function test_mark_as_in_progress_does_nothing_if_already_in_progress(): void
    {
        $task = Task::factory()->inProgress()->create();

        $task->markAsInProgress();

        $this->assertEquals(TaskStatus::InProgress, $task->status);
    }

    public function test_recurring_task_creates_next_occurrence_on_completion(): void
    {
        $task = Task::factory()->recurring('weekly', 1)->create([
            'due_date' => now(),
        ]);

        $task->markAsCompleted();

        $nextTask = Task::where('id', '!=', $task->id)
            ->where('title', $task->title)
            ->first();

        $this->assertNotNull($nextTask);
        $this->assertEquals(TaskStatus::Pending, $nextTask->status);
        $this->assertTrue($nextTask->due_date->isNextWeek());
    }

    public function test_open_scope_returns_pending_and_in_progress(): void
    {
        Task::factory()->create(['status' => TaskStatus::Pending]);
        Task::factory()->inProgress()->create();
        Task::factory()->completed()->create();

        $this->assertEquals(2, Task::open()->count());
    }

    public function test_overdue_scope(): void
    {
        Task::factory()->overdue()->create();
        Task::factory()->dueToday()->create();
        Task::factory()->create(['due_date' => now()->addWeek()]);

        $this->assertEquals(1, Task::overdue()->count());
    }

    public function test_due_today_scope(): void
    {
        Task::factory()->overdue()->create();
        Task::factory()->dueToday()->create();
        Task::factory()->create(['due_date' => now()->addWeek()]);

        $this->assertEquals(1, Task::dueToday()->count());
    }

    public function test_due_soon_scope(): void
    {
        Task::factory()->create(['due_date' => now()->addDays(3)]);
        Task::factory()->create(['due_date' => now()->addDays(10)]);

        $this->assertEquals(1, Task::dueSoon(7)->count());
    }

    public function test_for_client_scope(): void
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        Task::factory()->create(['client_id' => $client1->id]);
        Task::factory()->create(['client_id' => $client2->id]);

        $this->assertEquals(1, Task::forClient($client1->id)->count());
    }

    public function test_task_priority_enum_has_correct_labels(): void
    {
        $this->assertEquals('Niedrig', TaskPriority::Low->getLabel());
        $this->assertEquals('Normal', TaskPriority::Normal->getLabel());
        $this->assertEquals('Hoch', TaskPriority::High->getLabel());
        $this->assertEquals('Dringend', TaskPriority::Urgent->getLabel());
    }

    public function test_task_status_enum_has_correct_labels(): void
    {
        $this->assertEquals('Offen', TaskStatus::Pending->getLabel());
        $this->assertEquals('In Bearbeitung', TaskStatus::InProgress->getLabel());
        $this->assertEquals('Erledigt', TaskStatus::Completed->getLabel());
        $this->assertEquals('Abgebrochen', TaskStatus::Cancelled->getLabel());
    }

    public function test_task_status_is_open(): void
    {
        $this->assertTrue(TaskStatus::Pending->isOpen());
        $this->assertTrue(TaskStatus::InProgress->isOpen());
        $this->assertFalse(TaskStatus::Completed->isOpen());
        $this->assertFalse(TaskStatus::Cancelled->isOpen());
    }

    public function test_tasks_list_page_loads(): void
    {
        Livewire::test(ListTasks::class)
            ->assertOk();
    }

    public function test_task_can_be_created_via_filament(): void
    {
        $client = Client::factory()->create();

        Livewire::test(CreateTask::class)
            ->fillForm([
                'client_id' => $client->id,
                'title' => 'Neue Aufgabe',
                'priority' => TaskPriority::Normal,
                'status' => TaskStatus::Pending,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tasks', [
            'client_id' => $client->id,
            'title' => 'Neue Aufgabe',
        ]);
    }

    public function test_task_can_be_edited_via_filament(): void
    {
        $task = Task::factory()->create(['title' => 'Alte Aufgabe']);

        Livewire::test(EditTask::class, ['record' => $task->id])
            ->fillForm([
                'title' => 'Neue Aufgabe',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Neue Aufgabe',
        ]);
    }

    public function test_task_navigation_badge_shows_open_count(): void
    {
        Task::factory()->count(3)->create(['status' => TaskStatus::Pending]);
        Task::factory()->completed()->create();

        $this->assertEquals('3', TaskResource::getNavigationBadge());
    }

    public function test_worklog_can_be_linked_to_task(): void
    {
        $task = Task::factory()->create();
        $workLog = WorkLog::factory()->create([
            'client_id' => $task->client_id,
            'task_id' => $task->id,
        ]);

        $this->assertEquals($task->id, $workLog->task->id);
    }

    public function test_worklogs_relation_manager_loads_on_edit_page(): void
    {
        $task = Task::factory()->create();

        Livewire::test(EditTask::class, ['record' => $task->id])
            ->assertSeeLivewire(WorkLogsRelationManager::class);
    }

    public function test_worklog_can_be_created_via_relation_manager(): void
    {
        $task = Task::factory()->create();

        Livewire::test(
            WorkLogsRelationManager::class,
            ['ownerRecord' => $task, 'pageClass' => EditTask::class]
        )
            ->callAction(
                TestAction::make(CreateAction::class)->table(),
                data: [
                    'worked_on' => now()->format('Y-m-d'),
                    'duration_minutes' => 60,
                    'title' => 'Teilaufgabe erledigt',
                ]
            )
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('work_logs', [
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'title' => 'Teilaufgabe erledigt',
            'duration_minutes' => 60,
        ]);
    }
}
