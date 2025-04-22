<?php

namespace Database\Factories;

use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\Song;
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

    public function started(): RoundFactory|Factory
    {
        return $this->state(fn(array $attributes) => ['starts_at' => now(),
                                                      'ends_at'   => fake()->dateTimeBetween('1 day', '1 week')]
        );
    }

    public function ended(): RoundFactory|Factory
    {
        return $this->state(fn(array $attributes) => ['starts_at' => fake()->dateTimeBetween('-1 week'),
                                                      'ends_at'   => now()->subSecond()]
        );
    }

    public function withSongs(int $count = null): RoundFactory|Factory
    {
        $count = $count ?? fake()->numberBetween(config('contest.rounds.minSongs'), config('contest.round.maxSongs'));

        return $this->afterCreating(function (Round $round) use ($count)
        {
            $songs = Song::inRandomOrder()->take($count)->get();
            if ($songs->count() < $count)
            {
                $songs = $songs->merge(Song::factory($count - $songs->count())->withAct()->create());
            }
            foreach ($songs as $song)
            {
                RoundSongs::create([
                    'round_id' => $round->id,
                    'song_id'  => $song->id,
                ]);
            }
        });
    }
}
