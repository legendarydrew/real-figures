<?php

namespace Unit\Relations;

use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoundTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $act   = Act::factory()->create();
        $songs = Song::factory()->count(3)->create(['act_id' => $act->id]);

        $this->stage = Stage::factory()->create();
        $this->round = Round::factory()->create([
            'stage_id' => $this->stage->id
        ]);


        RoundSongs::create([
            'round_id' => $this->round->id,
            'song_id'  => $songs->get(0)->id
        ]);

        RoundVote::create([
            'round_id'         => $this->round->id,
            'first_choice_id'  => $songs->get(0)->id,
            'second_choice_id' => $songs->get(1)->id,
            'third_choice_id'  => $songs->get(2)->id,
        ]);

        RoundOutcome::factory(3)->create([
            'round_id' => $this->round->id,
            'song_id'  => new Sequence(
                $songs->get(0)->id,
                $songs->get(1)->id,
                $songs->get(2)->id
            )
        ]);
    }

    public function test_stage_relation()
    {
        self::assertInstanceOf(Stage::class, $this->round->stage);
    }

    public function test_songs_relation()
    {
        self::assertGreaterThan(0, $this->round->songs()->count());
    }

    public function test_votes_relation()
    {
        self::assertGreaterThan(0, $this->round->votes()->count());
    }

    public function test_outcomes_relation()
    {
        self::assertGreaterThan(0, $this->round->votes()->count());
    }
}
