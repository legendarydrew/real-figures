<?php

namespace Tests\Unit\Round;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IsActiveTest extends TestCase
{
    use DatabaseMigrations;

    public function test_before_start_date()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->for($stage)
                      ->create([
                          'starts_at' => now()->addDay(),
                          'ends_at'   => now()->addDays(2)
                      ]);

        self::assertFalse($round->isActive());
    }

    public function test_after_start_date()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->for($stage)
                      ->create([
                          'starts_at' => now(),
                          'ends_at'   => now()->addDay()
                      ]);

        self::assertTrue($round->isActive());
    }

    public function test_after_end_date()
    {
        $stage = Stage::factory()->create();
        $round = Round::factory()->for($stage)
                      ->create([
                          'starts_at' => now()->subDay(),
                          'ends_at'   => now()
                      ]);

        self::assertFalse($round->isActive());
    }

}
