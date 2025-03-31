<?php

namespace Tests\Unit\Relations;

use App\Models\Act;
use App\Models\ActProfile;
use App\Models\Song;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActTest extends TestCase
{
    use DatabaseMigrations;

    public function test_songs_relation()
    {
        $act = Act::factory()->withSong()->create();
        self::assertEquals(1, $act->songs()->count());
        foreach ($act->songs as $song)
        {
            self::assertInstanceOf(Song::class, $song);
        }
    }

    public function test_profile_relation()
    {
        $act = Act::factory()->withProfile()->create();
        self::assertInstanceOf(ActProfile::class, $act->profile);
    }
}
