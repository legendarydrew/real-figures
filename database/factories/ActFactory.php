<?php

namespace Database\Factories;

use App\Models\Act;
use App\Models\ActMetaGenre;
use App\Models\ActMetaLanguage;
use App\Models\ActMetaMember;
use App\Models\ActMetaNote;
use App\Models\ActMetaTrait;
use App\Models\ActPicture;
use App\Models\ActProfile;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;

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
        return [
            'name' => $this->faker->unique()->name,
            'is_fan_favourite' => $this->faker->boolean(10)
        ];
    }

    public function fanFavourite(): ActFactory
    {
        return $this->state(fn(array $attributes) => [
            'is_fan_favourite' => true
        ]);
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

    public function withPicture(int $chance = 100): ActFactory
    {
        return $this->afterCreating(function (Act $act) use ($chance)
        {
            if ($this->faker->boolean($chance))
            {
                ActPicture::factory()->for($act)->createOne();
            }
        });
    }

    public function withMeta(): ActFactory
    {
        return $this->afterCreating(function (Act $act)
        {
            // Add languages based on any existing Songs.
            $song_languages = $act->songs()->pluck('language_id')->unique()->toArray();
            ActMetaLanguage::factory(count($song_languages))->for($act)->create([
                'language_id' => new Sequence(...$song_languages)
            ]);

            // Add notes.
            if ($this->faker->boolean())
            {
                $note_count = $this->faker->numberBetween(1, 4);
                ActMetaNote::factory($note_count)->for($act)->create();
            }

            // Add genre.
            if ($this->faker->boolean())
            {
                ActMetaGenre::factory()->for($act)->createOne();
            }

            // Add member(s).
            if ($this->faker->boolean())
            {
                $member_count = $this->faker->biasedNumberBetween(1, 3);
                ActMetaMember::factory($member_count)->for($act)->create();
            }

            // Add traits (personality, etc.)
            if ($this->faker->boolean())
            {
                $trait_count = $this->faker->biasedNumberBetween(1, 3);
                ActMetaTrait::factory($trait_count)->for($act)->create();
            }
        });

    }
}
