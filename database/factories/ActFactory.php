<?php

namespace Database\Factories;

use App\Models\Act;
use App\Models\ActProfile;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @extends Factory<Model>
 */
class ActFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        return [
            'name' => $name,
            'slug' => Str::slug($name)
        ];
    }

    public function withProfile(): Factory|ActFactory
    {
        return $this->afterCreating(fn(Act $act) => ActProfile::factory()->createOne([
            'act_id' => $act->id,
        ])
        );
    }

    public function withSong($song_title = null): Factory|ActFactory
    {
        return $this->afterCreating(fn(Act $act) => Song::factory()->createOne([
            'act_id' => $act->id,
            'title'  => $song_title ?? config('contest.song.default-title')
        ])
        );
    }
}
