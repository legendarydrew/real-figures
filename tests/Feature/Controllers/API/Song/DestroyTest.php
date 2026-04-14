<?php

namespace Tests\Feature\Controllers\API\Song;

use App\Models\Act;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/songs/%u';

    private Song $song;

    protected function setUp(): void
    {
        parent::setUp();

        $this->song = Song::factory()->withAct()->withUrl(fake()->url)->create();
    }

    public function test_as_guest(): void
    {
        $response = $this->deleteJson(sprintf(self::ENDPOINT, $this->song->id));
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->deleteJson(sprintf(self::ENDPOINT, $this->song->id));
        $response->assertRedirect(route('admin.songs'));
    }

    #[Depends('test_as_user')]
    public function test_invalid_row(): void
    {
        $response = $this->actingAs($this->user)->deleteJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_as_user')]
    public function test_does_not_delete_act(): void
    {
        $this->actingAs($this->user)->deleteJson(sprintf(self::ENDPOINT, $this->song->id));
        $act = Act::find($this->song->act_id);

        self::assertInstanceOf(Act::class, $act);
    }

    #[Depends('test_as_user')]
    public function test_deletes_urls(): void
    {
        $this->actingAs($this->user)->deleteJson(sprintf(self::ENDPOINT, $this->song->id));
        $urls = SongUrl::whereSongId($this->song->id)->get();

        self::assertEmpty($urls);
    }
}
