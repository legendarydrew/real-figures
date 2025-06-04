<?php

namespace Controllers\API\Song;

use App\Models\Song;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class PlayTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/songs/%u/play';

    private Song $song;

    protected function setUp(): void
    {
        parent::setUp();

        $this->song = Song::factory()->withAct()->createOne();
    }

    public function test_as_guest()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->song->id));
        $response->assertNoContent();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->song->id));
        $response->assertNoContent();
    }

    #[Depends('test_as_user')]
    public function test_increments_play_count()
    {
        $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->song->id));

        $this->song->refresh();
        self::assertEquals(1, $this->song->plays()->count());
        self::assertEquals(1, $this->song->play_count);
    }

    #[Depends('test_as_user')]
    public function test_multiple_increments_on_same_day()
    {
        $play_count = fake()->numberBetween(2, 10);
        foreach (range(1, $play_count) as $i)
        {
            $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->song->id));
        }

        $this->song->refresh();
        self::assertEquals(1, $this->song->plays()->count());
        self::assertEquals($play_count, $this->song->play_count);
    }

    #[Depends('test_as_user')]
    public function test_increments_different_days()
    {
        $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->song->id));

        $this->travel(1)->day();
        $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->song->id));

        $this->song->refresh();
        self::assertEquals(2, $this->song->plays()->count());
        self::assertEquals(2, $this->song->play_count);
    }

    #[Depends('test_as_user')]
    public function test_invalid_song()
    {
        $response = $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

}
