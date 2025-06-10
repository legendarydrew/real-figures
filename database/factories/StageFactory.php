<?php

namespace Database\Factories;

use App\Jobs\EndOfRound;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use App\Models\StageWinner;
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
            'title'               => $this->faker->unique()->words(3, true),
            'description'         => $this->faker->paragraph,
            'golden_buzzer_perks' => $this->faker->boolean(20) ? null : $this->faker->paragraph,
        ];
    }

    public function withRounds(int $started_count = 2, int $ended_count = 0): StageFactory
    {
        return $this->afterCreating(function (Stage $stage) use ($started_count, $ended_count)
        {
            if ($started_count + $ended_count > 0)
            {
                Round::factory($ended_count)->ended()->withSongs()->for($stage)->create();
                Round::factory($started_count)->started()->withSongs()->for($stage)->create();
            }
            else
            {
                $round_count = $this->faker->numberBetween(1, 4);
                Round::factory($round_count)->withSongs()->for($stage)->create();
            }
        });
    }

    public function withResults(): StageFactory
    {
        return $this->afterCreating(function (Stage $stage)
        {
            $this->ensureRoundsForStage($stage);
            $this->ensureSongsForStage($stage);

            foreach ($stage->rounds as $round)
            {
                // Randomise the chance of a Stage having votes vs. resorting to a "manual vote".
                if ($this->faker->boolean(80))
                {
                    $song_ids = $round->songs->pluck('id')->toArray();
                    for ($i = 0; $i < 100; $i++)
                    {
                        $votes = $this->faker->randomElements($song_ids, 3);
                        // Spotted an issue here: because all three choices are required,
                        // a Round must have at least three Songs.
                        RoundVote::create([
                            'round_id'         => $round->id,
                            'first_choice_id'  => $votes[0],
                            'second_choice_id' => $votes[1],
                            'third_choice_id'  => $votes[2],
                        ]);
                    }
                    EndOfRound::dispatchSync($round);
                }
            }
        });

    }

    /**
     * Create a Stage that is considered over.
     * A Stage is over when all Rounds have ended and winners have been chosen.
     *
     * @return StageFactory
     */
    public function over(): StageFactory
    {
        return $this->afterCreating(function (Stage $stage)
        {
            // Create ended Rounds.
            $round_count = $this->faker->numberBetween(1, 4);
            $rounds      = Round::factory($round_count)->ended()->withSongs(16)->for($stage)->create();

            // Create Stage winners.
            foreach ($rounds as $round)
            {
                $song_id = $round->songs->random()->pluck('id')->first();
                StageWinner::create([
                    'stage_id'  => $stage->id,
                    'round_id'  => $round->id,
                    'song_id'   => $song_id,
                    'is_winner' => true,
                ]);
            }
        });
    }

    protected function ensureRoundsForStage(Stage $stage): void
    {
        if ($stage->rounds()->count() === 0)
        {
            Round::factory($this->faker->numberBetween(1, 4))->withSongs()->ended()->for($stage)->create();
        }
        else
        {
            foreach ($stage->rounds as $round)
            {
                if (!$round->hasEnded())
                {
                    $round->update([
                        'starts_at' => now()->subDay(),
                        'ends_at'   => now(),
                    ]);
                }
            }
        }
    }

    protected function ensureSongsForStage(Stage $stage): void
    {
        foreach ($stage->rounds as $round)
        {
            if ($round->songs()->count() < config('contest.rounds.minSongs'))
            {
                $songs = Song::factory(config('contest.rounds.minSongs') + 1)->withAct()->create();
                foreach ($songs as $song)
                {
                    RoundSongs::create([
                        'round_id' => $round->id,
                        'song_id'  => $song->id,
                    ]);
                }
            }
        }
    }
}
