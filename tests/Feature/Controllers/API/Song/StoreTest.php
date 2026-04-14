<?php

namespace Tests\Feature\Controllers\API\Song;

use App\Models\Act;
use App\Models\Language;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class StoreTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/songs';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $language = Language::inRandomOrder()->first();
        $this->payload = [
            'title' => fake()->sentence(),
            'act_id' => Act::factory()->createOne()->id,
            'language' => $language->code,
        ];
    }

    public function test_as_guest(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirect(route('admin.songs'));
    }

    #[Depends('test_as_user')]
    public function test_creates_song(): void
    {
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $song = Song::whereTitle($this->payload['title'])->first();
        self::assertInstanceOf(Song::class, $song);
        self::assertEquals($this->payload['act_id'], $song->act_id);
        self::assertEquals($this->payload['language'], $song->language->code);
    }

    #[Depends('test_creates_song')]
    public function test_creates_song_with_urls(): void
    {
        $this->payload['urls'] = [
            ['url' => fake()->url],
            ['url' => fake()->url],
        ];

        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $song = Song::whereTitle($this->payload['title'])->with(['urls'])->first();

        self::assertCount(2, $song->urls);
        self::assertInstanceOf(SongUrl::class, $song->latestVersion());
    }

    #[Depends('test_creates_song')]
    public function test_creates_song_without_url(): void
    {
        $this->payload['urls'] = null;

        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $song = Song::whereTitle($this->payload['title'])->with(['urls'])->first();

        self::assertEmpty($song->urls);
        self::assertNull($song->latestVersion());
    }
}
