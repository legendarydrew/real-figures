<?php

namespace Tests\Unit\Round;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FullTitleTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_only_round()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->for($stage)->create();

        $expected_title = trans('contest.round.title.only_round', [
            'stage_title' => $stage->title,
            'round_title' => $round->title
        ]);
        self::assertEquals($expected_title, $round->full_title);
    }

    public function test_as_one_of_many()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->for($stage)->create();
        Round::factory()->for($stage)->create();

        $expected_title = trans('contest.round.title.many_rounds', [
            'stage_title' => $stage->title,
            'round_title' => $round->title
        ]);
        self::assertEquals($expected_title, $round->full_title);
    }

}
