<?php

namespace Database\Factories;

use App\Models\GoldenBuzzer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GoldenBuzzer>
 */
class GoldenBuzzerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount'         => $this->faker->randomFloat(2, 10, 100),
            'transaction_id' => $this->faker->unique()->uuid(),
        ];
    }
}
