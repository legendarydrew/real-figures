<?php

namespace Tests\Unit\Round;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class ResultsTest extends TestCase
{
    use DatabaseMigrations;

    private int $number_of_songs = 20; // a good size for effective testing.

    private array $song_ids = [];

    protected function setUp(): void
    {
        parent::setUp();

        $songs          = Song::factory($this->number_of_songs)->withAct()->create();
        $this->song_ids = $songs->pluck('id')->toArray();

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
    }

    public function test_no_outcomes()
    {
        $results = $this->round->results();
        self::assertNull($results);
    }

    public function test_by_descending_score()
    {
        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id' => new Sequence(...$this->song_ids)
                    ]);

        $results = $this->round->results();

        $last_score = null;
        foreach ($results as $index => $result)
        {
            if ($last_score)
            {
                self::assertLessThanOrEqual($last_score, $result->score, "position {$index}");
            }
            $last_score = $result->score;
        }
    }

    #[Depends('test_by_descending_score')]
    public function test_by_first_votes()
    {
        // For this test to work, the scores for each song have to be the same.
        $first_votes_sequence  = [];
        $second_votes_sequence = [];
        $third_votes_sequence  = [];
        $total_score           = 100;
        foreach (range(1, $this->number_of_songs) as $_)
        {
            $first_votes             = fake()->numberBetween(1, 10);
            $second_votes            = fake()->numberBetween(1, 20);
            $first_votes_sequence[]  = $first_votes;
            $second_votes_sequence[] = $second_votes;
            $third_votes_sequence[]  = $total_score
                - ($first_votes * config('contest.points.0'))
                - ($second_votes * config('contest.points.1'));
        }

        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...$first_votes_sequence),
                        'second_votes' => new Sequence(...$second_votes_sequence),
                        'third_votes'  => new Sequence(...$third_votes_sequence),
                    ]);

        $results      = $this->round->results();
        $last_outcome = null;
        foreach ($results as $index => $result)
        {
            if ($last_outcome)
            {
                self::assertEquals($last_outcome->score, $result->score, "position {$index}");
                self::assertLessThanOrEqual($last_outcome->first_votes, $result->first_votes, "position {$index}");
            }
            $last_outcome = $result;
        }
    }

    #[Depends('test_by_descending_score')]
    public function test_by_second_votes()
    {
        // For this test to work, the scores and number of first votes for each song
        // have to be the same.
        $first_votes_sequence  = [];
        $second_votes_sequence = [];
        $third_votes_sequence  = [];
        $total_score           = 100;
        foreach (range(1, $this->number_of_songs) as $_)
        {
            $first_votes             = 5;
            $second_votes            = fake()->numberBetween(1, 20);
            $first_votes_sequence[]  = $first_votes;
            $second_votes_sequence[] = $second_votes;
            $third_votes_sequence[]  = $total_score
                - ($first_votes * config('contest.points.0'))
                - ($second_votes * config('contest.points.1'));
        }

        RoundOutcome::factory($this->number_of_songs)
                    ->for($this->round)
                    ->create([
                        'song_id'      => new Sequence(...$this->song_ids),
                        'first_votes'  => new Sequence(...$first_votes_sequence),
                        'second_votes' => new Sequence(...$second_votes_sequence),
                        'third_votes'  => new Sequence(...$third_votes_sequence),
                    ]);

        $results      = $this->round->results();
        $last_outcome = null;
        foreach ($results as $index => $result)
        {
            if ($last_outcome)
            {
                self::assertEquals($last_outcome->score, $result->score, "position {$index}");
                self::assertEquals($last_outcome->first_votes, $result->first_votes, "position {$index}");
                self::assertLessThanOrEqual($last_outcome->second_votes, $result->second_votes, "position {$index}");
            }
            $last_outcome = $result;
        }
    }

}
