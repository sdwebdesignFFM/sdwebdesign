<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Console\Command;

class ProcessRecurringTasks extends Command
{
    protected $signature = 'tasks:process-recurring';

    protected $description = 'Process recurring tasks and create new occurrences if needed';

    public function handle(): int
    {
        $this->info('Processing recurring tasks...');

        $recurringTasks = Task::query()
            ->recurring()
            ->completed()
            ->get();

        $created = 0;

        foreach ($recurringTasks as $task) {
            // Check if there's already an open task with the same title and client
            $existingOpenTask = Task::query()
                ->where('title', $task->title)
                ->where('client_id', $task->client_id)
                ->where('is_recurring', true)
                ->whereIn('status', [TaskStatus::Pending, TaskStatus::InProgress])
                ->exists();

            if (! $existingOpenTask) {
                $newTask = $task->createNextOccurrence();
                if ($newTask) {
                    $created++;
                    $this->line("Created: {$newTask->title} (fällig: ".($newTask->due_date?->format('d.m.Y') ?? 'ohne Datum').')');
                }
            }
        }

        if ($created > 0) {
            $this->info("Created {$created} new recurring task(s).");
        } else {
            $this->info('No new tasks needed to be created.');
        }

        return self::SUCCESS;
    }
}
