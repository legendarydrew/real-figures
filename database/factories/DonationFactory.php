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
            'name'           => $this->faker->name(),
            'transaction_id' => $this->faker->unique()->uuid(),
            'amount'         => $this->faker->randomFloat(2, 1, 999),
            'currency'       => $this->faker->boolean(80) ? 'USD' : $this->faker->currencyCode(),
            'message'        => $this->faker->boolean(20) ? $this->faker->realText() : null,
        ];
    }
}
