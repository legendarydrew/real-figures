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
        $name = $this->faker->unique()->name;
        return [
            'name' => $name,
            'slug' => Str::slug($name)
        ];
    }

    public function withProfile(): ActFactory
    {
        return $this->afterCreating(fn(Act $act) => ActProfile::factory()->for($act)->createOne());
    }

    public function withSong(string $song_title = null): ActFactory
    {
        return $this->afterCreating(fn(Act $act) => Song::factory()->for($act)->createOne([
            'title' => $song_title ?? config('contest.song.default-title')
        ])
        );
    }
}
