<?php


namespace Tests\Unit\Contest;

use App\Facades\ContestFacade;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetCurrentStageTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_stages()
    {
        $current_stage = ContestFacade::getCurrentStage();
        self::assertNull($current_stage);
    }

    public function test_inactive_stage()
    {
        $stage = Stage::factory()->create();

        $current_stage = ContestFacade::getCurrentStage();
        self::assertNull($current_stage);
    }

    public function test_ready_stage()
    {
        $stage = Stage::factory()->withRounds(started_count: 0, ended_count: 0)->create();
        Stage::factory(2)->withRounds()->create();

        $current_stage = ContestFacade::getCurrentStage();
        self::assertInstanceOf(Stage::class, $current_stage);
        self::assertEquals($stage->id, $current_stage->id);
    }

    public function test_active_first_stage()
    {
        $stage = Stage::factory()->withRounds(started_count: 2, ended_count: 0)->create();
        Stage::factory(2)->withRounds(false)->create();

        $current_stage = ContestFacade::getCurrentStage();
        self::assertInstanceOf(Stage::class, $current_stage);
        self::assertEquals($stage->id, $current_stage->id);
    }

    public function test_ended_stage()
    {
        $stage = Stage::factory()->withRounds(started_count: 0, ended_count: 2)->create();
        Stage::factory(2)->withRounds(false)->create();

        $current_stage = ContestFacade::getCurrentStage();
        self::assertInstanceOf(Stage::class, $current_stage);
        self::assertEquals($stage->id, $current_stage->id);
    }

    public function test_over_stages()
    {
        Stage::factory(2)->over()->create()->toArray();
        $stage = Stage::factory()->withRounds()->create();

        $current_stage = ContestFacade::getCurrentStage();
        self::assertInstanceOf(Stage::class, $current_stage);
        self::assertEquals($stage->id, $current_stage->id);
    }

    public function test_contest_is_over()
    {
        $stages = Stage::factory(2)->over()->create()->toArray();

        $current_stage = ContestFacade::getCurrentStage();
        self::assertInstanceOf(Stage::class, $current_stage);
        self::assertEquals($stages[1]['id'], $current_stage->id);
    }
}
