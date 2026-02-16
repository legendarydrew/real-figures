<?php

namespace Database\Factories;

use App\Models\GoldenBuzzer;
use App\Models\RoundSongs;
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
        $round_song = RoundSongs::inRandomOrder()->first();

        return [
            'name'           => fake()->name(),
            'round_id'       => $round_song->round_id,
            'song_id'        => $round_song->song_id,
            'transaction_id' => fake()->unique()->uuid(),
            'amount'         => fake()->randomFloat(2, 1, 999),
            'currency'       => fake()->boolean(80) ? config('contest.donation.currency') : fake()->currencyCode(),
            'message'        => fake()->boolean(20) ? fake()->realText() : null,
            'is_anonymous'   => fake()->boolean(20),
        ];
    }
}
