<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class ManualVoteShowTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/stages/%u/manual-vote';

    private Stage $stage;
    private Round $round;
    private array $song_ids;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage    = Stage::factory()->createOne();
        $this->round    = Round::factory()->for($this->stage)->ended()->createOne();
        $this->song_ids = Song::factory(8)->withAct()->create()->pluck('id')->toArray();

        foreach ($this->song_ids as $song_id)
        {
            RoundSongs::create([
                'round_id' => $this->round->id,
                'song_id'  => $song_id,
            ]);
        }
    }

    public function test_as_guest()
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        self::assertTrue($this->stage->requiresManualVote());

        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_invalid_stage()
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_as_user')]
    public function test_no_manual_vote_required()
    {
        $stage = Stage::factory()->over()->withResults()->createOne();
        self::assertFalse($stage->requiresManualVote());

        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $stage->id));
        $response->assertRedirectToRoute('admin.stages');
    }

}
