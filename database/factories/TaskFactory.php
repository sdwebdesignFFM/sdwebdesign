<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Client;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durationOptions = [30, 60, 90, 120, 180, 240, 300, 360, 480];

        return [
            'client_id' => Client::factory(),
            'title' => fake()->randomElement([
                'Website-Update durchführen',
                'Neues Feature implementieren',
                'Bug beheben',
                'Content aktualisieren',
                'Design-Anpassungen',
                'Performance optimieren',
                'Backup erstellen',
                'Sicherheitsupdate',
                'SEO-Optimierung',
                'Newsletter erstellen',
            ]),
            'description' => fake()->optional(0.7)->paragraphs(2, true),
            'estimated_minutes' => fake()->optional(0.8)->randomElement($durationOptions),
            'due_date' => fake()->optional(0.8)->dateTimeBetween('now', '+2 weeks'),
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'status' => TaskStatus::Pending,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'next_reminder_at' => null,
            'completed_at' => null,
        ];
    }

    /**
     * Set status to in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::InProgress,
        ]);
    }

    /**
     * Set status to completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    /**
     * Set as overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => fake()->dateTimeBetween('-2 weeks', '-1 day'),
            'status' => TaskStatus::Pending,
        ]);
    }

    /**
     * Set as due today.
     */
    public function dueToday(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => now()->toDateString(),
            'status' => TaskStatus::Pending,
        ]);
    }

    /**
     * Set priority to urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => TaskPriority::Urgent,
        ]);
    }

    /**
     * Set priority to high.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => TaskPriority::High,
        ]);
    }

    /**
     * Set as recurring with weekly rule.
     */
    public function recurring(string $interval = 'weekly', int $every = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'is_recurring' => true,
            'recurrence_rule' => [
                'interval' => $interval,
                'every' => $every,
            ],
        ]);
    }

    /**
     * Create without client.
     */
    public function withoutClient(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => null,
        ]);
    }
}
