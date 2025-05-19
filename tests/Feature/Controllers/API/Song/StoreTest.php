<?php

namespace Tests\Feature\Controllers\API\Song;

use App\Models\Act;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/songs';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'title'  => fake()->sentence(),
            'act_id' => Act::factory()->createOne()->id,
            'language' => fake()->languageCode
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirect(route('admin.songs'));
    }

    #[Depends('test_as_user')]
    public function test_creates_song()
    {
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $song = Song::whereTitle($this->payload['title'])->first();
        self::assertInstanceOf(Song::class, $song);
        self::assertEquals($this->payload['act_id'], $song->act_id);
        self::assertEquals($this->payload['language'], $song->language);
    }

    #[Depends('test_creates_song')]
    public function test_creates_song_with_url()
    {
        $this->payload['url'] = fake()->url();

        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $song = Song::whereTitle($this->payload['title'])->first();

        self::assertInstanceOf(SongUrl::class, $song->url);
        self::assertEquals($this->payload['url'], $song->url->url);
    }


    #[Depends('test_creates_song')]
    public function test_creates_song_without_url()
    {
        $this->payload['url'] = null;

        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $song = Song::whereTitle($this->payload['title'])->first();

        self::assertNull($song->url);
    }

}
