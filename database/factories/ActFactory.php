<?php

namespace Database\Factories;

use App\Facades\ActImageFacade;
use App\Models\Act;
use App\Models\ActMetaGenre;
use App\Models\ActMetaLanguage;
use App\Models\ActMetaMember;
use App\Models\ActMetaNote;
use App\Models\ActMetaTrait;
use App\Models\ActProfile;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

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
        $name = fake()->unique()->name();
        return [
            'name'             => $name,
            'slug'             => Str::slug($name),
            'is_fan_favourite' => fake()->boolean(10)
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
            if (fake()->boolean($chance))
            {
                fake()->addProvider(new FakerPicsumImagesProvider(fake()));
                ActImageFacade::create($act, fake()->image);
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
            if (fake()->boolean())
            {
                $note_count = fake()->numberBetween(1, 4);
                ActMetaNote::factory($note_count)->for($act)->create();
            }

            // Add genre.
            if (fake()->boolean())
            {
                ActMetaGenre::factory()->for($act)->createOne();
            }

            // Add member(s).
            if (fake()->boolean())
            {
                $member_count = fake()->biasedNumberBetween(1, 3);
                ActMetaMember::factory($member_count)->for($act)->create();
            }

            // Add traits (personality, etc.)
            if (fake()->boolean())
            {
                $trait_count = fake()->biasedNumberBetween(1, 3);
                ActMetaTrait::factory($trait_count)->for($act)->create();
            }
        });

    }
}
