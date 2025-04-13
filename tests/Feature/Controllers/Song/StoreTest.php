<?php

namespace Tests\Feature\Controllers\Song;

use App\Models\Act;
use App\Models\Song;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/songs';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'title'  => fake()->sentence(),
            'act_id' => Act::factory()->createOne()->id,
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
    }

}
