<?php

namespace Tests\Unit\Relations;

use App\Models\Act;
use App\Models\Round;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class RoundVoteTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $act   = Act::factory()->create();
        $songs = Song::factory()->count(3)->create(['act_id' => $act->id]);

        $stage = Stage::factory()->create();
        $round = Round::factory()->create([
            'stage_id' => $stage->id
        ]);

        $this->vote = RoundVote::create([
            'round_id'         => $round->id,
            'first_choice_id'  => $songs->get(0)->id,
            'second_choice_id' => $songs->get(1)->id,
            'third_choice_id'  => $songs->get(2)->id,
        ]);
    }

    public function test_round_relation()
    {
        self::assertInstanceOf(Round::class, $this->vote->round);
    }

    #[Depends('test_round_relation')] public function test_stage_relation()
    {
        self::assertInstanceOf(Stage::class, $this->vote->stage);
    }

    public function test_choices_relations()
    {
        self::assertInstanceOf(Song::class, $this->vote->first_choice);
        self::assertInstanceOf(Song::class, $this->vote->second_choice);
        self::assertInstanceOf(Song::class, $this->vote->third_choice);
    }
}
