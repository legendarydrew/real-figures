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

final class RequiresManualVoteTest extends TestCase
{
    use DatabaseMigrations;

    private Stage $stage;

    private array $song_ids;
    private int $min_votes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->min_votes = (int)config('contest.judgement.min-votes');
        $this->stage = Stage::factory()->createOne();
        $this->song_ids = Song::factory(3)->withAct()->create()->pluck('id')->toArray();
    }

    public function test_round_has_ended_with_enough_votes(): void
    {
        $round = Round::factory()->ended()->for($this->stage)->create();
        $this->createRoundOutcomes($round, $this->min_votes);

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_ended_with_no_votes(): void
    {
        $round = Round::factory()->ended()->for($this->stage)->create();
        $this->createRoundOutcomes($round, 0);

        self::assertTrue($round->requiresManualVote());
    }

    public function test_round_has_ended_with_too_few_votes(): void
    {
        $round = Round::factory()->ended()->for($this->stage)->create();
        $this->createRoundOutcomes($round, $this->min_votes - 1);

        self::assertTrue($round->requiresManualVote());
    }

    public function test_round_has_not_ended_with_votes(): void
    {
        $round = Round::factory()->started()->for($this->stage)->create();
        $this->createRoundOutcomes($round, $this->min_votes);

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_not_ended_with_no_votes(): void
    {
        $round = Round::factory()->started()->for($this->stage)->create();
        $this->createRoundOutcomes($round, 0);

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_no_outcomes(): void
    {
        // Edge case!
        $round = Round::factory()->started()->for($this->stage)->create();
        foreach ($this->song_ids as $song_id) {
            RoundSongs::create([
                'round_id' => $round->id,
                'song_id' => $song_id,
            ]);
        }

        self::assertFalse($round->requiresManualVote());
    }

    public function test_round_has_no_songs(): void
    {
        // Edge case!
        $round = Round::factory()->started()->for($this->stage)->create();

        self::assertFalse($round->requiresManualVote());
    }

    protected function createRoundOutcomes(Round $round, int $vote_count): void
    {
        $vote_count = max(1, $vote_count);

        foreach ($this->song_ids as $song_id) {
            RoundSongs::create([
                'round_id' => $round->id,
                'song_id' => $song_id,
            ]);
        }

        // Create the respective votes.
        // (It doesn't matter in this case that the scores match: we should have votes if we have outcomes.)
        for ($i = 0; $i < $vote_count; $i++) {
            $songs = fake()->randomElements($this->song_ids, 3);
            RoundVote::create([
                'round_id' => $round->id,
                'first_choice_id' => $songs[0],
                'second_choice_id' => $songs[1],
                'third_choice_id' => $songs[2],
            ]);
        }

        RoundOutcome::factory(count($this->song_ids))->for($round)->create([
            'song_id' => new Sequence(...$this->song_ids),
            'first_votes' => $vote_count ? fake()->numberBetween(1, 5) : 0,
            'second_votes' => $vote_count ? fake()->numberBetween(1, 5) : 0,
            'third_votes' => $vote_count ? fake()->numberBetween(1, 5) : 0,
        ]);
    }
}
