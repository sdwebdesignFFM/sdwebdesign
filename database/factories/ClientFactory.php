<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salutation' => fake()->randomElement(['Herr', 'Frau', null]),
            'title' => fake()->optional(0.1)->randomElement(['Dr.', 'Prof.', 'Prof. Dr.']),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company' => fake()->optional(0.7)->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->numerify('+49 69 #### ####'),
            'street' => fake()->optional()->streetAddress(),
            'zip' => fake()->optional()->postcode(),
            'city' => fake()->optional()->city(),
            'country' => 'Deutschland',
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
