<?php

namespace Database\Factories;

use App\Models\Act;
use App\Models\Song;
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
        return [
            'title'    => config('contest.song.default-title'),
            'language' => fake()->boolean(80) ? 'en' : fake()->languageCode()
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
}
