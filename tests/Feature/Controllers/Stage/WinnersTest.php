<?php

namespace Controllers\Stage;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class WinnersTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = 'api/stages/%u/winners';
    private Stage $stage;
    private array $payload;
    private const int  ROUND_COUNT = 3;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stage = Stage::factory()->createOne();

        $this->payload = [
            'runners_up' => 1
        ];

        $rounds = Round::factory(self::ROUND_COUNT)
                       ->for($this->stage)
                       ->withSongs(8)
                       ->ended()
                       ->create();
        foreach ($rounds as $round)
        {
            self::assertTrue($round->hasEnded());
            $song_ids = $round->songs->pluck('id');
            RoundOutcome::factory($song_ids->count())->create([
                'round_id' => $round->id,
                'song_id'  => new Sequence(...$song_ids)
            ]);
        }
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
    public function test_creates_winner_rows()
    {
        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $winner_rows = StageWinner::whereIsWinner(true)->get();
        self::assertGreaterThanOrEqual(self::ROUND_COUNT, count($winner_rows));
    }

//    #[Depends('test_as_user')]
    public function test_creates_runner_up()
    {
        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $runner_up_rows = StageWinner::whereIsWinner(false)->get();
        self::assertGreaterThanOrEqual(1, count($runner_up_rows));
    }

    #[Depends('test_as_user')]
    public function test_create_only_winners()
    {
        $this->payload['runners_up'] = 0;
        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);

        $winner_rows    = StageWinner::whereIsWinner(true)->get();
        $runner_up_rows = StageWinner::whereIsWinner(false)->get();
        self::assertGreaterThanOrEqual(self::ROUND_COUNT, count($winner_rows));
        self::assertEquals(0, count($runner_up_rows));
    }

    #[Depends('test_as_user')]
    public function test_creates_multiple_runners_up()
    {
        $this->payload['runners_up'] = 2;
        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);

        $runner_up_rows = StageWinner::whereIsWinner(false)->get();
        self::assertGreaterThanOrEqual(2, count($runner_up_rows));

    }

    #[Depends('test_as_user')]
    public function test_no_winners()
    {
        // An edge case where everything is tied because there were absolutely no votes.
        // This should never happen!
        RoundOutcome::truncate();

        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);

        $winner_rows    = StageWinner::whereIsWinner(true)->get();
        $runner_up_rows = StageWinner::whereIsWinner(false)->get();
        self::assertEquals(0, count($winner_rows));
        self::assertEquals(0, count($runner_up_rows));
    }

}
