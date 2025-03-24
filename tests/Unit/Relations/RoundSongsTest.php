<?php

namespace Relations;

use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class RoundSongsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $act   = Act::factory()->create();
        $song = Song::factory()->create(['act_id' => $act->id]);

        $stage = Stage::factory()->create();
        $this->round = Round::factory()->create([
            'stage_id' => $stage->id
        ]);

        $this->round_songs = RoundSongs::create([
            'round_id' => $this->round->id,
            'song_id'  => $song->id
        ]);
    }

    public function test_round_relation()
    {
        self::assertInstanceOf(Round::class, $this->round_songs->round);
    }

    #[Depends('test_round_relation')] function test_stage_relation()
    {
        self::assertInstanceOf(Stage::class, $this->round_songs->stage);
    }

    public function test_song_relation()
    {
        self::assertInstanceOf(Song::class, $this->round_songs->song);
    }
}
