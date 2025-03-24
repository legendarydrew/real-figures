<?php

namespace Unit\Relations;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActProfileTest extends TestCase
{
    use DatabaseMigrations;

    public function test_act_relation()
    {
        $act = Act::factory()->create();
        $act_profile = ActProfile::factory()->create([
            'act_id' => $act->id,
        ]);
        self::assertInstanceOf(Act::class, $act_profile->act);
    }
}
