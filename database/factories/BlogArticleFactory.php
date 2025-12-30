<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogArticle>
 */
class BlogArticleFactory extends Factory
{
    private const CATEGORIES = [
        'Digitale Systeme',
        'Prozessautomatisierung',
        'API-Integration',
        'E-Commerce',
        'WordPress',
        'Technologie',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(self::CATEGORIES),
            'excerpt' => fake()->paragraph(2),
            'intro' => fake()->paragraphs(2, true),
            'sections' => [
                [
                    'heading' => fake()->sentence(4),
                    'content' => fake()->paragraphs(3, true),
                ],
                [
                    'heading' => fake()->sentence(4),
                    'content' => fake()->paragraphs(3, true),
                ],
            ],
            'conclusion' => fake()->paragraphs(2, true),
            'read_time' => fake()->numberBetween(5, 15),
            'meta_title' => $title,
            'meta_description' => fake()->paragraph(1),
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
