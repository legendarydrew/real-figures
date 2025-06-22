<?php


namespace Tests\Unit\Contest;

use App\Facades\ContestFacade;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DetermineStageWinnersTest extends TestCase
{
    use DatabaseMigrations;

    protected Stage $stage;
    protected Round $round;
    protected array $song_ids;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage    = Stage::factory()->createOne();
        $this->round    = Round::factory()->for($this->stage)->ended()->withSongs(5)->withVotes()->create();
        $this->song_ids = $this->round->songs()->pluck('songs.id')->toArray();
    }


    public function test_stage_is_not_over()
    {
        $this->round->update([
            'starts_at' => now()->subDay(),
            'ends_at'   => now()->subSecond(),
        ]);
        self::assertTrue($this->stage->hasEnded());

        $result = ContestFacade::determineStageWinners($this->stage);
        self::assertNull($result);
    }

    public function test_round_with_no_outcomes()
    {
        $result = ContestFacade::determineStageWinners($this->stage);
        self::assertNull($result);
    }

    public function test_stage_with_outcomes()
    {
        foreach ($this->song_ids as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id' => $this->round->id,
                'song_id'  => $song_id
            ]);
        }
        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage);
        self::assertIsIterable($winners);
        self::assertIsIterable($runners_up);
    }

    public function test_stage_one_winner_with_ties_enabled()
    {
        config()->set('contest.judgement.allow-ties', true);

        foreach ($this->song_ids as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id'     => $this->round->id,
                'song_id'      => $song_id,
                'first_votes'  => $song_id,
                'second_votes' => $song_id,
                'third_votes'  => $song_id,
            ]);
        }

        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage);
        self::assertCount(1, $winners);
    }

    public function test_stage_multiple_winners_with_ties_enabled()
    {
        config()->set('contest.judgement.allow-ties', true);

        $winners = fake()->randomElements($this->song_ids);
        $others  = array_diff($this->song_ids, $winners);

        foreach ($winners as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id'     => $this->round->id,
                'song_id'      => $song_id,
                'first_votes'  => 99,
                'second_votes' => 99,
                'third_votes'  => 99,
            ]);
        }

        foreach ($others as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id'     => $this->round->id,
                'song_id'      => $song_id,
                'first_votes'  => fake()->numberBetween(0, 5),
                'second_votes' => fake()->numberBetween(0, 5),
                'third_votes'  => fake()->numberBetween(0, 5),
            ]);
        }

        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage);
        self::assertGreaterThanOrEqual(1, count($winners));
    }

    public function test_stage_one_winner_with_ties_disabled()
    {
        config()->set('contest.judgement.allow-ties', false);

        foreach ($this->song_ids as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id'     => $this->round->id,
                'song_id'      => $song_id,
                'first_votes'  => $song_id,
                'second_votes' => $song_id,
                'third_votes'  => $song_id,
            ]);
        }

        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage);
        self::assertCount(1, $winners);
    }

    public function test_stage_multiple_winners_with_ties_disabled()
    {
        config()->set('contest.judgement.allow-ties', false);
        foreach ($this->song_ids as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id'     => $this->round->id,
                'song_id'      => $song_id,
                'first_votes'  => 4,
                'second_votes' => 4,
                'third_votes'  => 4,
            ]);
        }

        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage);
        self::assertCount(1, $winners);
    }

    public function test_stage_runners_up_count()
    {
        foreach ($this->song_ids as $song_id)
        {
            RoundOutcome::factory()->create([
                'round_id'     => $this->round->id,
                'song_id'      => $song_id,
                'first_votes'  => $song_id,
                'second_votes' => $song_id,
                'third_votes'  => $song_id,
            ]);
        }

        foreach (range(1, 3) as $i)
        {
            [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage, $i);
            self::assertCount($i, $runners_up);
        }
    }

    public function test_stage_runners_up_with_ties_enabled()
    {
        config()->set('contest.judgement.allow-ties', true);

        // overall winner
        RoundOutcome::factory()->create([
            'round_id'     => $this->round->id,
            'song_id'      => $this->song_ids[0],
            'first_votes'  => 99,
            'second_votes' => 99,
            'third_votes'  => 99,
        ]);

        // tied runners-up
        RoundOutcome::factory(3)->create([
            'round_id'     => $this->round->id,
            'song_id'      => new Sequence($this->song_ids[1], $this->song_ids[2], $this->song_ids[3]),
            'first_votes'  => 49,
            'second_votes' => 49,
            'third_votes'  => 49,
        ]);

        // other runners-up
        RoundOutcome::factory()->create([
            'round_id'     => $this->round->id,
            'song_id'      => $this->song_ids[4],
            'first_votes'  => 9,
            'second_votes' => 9,
            'third_votes'  => 9,
        ]);

        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage, 2);
        self::assertGreaterThanOrEqual(2, count($runners_up));
    }

    public function test_stage_runners_up_with_ties_disabled()
    {
        config()->set('contest.judgement.allow-ties', false);

        // overall winner
        RoundOutcome::factory()->create([
            'round_id'     => $this->round->id,
            'song_id'      => $this->song_ids[0],
            'first_votes'  => 99,
            'second_votes' => 99,
            'third_votes'  => 99,
        ]);

        // tied runners-up
        RoundOutcome::factory(3)->create([
            'round_id'     => $this->round->id,
            'song_id'      => new Sequence($this->song_ids[1], $this->song_ids[2], $this->song_ids[3]),
            'first_votes'  => 49,
            'second_votes' => 49,
            'third_votes'  => 49,
        ]);

        // other runners-up
        RoundOutcome::factory()->create([
            'round_id'     => $this->round->id,
            'song_id'      => $this->song_ids[4],
            'first_votes'  => 9,
            'second_votes' => 9,
            'third_votes'  => 9,
        ]);

        [$winners, $runners_up] = ContestFacade::determineStageWinners($this->stage, 2);
        self::assertCount(2, $runners_up);
    }
}
