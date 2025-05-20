<?php

namespace Tests\Unit\Relations;

use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class RoundOutcomeTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $act  = Act::factory()->create();
        $song = Song::factory()->create(['act_id' => $act->id]);

        $stage = Stage::factory()->create();
        $round = Round::factory()->create([
            'stage_id' => $stage->id
        ]);

        $this->outcome = RoundOutcome::factory()->create([
            'round_id' => $round->id,
            'song_id'  => $song->id
        ]);
    }

    public function test_round_relation()
    {
        self::assertInstanceOf(Round::class, $this->outcome->round);
    }

    #[Depends('test_round_relation')] public function test_stage_relation()
    {
        self::assertInstanceOf(Stage::class, $this->outcome->stage);
    }

    public function test_song_relation()
    {
        self::assertInstanceOf(Song::class, $this->outcome->song);
    }

}
