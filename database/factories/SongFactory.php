<?php

namespace Database\Factories;

use App\Models\Act;
use App\Models\Language;
use App\Models\Song;
use App\Models\SongPlay;
use App\Models\SongUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (fake()->boolean(80))
        {
            $language = Language::whereCode('en')->first();
        }
        else
        {
            $language = Language::inRandomOrder()->first();
        }

        return [
            'title'       => config('contest.song.default-title'),
            'language_id' => $language->id
        ];
    }

    public function withAct(): SongFactory
    {
        return $this->state(function ()
        {
            return ['act_id' => Act::factory()];
        });
    }

    public function withGoldenBuzzer(): SongFactory
    {
        return $this->afterCreating(function (Song $song)
        {
            $song->setGoldenBuzzerStatus(true);
        });
    }

    public function withUrl(int $chance = 100): SongFactory
    {
        return $this->afterCreating(function (Song $song) use ($chance)
        {
            if ($this->faker->boolean($chance))
            {
                SongUrl::factory()->for($song)->create();
            }
        });
    }

    public function withPlays(): SongFactory
    {
        return $this->afterCreating(function (Song $song)
        {
            $date = now()->subDays($this->faker->numberBetween(1, 10));
            while ($date <= now())
            {
                SongPlay::create([
                    'song_id'    => $song->id,
                    'played_on'  => $date->format('Y-m-d'),
                    'play_count' => $this->faker->numberBetween(1, 100),
                ]);
                $date->addDay();
            }
        });

    }
}
