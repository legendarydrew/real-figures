<?php

namespace Round;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HasEndedTest extends TestCase
{
    use DatabaseMigrations;

    public function test_has_ended()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->create([
            'stage_id' => $stage->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()
        ]);

        self::assertTrue($round->hasEnded());
    }

    public function test_has_not_ended()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->create([
            'stage_id' => $stage->id,
            'starts_at' => now(),
            'ends_at' => now()->addDay()
        ]);

        self::assertFalse($round->hasEnded());
    }

}
