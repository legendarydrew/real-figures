<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Round>
 */
class RoundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = $this->faker->dateTimeThisMonth();
        $end_date = Carbon::parse($start_date)->addWeek();
        return [
            'title'     => $this->faker->sentence(2),
            'starts_at' => $start_date,
            'ends_at'   => $end_date,
        ];
    }
}
