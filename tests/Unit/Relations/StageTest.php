<?php

namespace Relations;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StageTest extends TestCase
{
    use DatabaseMigrations;

    public function test_rounds_relation()
    {
        $stage = Stage::factory()->create();
        Round::factory()->count(2)->create(['stage_id' => $stage->id]);
        self::assertGreaterThan(0, $stage->rounds()->count());
    }

}
