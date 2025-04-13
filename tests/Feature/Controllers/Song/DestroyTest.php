<?php

namespace Tests\Feature\Controllers\Song;

use App\Models\Act;
use App\Models\Song;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/songs/%u';

    private Song $song;

    protected function setUp(): void
    {
        parent::setUp();

        $this->song = Song::factory()->withAct()->create();
    }

    public function test_as_guest()
    {
        $response = $this->deleteJson(sprintf(self::ENDPOINT, $this->song->id));
        $response->assertUnauthorized();
    }

    public function test_as_user()
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
}
