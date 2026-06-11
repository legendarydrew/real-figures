<?php

namespace Tests\Unit\Contest;

use App\Enums\VoteType;
use App\Facades\ContestFacade;
use App\Models\Round;
use App\Models\RoundVote;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class BreakdownVoteTypesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_structure()
    {
        $stage   = Stage::factory()->createOne();
        $round   = Round::factory()->for($stage)->withSongs(3)->createOne();
        $results = ContestFacade::breakdownVoteTypes($round);

        self::assertArrayHasKey('acts', $results);
        self::assertArrayHasKey('breakdown', $results);

        self::assertCount(3, $results['acts']);
        self::assertCount(3, $results['breakdown']);

        foreach ($results['breakdown'] as $row)
        {
            self::assertArrayHasKey('id', $row);
            self::assertArrayHasKey(VoteType::ORGANIC->value, $row);
            self::assertArrayHasKey(VoteType::MANUAL->value, $row);
            self::assertArrayHasKey(VoteType::DUMBRICK->value, $row);
        }
    }

    public function test_no_votes()
    {
        $stage = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->withSongs(3)->createOne();

        $results = ContestFacade::breakdownVoteTypes($round);

        foreach ($results['breakdown'] as $row)
        {
            self::assertEquals(0, $row[VoteType::ORGANIC->value]);
            self::assertEquals(0, $row[VoteType::MANUAL->value]);
            self::assertEquals(0, $row[VoteType::DUMBRICK->value]);
        }
    }

    public function test_with_votes()
    {
        $stage = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->withSongs(3)->createOne();

        RoundVote::create([
            'round_id' => $round->id,
            'vote_type'        => VoteType::ORGANIC->value,
            'first_choice_id'  => $round->songs[0]->act_id,
            'second_choice_id' => $round->songs[1]->act_id,
            'third_choice_id'  => $round->songs[2]->act_id,
        ]);

        RoundVote::create([
            'round_id' => $round->id,
            'vote_type'        => VoteType::MANUAL->value,
            'first_choice_id'  => $round->songs[0]->act_id,
            'second_choice_id' => $round->songs[1]->act_id,
            'third_choice_id'  => $round->songs[2]->act_id,
        ]);

        RoundVote::create([
            'round_id' => $round->id,
            'vote_type'        => VoteType::DUMBRICK->value,
            'first_choice_id'  => $round->songs[0]->act_id,
            'second_choice_id' => $round->songs[1]->act_id,
            'third_choice_id'  => $round->songs[2]->act_id,
        ]);

        $results = ContestFacade::breakdownVoteTypes($round);

        foreach ($round->songs as $i => $song)
        {
            $breakdown_row = $results['breakdown'][$i];
            self::assertEquals($song->act_id, $breakdown_row['id']);
            self::assertEquals(config('contest.points')[$i], $breakdown_row[VoteType::ORGANIC->value]);
            self::assertEquals(config('contest.points')[$i], $breakdown_row[VoteType::MANUAL->value]);
            self::assertEquals(config('contest.points')[$i], $breakdown_row[VoteType::DUMBRICK->value]);
        }
    }
}
