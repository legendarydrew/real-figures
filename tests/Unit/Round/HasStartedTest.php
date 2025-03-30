<?php

namespace Round;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class HasStartedTest extends TestCase
{
    use DatabaseMigrations;

    public function test_has_started()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->create([
            'stage_id' => $stage->id,
            'starts_at' => now(),
            'ends_at' => now()->addDay()
        ]);

        self::assertTrue($round->hasStarted());
    }

    public function test_has_not_started()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->create([
            'stage_id' => $stage->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDays(2)
        ]);

        self::assertFalse($round->hasStarted());
    }

}
