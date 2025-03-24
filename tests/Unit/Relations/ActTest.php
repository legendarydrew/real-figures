<?php

namespace Unit\Relations;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActTest extends TestCase
{
    use DatabaseMigrations;

    public function test_songs_relation()
    {
        $act = Act::factory()->withSong()->create();
        self::assertGreaterThan(0, $act->songs()->count());
    }

    public function test_profile_relation()
    {
        $act = Act::factory()->withProfile()->create();
        self::assertInstanceOf(ActProfile::class, $act->profile);
    }
}
