<?php

namespace Tests\Unit\Stage;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HasStartedTest extends TestCase
{
    use DatabaseMigrations;

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->create();
    }

    public function test_no_rounds()
    {
        self::assertCount(0, $this->stage->rounds);
        self::assertFalse($this->stage->hasStarted());
    }

    public function test_has_started_rounds()
    {
        Round::factory(3)
             ->for($this->stage)
             ->create([
                 'starts_at' => now(),
                 'ends_at'   => now()->addDay()
             ]);

        self::assertCount(3, $this->stage->rounds);
        self::assertTrue($this->stage->hasStarted());
    }

    public function test_has_no_started_rounds()
    {
        Round::factory(3)
             ->for($this->stage)
             ->create([
                 'starts_at' => now()->addDay(),
                 'ends_at'   => now()->addDays(2)
             ]);

        self::assertCount(3, $this->stage->rounds);
        self::assertFalse($this->stage->hasStarted());
    }

}
