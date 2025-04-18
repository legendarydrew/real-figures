<?php

namespace Round;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RequiresManualVoteTest extends TestCase
{
    use DatabaseMigrations;

    private Stage $stage;
    private array $song_ids;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage    = Stage::factory()->createOne();
        $this->song_ids = Song::factory(3)->withAct()->create()->pluck('id')->toArray();
    }

    public function test_round_has_ended_with_votes()
    {
        $round = Round::factory()->ended()->for($this->stage)->create();
        $this->createRoundOutcomes($round, no_votes: false);

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_ended_with_no_votes()
    {
        $round = Round::factory()->ended()->for($this->stage)->create();
        $this->createRoundOutcomes($round, no_votes: true);

        self::assertTrue($round->requiresManualVote());
    }

    public function test_round_has_not_ended_with_votes()
    {
        $round = Round::factory()->started()->for($this->stage)->create();
        $this->createRoundOutcomes($round, no_votes: false);

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_not_ended_with_no_votes()
    {
        $round = Round::factory()->started()->for($this->stage)->create();
        $this->createRoundOutcomes($round, no_votes: true);

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_no_outcomes()
    {
        // Edge case!
        $round = Round::factory()->started()->for($this->stage)->create();

        self::assertFalse($round->requiresManualVote());
    }

    protected function createRoundOutcomes(Round $round, bool $no_votes): void
    {
        RoundOutcome::factory(count($this->song_ids))->for($round)->create([
            'song_id'      => new Sequence(...$this->song_ids),
            'first_votes'  => $no_votes ? 0 : fake()->numberBetween(1, 5),
            'second_votes' => $no_votes ? 0 : fake()->numberBetween(1, 5),
            'third_votes'  => $no_votes ? 0 : fake()->numberBetween(1, 5),
        ]);
    }

}
