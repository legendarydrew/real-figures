<?php

namespace Database\Factories;

use App\Models\Round;
use App\Models\RoundSongs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoundVote>
 */
class RoundVoteFactory extends Factory
{
    protected static ?int $round_id = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (static::$round_id)
        {
            $song_ids = RoundSongs::whereRoundId(static::$round_id)->pluck('song_id')->toArray();
        }
        else
        {
            $round          = Round::whereHas('songs')->inRandomOrder()->first();
            self::$round_id = $round->id;
            $song_ids       = $round->songs()->pluck('songs.id')->toArray();
        }
        $choices = $this->faker->randomElements($song_ids, 3);

        return [
            'round_id'         => self::$round_id,
            'first_choice_id'  => $choices[0],
            'second_choice_id' => $choices[1],
            'third_choice_id'  => $choices[2]
        ];
    }
}
