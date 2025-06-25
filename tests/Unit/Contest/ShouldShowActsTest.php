<?php


namespace Contest;

use App\Facades\ContestFacade;
use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShouldShowActsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_acts()
    {
        Act::truncate();
        self::assertFalse(ContestFacade::shouldShowActs());
    }

    public function test_no_acts_with_songs()
    {
        Act::factory(3)->create();
        self::assertFalse(ContestFacade::shouldShowActs());
    }

    public function test_acts_with_songs()
    {
        Act::factory(3)->withSong()->create();
        self::assertTrue(ContestFacade::shouldShowActs());
    }

}
