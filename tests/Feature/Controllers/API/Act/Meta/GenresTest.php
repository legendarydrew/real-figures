<?php

namespace Tests\Feature\Controllers\API\Act\Meta;

use App\Models\Act;
use App\Models\ActMetaGenre;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenresTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/acts/%u';

    private Act   $act;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act     = Act::factory()->createOne();
        $this->payload = [
            'name' => fake()->name,
            'meta' => [
                'genres' => ['Pop', 'Rock']
            ]
        ];

        // Create some genres.
        Genre::factory()->createMany([
            ['name' => 'Pop'],
            ['name' => 'Rock'],
            ['name' => 'Jazz']
        ]);
    }

    public function test_adds_meta_genres()
    {
        $this->payload['meta']['genres'] = ['Pop', 'Rock'];
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertRedirect();

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['genres']), $this->act->genres);

        $genres = $this->act->genres->pluck('name')->toArray();
        self::assertEquals(['Pop', 'Rock'], $genres);
    }

    public function test_creates_new_genres()
    {
        $this->payload['meta']['genres'] = ['latin', 'blues', 'hip hop'];
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertRedirect();

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['genres']), $this->act->genres);

        $genres = $this->act->genres->pluck('name')->toArray();
        self::assertEquals(['Latin', 'Blues', 'Hip Hop'], $genres);
    }

    public function test_replace_meta_genres()
    {
        $genre_ids = fake()->randomElements(Genre::pluck('id')->toArray(), 2);
        foreach ($genre_ids as $genre_id)
        {
            ActMetaGenre::create([
                'act_id'   => $this->act->id,
                'genre_id' => $genre_id
            ]);
        }

        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertRedirect();

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['genres']), $this->act->genres);

        $saved_genres = $this->act->genres->pluck('name')->toArray();
        foreach ($this->payload['meta']['genres'] as $genre)
        {
            self::assertContains($genre, $saved_genres);
        }
    }

    public function test_preserve_meta_languages()
    {
        $genre_ids = fake()->randomElements(Genre::pluck('id')->toArray(), 2);
        foreach ($genre_ids as $genre_id)
        {
            ActMetaGenre::create([
                'act_id'   => $this->act->id,
                'genre_id' => $genre_id
            ]);
        }

        $this->payload['meta']['genres'][] = 'Jazz';
        $this->payload['meta']['genres'][] = 'New Age';
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertRedirect();

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['genres']), $this->act->genres);

        $saved_genres = $this->act->genres->pluck('name')->toArray();
        foreach ($this->payload['meta']['genres'] as $genre)
        {
            self::assertContains($genre, $saved_genres);
        }
    }

}
