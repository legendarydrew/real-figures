<?php

namespace Tests\Song;

use App\Models\Song;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CanReceiveGoldenBuzzerTest extends TestCase
{
    use DatabaseMigrations;

    private Song $song;

    protected function setUp(): void
    {
        parent::setUp();
        $this->song = Song::factory()->withAct()->create();
    }

    public function test_when_false()
    {
        $this->song->setGoldenBuzzerStatus(false);
        self::assertFalse($this->song->canReceiveGoldenBuzzer());
    }

    public function test_when_true()
    {
        $this->song->setGoldenBuzzerStatus(true);
        self::assertTrue($this->song->canReceiveGoldenBuzzer());
    }
}
