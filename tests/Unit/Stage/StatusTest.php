<?php

namespace Stage;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use DatabaseMigrations;

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->createOne();
    }

    public function test_stage_has_no_rounds()
    {
        $this->stage->rounds()->delete();

        self::assertEquals(trans('contest.stage.status.inactive'), $this->stage->status);
    }

    public function test_stage_has_rounds_not_started()
    {
        Round::factory(3)->for($this->stage)->withSongs(2)->create([
            'starts_at' => now()->addDay(),
            'ends_at'   => now()->addDays(2),
        ]);

        self::assertEquals(trans('contest.stage.status.ready'), $this->stage->status);
    }

    public function test_stage_has_some_rounds_started()
    {
        Round::factory(3)->for($this->stage)->withSongs(2)->create();
        Round::factory(3)->for($this->stage)->withSongs(2)->started()->create();
        self::assertEquals(trans('contest.stage.status.started'), $this->stage->status);
    }

    public function test_stage_has_some_rounds_ended()
    {
        Round::factory(3)->for($this->stage)->withSongs(2)->started()->create();
        Round::factory(3)->for($this->stage)->withSongs(2)->ended()->create();
        self::assertEquals(trans('contest.stage.status.started'), $this->stage->status);
    }

    public function test_stage_has_all_rounds_ended_without_outcomes()
    {
        Round::factory(3)->for($this->stage)->withSongs(2)->ended()->create();
        self::assertEquals(trans('contest.stage.status.judgement'), $this->stage->status);
    }

    public function test_stage_has_all_rounds_ended_with_outcomes()
    {
        $round = Round::factory()->for($this->stage)->withSongs(2)->ended()->create();
        foreach ($round->songs as $song)
        {
            RoundOutcome::factory()->create([
                'round_id' => $round->id,
                'song_id'  => $song->id
            ]);
        }
        self::assertEquals(trans('contest.stage.status.judgement'), $this->stage->status);
    }

    public function test_stage_has_all_rounds_ended_with_winners()
    {
        $round = Round::factory()->for($this->stage)->withSongs(2)->ended()->create();
        foreach ($round->songs as $song)
        {
            StageWinner::create([
                'stage_id' => $round->stage->id,
                'round_id' => $round->id,
                'song_id'  => $song->id
            ]);
        }
        self::assertEquals(trans('contest.stage.status.ended'), $this->stage->status);
    }
}
