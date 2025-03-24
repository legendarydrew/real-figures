<?php

namespace Relations;

use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SongTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $act        = Act::factory()->create();
        $this->song = Song::factory()->create(['act_id' => $act->id]);

        $this->stage = Stage::factory()->create();
        $this->round = Round::factory()->create([
            'stage_id' => $this->stage->id
        ]);

        RoundOutcome::factory()->create([
            'round_id' => $this->round->id,
            'song_id'  => $this->song->id
        ]);
    }

    public function test_act_relation()
    {
        self::assertInstanceOf(Act::class, $this->song->act);
    }

    public function test_outcomes_relation()
    {
        self::assertEquals(1, $this->song->outcomes->count());
        foreach ($this->song->outcomes as $outcome)
        {
            self::assertInstanceOf(RoundOutcome::class, $outcome);
        }
    }
}
