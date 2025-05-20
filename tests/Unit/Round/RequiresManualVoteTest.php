<?php

namespace Tests\Unit\Round;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\RoundVote;
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
        foreach ($this->song_ids as $song_id)
        {
            RoundSongs::create([
                'round_id' => $round->id,
                'song_id'  => $song_id,
            ]);
        }

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_no_songs()
    {
        // Edge case!
        $round = Round::factory()->started()->for($this->stage)->create();

        self::assertFalse($round->requiresManualVote());
    }

    protected function createRoundOutcomes(Round $round, bool $no_votes): void
    {
        foreach ($this->song_ids as $song_id)
        {
            RoundSongs::create([
                'round_id' => $round->id,
                'song_id'  => $song_id,
            ]);
        }

        if (!$no_votes)
        {
            // Create the respective votes.
            // (It doesn't matter in this case that the scores match: we should have votes if we have outcomes.)
            $vote_count = fake()->numberBetween(2, 10);
            for ($i = 0; $i < $vote_count; $i++)
            {
                $songs = fake()->randomElements($this->song_ids, 3);
                RoundVote::create([
                    'round_id'         => $round->id,
                    'first_choice_id'  => $songs[0],
                    'second_choice_id' => $songs[1],
                    'third_choice_id'  => $songs[2]
                ]);
            }
        }

        RoundOutcome::factory(count($this->song_ids))->for($round)->create([
            'song_id'      => new Sequence(...$this->song_ids),
            'first_votes'  => $no_votes ? 0 : fake()->numberBetween(1, 5),
            'second_votes' => $no_votes ? 0 : fake()->numberBetween(1, 5),
            'third_votes'  => $no_votes ? 0 : fake()->numberBetween(1, 5),
        ]);
    }

}
