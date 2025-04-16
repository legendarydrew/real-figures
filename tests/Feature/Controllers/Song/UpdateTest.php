<?php

namespace Tests\Feature\Controllers\Song;

use App\Models\Act;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/songs/%u';

    private Song  $song;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->song    = Song::factory()->withAct()->withUrl()->createOne();
        $this->payload = [
            'title'    => fake()->sentence(),
            'act_id'   => Act::factory()->createOne()->id,
            'language' => fake()->languageCode,
        ];
    }

    public function test_as_guest()
    {
        $response = $this->patchJson(sprintf(self::ENDPOINT, $this->song->id), $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->song->id), $this->payload);
        $response->assertRedirect(route('admin.songs'));
    }

    #[Depends('test_as_user')]
    public function test_updates_song()
    {
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->song->id), $this->payload);

        $this->song->refresh();
        self::assertEquals($this->payload['title'], $this->song->title);
        self::assertEquals($this->payload['act_id'], $this->song->act_id);
        self::assertEquals($this->payload['language'], $this->song->language);
    }

    #[Depends('test_updates_song')]
    public function test_updates_song_with_url()
    {
        $this->payload['url'] = fake()->url;
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->song->id), $this->payload);

        $this->song->refresh();
        self::assertInstanceOf(SongUrl::class, $this->song->url);
        self::assertEquals($this->payload['url'], $this->song->url->url);
    }

    #[Depends('test_updates_song')]
    public function test_updates_song_without_url()
    {
        $this->payload['url'] = null;
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->song->id), $this->payload);

        $this->song->refresh();
        self::assertNull($this->song->url);
    }

    #[Depends('test_as_user')]
    public function test_invalid_act()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

}
