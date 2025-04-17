<?php

namespace Database\Factories;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stage>
 */
class StageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(),
            'description' => $this->faker->paragraph(),
        ];
    }

    public function withRounds(): StageFactory
    {
        return $this->afterCreating(function (Stage $stage)
        {
            Round::factory($this->faker->numberBetween(1, 3))->for($stage)->create();
        });
    }
}
