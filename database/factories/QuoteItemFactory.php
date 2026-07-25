<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteItem>
 */
class QuoteItemFactory extends Factory
{
    protected $model = QuoteItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 50, 500);

        return [
            'quote_id' => Quote::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'quantity' => $quantity,
            'unit' => fake()->randomElement(['Stück', 'Stunden', 'Pauschal']),
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
            'is_optional' => false,
            'is_selected' => true,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Set the item as optional.
     */
    public function optional(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_optional' => true,
            'is_selected' => false,
        ]);
    }

    /**
     * Set the item as selected (for optional items).
     */
    public function selected(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_selected' => true,
        ]);
    }

    /**
     * Set the item with an option group.
     */
    public function optionGroup(string $group): static
    {
        return $this->state(fn (array $attributes) => [
            'is_optional' => true,
            'option_group' => $group,
        ]);
    }
}
