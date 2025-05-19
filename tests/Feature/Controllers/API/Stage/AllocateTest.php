<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class AllocateTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/stages/%u/allocate';

    private Stage $stage;
    private array $song_ids;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage    = Stage::factory()->createOne();
        $this->song_ids = Song::factory(6)->withAct()->create()->pluck('id')->toArray();

        $this->payload = [
            'song_ids'  => $this->song_ids,
            'per_round' => 4,
            'duration'  => 5,
            'start_at'  => now()->addHour()->toISOString(),
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertRedirectToRoute('admin.stages');
    }

    #[Depends('test_as_user')]
    public function test_creates_rounds()
    {
        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $this->stage->refresh();

        self::assertCount(2, $this->stage->rounds);
    }

    #[Depends('test_as_user')]
    public function test_without_start_at()
    {
        unset($this->payload['start_at']);
        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertRedirectToRoute('admin.stages');

        $this->stage->refresh();
        self::assertCount(2, $this->stage->rounds);
    }

    #[Depends('test_as_user')]
    public function test_start_in_past()
    {
        $this->payload['start_at'] = now()->subDay();
        $response                  = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnprocessable();
    }

    #[Depends('test_as_user')]
    public function test_invalid_stage()
    {
        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

    #[Depends('test_as_user')]
    public function test_without_enough_songs()
    {
        $this->payload['song_ids'] = [];
        $response                  = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnprocessable();

        $this->payload['song_ids'] = [$this->song_ids[0]];
        $response                  = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnprocessable();
    }
}
