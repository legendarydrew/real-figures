<?php

namespace Tests\Unit\Stage;

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

    public function test_all_rounds_ended_with_votes()
    {
        $rounds = Round::factory(4)->ended()->for($this->stage)->create();
        foreach ($rounds as $round)
        {
            $this->createRoundOutcomes($round, no_votes: false);
        }

        $scores = $this->stage->outcomes->map(fn($outcome) => $outcome->score);
        self::assertNotContains(0, $scores);

        self::assertTrue($this->stage->hasEnded());
        self::assertTrue($this->stage->outcomes->isNotEmpty());
        self::assertFalse($this->stage->requiresManualVote());
    }

    public function test_some_rounds_ended_with_no_votes()
    {
        $rounds = Round::factory(4)->ended()->for($this->stage)->create();
        foreach ($rounds as $index => $round)
        {
            $this->createRoundOutcomes($round, no_votes: (bool)($index % 2));
        }

        self::assertTrue($this->stage->hasEnded());

        $scores = $this->stage->outcomes->map(fn($outcome) => $outcome->score);
        self::assertContains(0, $scores);

        self::assertTrue($this->stage->outcomes->isNotEmpty());
        self::assertTrue($this->stage->requiresManualVote());
    }

    public function test_no_rounds_ended_with_votes()
    {
        $rounds = Round::factory(4)->started()->for($this->stage)->create();
        foreach ($rounds as $round)
        {
            $this->createRoundOutcomes($round, no_votes: false);
        }

        $scores = $this->stage->outcomes->map(fn($outcome) => $outcome->score);
        self::assertTrue($scores->every(fn($score) => $score > 0));

        self::assertFalse($this->stage->hasEnded());
        self::assertTrue($this->stage->outcomes->isNotEmpty());
        self::assertFalse($this->stage->requiresManualVote());
    }

    public function test_no_rounds_ended_with_no_votes()
    {
        $rounds = Round::factory(4)->started()->for($this->stage)->create();
        foreach ($rounds as $index => $round)
        {
            $this->createRoundOutcomes($round, no_votes: $index % 2);
        }

        self::assertFalse($this->stage->hasEnded());
        self::assertTrue($this->stage->outcomes->isNotEmpty());
        self::assertFalse($this->stage->requiresManualVote());
    }

    public function test_no_rounds()
    {
        // Edge case!
        self::assertFalse($this->stage->requiresManualVote());
    }

    protected function createRoundOutcomes(Round $round, bool $no_votes): void
    {
        foreach ($this->song_ids as $song_id)
        {
            RoundSongs::create([
                'round_id' => $round->id,
                'song_id'  => $song_id
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
