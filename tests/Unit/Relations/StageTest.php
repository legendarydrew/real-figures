<?php

namespace Tests\Unit\Relations;

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
        Round::factory(2)->for($stage)->create();

        self::assertEquals(2, $stage->rounds->count());
        foreach ($stage->rounds as $round)
        {
            self::assertInstanceOf(Round::class, $round);
        }
    }

}
