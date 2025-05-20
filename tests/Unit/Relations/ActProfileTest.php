<?php

namespace Tests\Unit\Relations;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $act = Act::factory()->create();
        $this->act_profile = ActProfile::factory()->create([
            'act_id' => $act->id,
        ]);
    }

    public function test_act_relation()
    {
        self::assertInstanceOf(Act::class, $this->act_profile->act);
    }
}
