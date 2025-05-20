<?php

namespace Database\Factories;

use App\Jobs\EndOfRound;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
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
        $this->faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($this->faker));

        return [
            'title' => $this->faker->unique()->sentence(),
            'description' => $this->faker->markdown(),
        ];
    }

    public function withRounds(): StageFactory
    {
        return $this->afterCreating(function (Stage $stage)
        {
            Round::factory($this->faker->numberBetween(1, 3))->for($stage)->create();
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
