<?php

namespace Job;

use App\Jobs\CheckEndedRounds;
use App\Jobs\EndOfRound;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

final class CheckEndedRoundsTest extends TestCase
{
    use DatabaseMigrations;

    private MockInterface $spy;
    private Stage         $stage;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake([EndOfRound::class]);
        $this->stage = Stage::factory()->createOne();
    }

    public function test_no_rounds()
    {
        CheckEndedRounds::dispatchSync();
        Queue::assertNothingPushed();
    }

    public function test_round_not_started()
    {
        Round::factory()->for($this->stage)->ready()->createOne();
        CheckEndedRounds::dispatchSync();

        Queue::assertNothingPushed();
    }

    public function test_round_started_not_ended()
    {
        Round::factory()->for($this->stage)->started()->createOne();
        CheckEndedRounds::dispatchSync();

        Queue::assertNothingPushed();
    }

    public function test_round_ended()
    {
        $round = Round::factory()->for($this->stage)->ended()->createOne();
        CheckEndedRounds::dispatchSync();

        Queue::assertPushed(EndOfRound::class, function (EndOfRound $job) use ($round) {
            return $job->round->id === $round->id;
        });
    }

}
