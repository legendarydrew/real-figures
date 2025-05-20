<?php

namespace Tests\Unit\Stage;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HasEndedTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->create();
    }

    public function test_no_rounds()
    {
        self::assertCount(0, $this->stage->rounds);
        self::assertFalse($this->stage->hasEnded());
    }

    public function test_has_all_ended_rounds()
    {
        Round::factory(3)
            ->for($this->stage)
             ->create([
                 'starts_at' => now()->subDay(),
                 'ends_at'   => now()
             ]);

        $this->stage->load('rounds');
        self::assertCount(3, $this->stage->rounds);
        self::assertTrue($this->stage->hasEnded());
    }

    public function test_has_ongoing_rounds()
    {
        Round::factory()
            ->for($this->stage)
             ->create([
                 'starts_at' => now()->addDay(),
                 'ends_at'   => now()->addDays(2)
             ]);
        Round::factory(2)
             ->for($this->stage)
             ->create();

        $this->stage->load('rounds');
        self::assertCount(3, $this->stage->rounds);
        self::assertFalse($this->stage->hasEnded());
    }

}
