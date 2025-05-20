<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoundOutcome>
 */
class RoundOutcomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_votes'  => $this->faker->numberBetween(0, 10),
            'second_votes' => $this->faker->numberBetween(0, 10),
            'third_votes'  => $this->faker->numberBetween(0, 10),
            'was_manual'   => false,
        ];
    }

    public function manualVote(): RoundOutcomeFactory
    {
        return $this->state(fn() => ['was_manual' => true]);
    }
}
