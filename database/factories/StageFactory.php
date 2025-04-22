<?php

namespace Database\Factories;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
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

    public function withResults(): StageFactory
    {
        return $this->afterCreating(function (Stage $stage)
        {
            $this->ensureRoundsForStage($stage);

            foreach ($stage->rounds as $round)
            {
                if ($round->songs()->count() === 0)
                {
                    $songs = Song::factory(4)->withAct()->create();
                    foreach ($songs as $song)
                    {
                        RoundSongs::create([
                            'round_id' => $round->id,
                            'song_id'  => $song->id,
                        ]);
                    }
                }
                foreach ($round->songs as $song)
                {
                    RoundOutcome::factory()->create([
                        'round_id'     => $round->id,
                        'song_id'      => $song->id,
                        'first_votes'  => $this->faker->numberBetween(0, 20),
                        'second_votes' => $this->faker->numberBetween(0, 20),
                        'third_votes'  => $this->faker->numberBetween(0, 20),
                    ]);
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
}
