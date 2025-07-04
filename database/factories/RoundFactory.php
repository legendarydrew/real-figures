<?php

namespace Database\Factories;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;

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
        $start_date = Carbon::parse($this->faker->dateTimeThisMonth());
        $end_date   = $start_date->clone()->addWeek();

        return [
            'title'     => $this->faker->sentence(2),
            'starts_at' => $start_date,
            'ends_at'   => $end_date,
        ];
    }

    public function ready(): RoundFactory|Factory
    {
        return $this->state(fn(array $attributes) => ['starts_at' => now()->addDay(),
                                                      'ends_at'   => now()->addWeek()]
        );
    }

    public function future(): RoundFactory|Factory
    {
        $start_at = now()->addDays(fake()->numberBetween(1, 6));
        return $this->state(fn(array $attributes) => [
            'starts_at' => $start_at,
            'ends_at'   => $start_at->clone()->addDays(fake()->numberBetween(1, 6))
        ]);
    }

    public function started(): RoundFactory|Factory
    {
        return $this->state(fn(array $attributes) => [
            'starts_at' => now(),
            'ends_at'   => fake()->dateTimeBetween('1 day', '1 week')
        ]);
    }

    public function ended(): RoundFactory|Factory
    {
        return $this->state(fn(array $attributes) => [
            'starts_at' => fake()->dateTimeBetween('-1 week'),
            'ends_at'   => now()->subSecond()
        ]);
    }

    public function withSongs(int $count = null): RoundFactory|Factory
    {
        if (!$count)
        {
            $count = fake()->numberBetween(config('contest.rounds.minSongs'), config('contest.round.maxSongs'));
        }
        $count = max(config('contest.rounds.minSongs'), $count);
        $count = min(config('contest.rounds.maxSongs'), $count);

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

    public function withVotes(): RoundFactory|Factory
    {
        return $this->afterCreating(function (Round $round)
        {
            $song_ids   = $round->songs()->pluck('songs.id')->toArray();
            $vote_count = fake()->numberBetween(1, 300);
            foreach (range(1, $vote_count) as $_)
            {
                $voted_for_songs = fake()->randomElements($song_ids, 3);
                RoundVote::create([
                    'round_id'         => $round->id,
                    'first_choice_id'  => $voted_for_songs[0],
                    'second_choice_id' => $voted_for_songs[1],
                    'third_choice_id'  => $voted_for_songs[2]
                ]);
            }

        });
    }

    public function withOutcomes(): RoundFactory|Factory
    {
        return $this->afterCreating(function (Round $round) {
           RoundOutcome::factory($round->songs()->count())->for($round)->create([
               'song_id' => new Sequence(...$round->songs->pluck('id'))
           ]);
        });
    }
}
