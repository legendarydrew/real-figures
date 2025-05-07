<?php

namespace Tests\Unit\StageWinner;

use App\Models\Round;
use App\Models\Song;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DescriptionTest extends TestCase
{
    use DatabaseMigrations;

    private StageWinner $row;

    protected function setUp(): void
    {
        parent::setUp();
        $stage = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->createOne();
        $song  = Song::factory()->withAct()->createOne();

        $this->row = StageWinner::create([
            'stage_id' => $stage->id,
            'round_id' => $round->id,
            'song_id'  => $song->id
        ]);
    }

    public function test_winner_description()
    {
        $this->row->update([
            'is_winner' => true
        ]);

        $expected_description = trans('contest.song.accolade.winner', [
            'stage' => $this->row->stage->title,
            'round' => $this->row->round->title
        ]);

        self::assertEquals($expected_description, $this->row->description);
    }

    public function test_runner_up_description()
    {
        $this->row->update([
            'is_winner' => false
        ]);

        $expected_description = trans('contest.song.accolade.runner_up', [
            'stage' => $this->row->stage->title,
            'round' => $this->row->round->title
        ]);

        self::assertEquals($expected_description, $this->row->description);
    }
}
