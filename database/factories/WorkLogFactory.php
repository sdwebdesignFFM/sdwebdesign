<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\WorkLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkLog>
 */
class WorkLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durationOptions = [15, 30, 45, 60, 90, 120, 180, 240, 300, 360];

        return [
            'client_id' => Client::factory(),
            'worked_on' => fake()->dateTimeBetween('-3 months', 'now'),
            'title' => fake()->randomElement([
                'Bugfix implementiert',
                'Feature entwickelt',
                'Code Review durchgeführt',
                'Meeting mit Kunde',
                'Dokumentation aktualisiert',
                'Deployment durchgeführt',
                'Support-Anfrage bearbeitet',
                'Konzept erstellt',
            ]),
            'description' => fake()->optional(0.7)->sentence(),
            'duration_minutes' => fake()->randomElement($durationOptions),
            'hourly_rate' => null,
            'is_billed' => false,
        ];
    }

    /**
     * Mark as billed.
     */
    public function billed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_billed' => true,
        ]);
    }

    /**
     * Set a specific hourly rate.
     */
    public function withHourlyRate(float $rate): static
    {
        return $this->state(fn (array $attributes) => [
            'hourly_rate' => $rate,
        ]);
    }

    /**
     * Create for a specific month.
     */
    public function forMonth(int $year, int $month): static
    {
        return $this->state(fn (array $attributes) => [
            'worked_on' => fake()->dateTimeBetween(
                "{$year}-{$month}-01",
                date('Y-m-t', strtotime("{$year}-{$month}-01"))
            ),
        ]);
    }
}
