<?php

namespace Database\Factories;

use App\Models\GoldenBuzzer;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GoldenBuzzer>
 */
class GoldenBuzzerFactory extends Factory
{

    /**
     * The current password being used by the factory.
     */
    protected static ?int $song_id;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $song_id = static::$song_id ??= Song::inRandomOrder()->first()->id;

        return [
            'name'         => $this->faker->name(),
            'song_id'      => $song_id,
            'transaction_id' => $this->faker->unique()->uuid(),
            'amount'       => $this->faker->randomFloat(2, 1, 999),
            'currency'     => $this->faker->boolean(80) ? 'USD' : $this->faker->currencyCode(),
            'message'      => $this->faker->boolean(20) ? $this->faker->realText() : null,
            'is_anonymous' => $this->faker->boolean(20),
        ];
    }
}
