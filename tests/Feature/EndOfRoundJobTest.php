<?php

namespace Tests\Feature;

use App\Jobs\EndOfRound;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class EndOfRoundJobTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->number_of_songs = 4;
        $songs                 = Song::factory($this->number_of_songs)->withAct()->create();
        $this->song_ids        = $songs->pluck('id')->toArray();

        $this->round = Round::factory()
                            ->for(Stage::factory())
                            ->create([
                                'starts_at' => now()->subDay(),
                                'ends_at'   => now()->subDay(),
                            ]);
        foreach ($this->song_ids as $song_id)
        {
            RoundSongs::create([
                'round_id' => $this->round->id,
                'song_id'  => $song_id,
            ]);
        }

        // Create some votes.
        $number_of_votes = fake()->numberBetween(1, 10);
        for ($i = 0; $i < $number_of_votes; $i++)
        {
            $picks = fake()->randomElements($this->song_ids, 3);
            RoundVote::create([
                'round_id'         => $this->round->id,
                'first_choice_id'  => $picks[0],
                'second_choice_id' => $picks[1],
                'third_choice_id'  => $picks[2]
            ]);
        }

        // Check that there are no existing round outcomes.
        self::assertEquals(0, $this->round->outcomes()->count());
    }

    public function test_after_round_end()
    {
        EndOfRound::dispatch($this->round);

        // Test for the creation of RoundOutcomes.
        self::assertEquals($this->number_of_songs, $this->round->outcomes()->count());

        // Each Song associated with the round should have an associated RoundOutcome.
        $outcome_song_ids = $this->round->outcomes()->pluck('song_id');
        foreach ($this->round->songs as $song)
        {
            self::assertContains($song->id, $outcome_song_ids);
        }
    }

    #[Depends('test_after_round_end')]
    public function test_before_round_begins()
    {
        $this->round->update([
            'starts_at' => now()->addDay(),
        ]);
        EndOfRound::dispatch($this->round);

        self::assertEquals(0, $this->round->outcomes()->count());
    }

    #[Depends('test_after_round_end')]
    public function test_before_round_end()
    {
        $this->round->update([
            'ends_at' => now()->addDay(),
        ]);
        EndOfRound::dispatch($this->round);

        self::assertEquals(0, $this->round->outcomes()->count());
    }

    public function test_edge_round_start_after_round_end()
    {
        $this->round->update([
            'starts_at' => now()->addDays(2),
            'ends_at'   => now()->addDay(),
        ]);
        EndOfRound::dispatch($this->round);

        self::assertEquals(0, $this->round->outcomes()->count());
    }

    #[Depends('test_after_round_end')]
    public function test_if_no_votes()
    {
        RoundVote::truncate();

        // Originally, if there were no votes for a round, the idea was to employ a "panel of judges"
        // that would assign random scores.
        // Instead, it was decided that I'm going to vote on the entries in the round myself.
        // (But how would this impact the calculation of runners-up? What if there is a tie?)
        // So this test would check that NO outcomes are generated when a round with no votes is up.

        EndOfRound::dispatch($this->round);

        self::assertEquals(0, $this->round->outcomes()->count());
    }

    #[Depends('test_after_round_end')]
    public function test_duplicate()
    {
        EndOfRound::dispatch($this->round);

        self::assertEquals($this->number_of_songs, $this->round->outcomes()->count());

        EndOfRound::dispatch($this->round);

        // Only one set of outcomes (one for each song) should have been created.
        self::assertEquals($this->number_of_songs, $this->round->outcomes()->count());
    }
}
