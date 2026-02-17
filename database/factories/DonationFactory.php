<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => fake()->name(),
            'transaction_id' => fake()->unique()->uuid(),
            'amount'         => fake()->randomFloat(2, 1, 999),
            'currency'       => fake()->boolean(80) ? 'USD' : fake()->currencyCode(),
            'message'        => fake()->boolean(20) ? fake()->realText() : null,
            'is_anonymous'   => fake()->boolean(20),
        ];
    }
}
