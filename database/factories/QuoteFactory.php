<?php

namespace Database\Factories;

use App\Enums\BillingCycle;
use App\Enums\QuoteStatus;
use App\Enums\ServiceType;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote_number' => 'A-'.date('y').'-'.str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'created_by' => User::factory(),
            'type' => ServiceType::OneTime,
            'client_name' => fake()->name(),
            'client_company' => fake()->company(),
            'client_email' => fake()->safeEmail(),
            'client_phone' => fake()->phoneNumber(),
            'client_address' => fake()->address(),
            'title' => fake()->sentence(4),
            'subject' => fake()->optional()->sentence(6),
            'intro_text' => fake()->paragraph(),
            'terms_text' => fake()->paragraphs(2, true),
            'footer_text' => fake()->optional()->sentence(),
            'subtotal' => 1000.00,
            'tax_rate' => 19.00,
            'tax_amount' => 190.00,
            'total' => 1190.00,
            'status' => QuoteStatus::Draft,
            'valid_until' => now()->addDays(14),
            'token' => Str::random(64),
        ];
    }

    /**
     * Mark the quote as sent.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuoteStatus::Sent,
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark the quote as viewed.
     */
    public function viewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuoteStatus::Viewed,
            'sent_at' => now()->subDay(),
            'first_viewed_at' => now(),
        ]);
    }

    /**
     * Mark the quote as accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuoteStatus::Accepted,
            'sent_at' => now()->subDays(2),
            'first_viewed_at' => now()->subDay(),
            'accepted_at' => now(),
            'accepted_name' => fake()->name(),
            'accepted_ip' => fake()->ipv4(),
        ]);
    }

    /**
     * Mark the quote as expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuoteStatus::Expired,
            'valid_until' => now()->subDay(),
        ]);
    }

    /**
     * Set the quote as recurring.
     */
    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ServiceType::Recurring,
            'billing_cycle' => BillingCycle::Monthly,
            'min_term_months' => 12,
            'auto_renewal' => true,
            'notice_period_days' => 30,
        ]);
    }
}
