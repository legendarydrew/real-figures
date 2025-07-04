<?php

namespace Tests\Feature\RoundResults;

use App\Facades\RoundResultsFacade;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CalculateTest extends TestCase
{
    use DatabaseMigrations;

    private Round $round;
    private int   $number_of_songs = 8;
    private array $song_ids;

    protected function setUp(): void
    {
        parent::setUp();

        $this->round    = Round::factory()
                               ->for(Stage::factory())
                               ->withSongs($this->number_of_songs)
                               ->ended()
                               ->create();
        $this->song_ids = $this->round->songs->pluck('id')->toArray();
    }

    public function test_no_outcomes()
    {
        $results = RoundResultsFacade::calculate($this->round);
        self::assertNull($results);
    }

    public function test_one_winner()
    {
        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                        'second_votes' => new Sequence(...range(1, $this->number_of_songs)),
                        'third_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                    ]);

        config()->set('contest.judgement.allow-ties', true);
        $results = RoundResultsFacade::calculate($this->round);

        self::assertNotNull($results);
        self::assertCount(1, $results['winners']);

        config()->set('contest.judgement.allow-ties', false);
        $results = RoundResultsFacade::calculate($this->round);

        self::assertNotNull($results);
        self::assertCount(1, $results['winners']);
    }

    public function test_tied_winners()
    {
        $tied_winner_count = fake()->numberBetween(2, ceil($this->number_of_songs / 2));
        RoundOutcome::factory($tied_winner_count)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => 50,
                        'second_votes' => 50,
                        'third_votes'  => 50,
                    ]);
        RoundOutcome::factory($this->number_of_songs - $tied_winner_count)
                    ->for($this->round)
                    ->create([
                        'song_id' => new Sequence(...array_slice($this->song_ids, $tied_winner_count)),
                    ]);

        config()->set('contest.judgement.allow-ties', true);
        $results = RoundResultsFacade::calculate($this->round);

        self::assertNotNull($results);
        self::assertCount($tied_winner_count, $results['winners']);

        config()->set('contest.judgement.allow-ties', false);
        $results = RoundResultsFacade::calculate($this->round);

        self::assertNotNull($results);
        self::assertCount(1, $results['winners']);
    }

    public function test_no_runners_up()
    {
        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                        'second_votes' => new Sequence(...range(1, $this->number_of_songs)),
                        'third_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                    ]);

        $results = RoundResultsFacade::calculate($this->round, 0);
        self::assertCount(0, $results['runners_up']);
    }

    public function test_one_runner_up()
    {
        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                        'second_votes' => new Sequence(...range(1, $this->number_of_songs)),
                        'third_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                    ]);

        $results = RoundResultsFacade::calculate($this->round, 1);
        self::assertCount(1, $results['runners_up']);
    }

    public function test_multiple_runners_up()
    {
        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                        'second_votes' => new Sequence(...range(1, $this->number_of_songs)),
                        'third_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                    ]);

        $runner_up_count = ceil($this->number_of_songs / 2);
        $results         = RoundResultsFacade::calculate($this->round, $runner_up_count);
        self::assertLessThanOrEqual($runner_up_count, $results['runners_up']->count());
    }

    public function test_no_duplicates()
    {
        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                        'second_votes' => new Sequence(...range(1, $this->number_of_songs)),
                        'third_votes'  => new Sequence(...range(1, $this->number_of_songs)),
                    ]);

        $runner_up_count = $this->number_of_songs - 1;
        $results         = RoundResultsFacade::calculate($this->round, $runner_up_count);

        foreach ($results['runners_up'] as $runner_up)
        {
            self::assertNotEquals($results['winners']->first()->id, $runner_up->id);
        }
    }
}
