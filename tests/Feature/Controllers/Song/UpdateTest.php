<?php

namespace Tests\Feature\Controllers\Song;

use App\Models\Act;
use App\Models\Song;
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

        $this->song    = Song::factory()->withAct()->createOne();
        $this->payload = [
            'title'  => fake()->sentence(),
            'act_id' => Act::factory()->createOne()->id,
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
    public function test_updates_act()
    {
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->song->id), $this->payload);

        $this->song->refresh();
        self::assertEquals($this->payload['title'], $this->song->title);
        self::assertEquals($this->payload['act_id'], $this->song->act_id);
    }

    #[Depends('test_as_user')]
    public function test_invalid_act()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

}
