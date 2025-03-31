<?php

namespace Tests\Feature\RoundAllocate;

use App\Exceptions\DataException;
use App\Facades\RoundAllocateFacade;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class SongsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->create();
        $this->songs = Song::factory(8)->withAct()->create();
    }

    public function test_no_songs()
    {
        $this->expectException(DataException::class);
        RoundAllocateFacade::songs($this->stage, new Collection());
    }

    public function test_all_in_one_round()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs);

        self::assertEquals(1, Round::count());
        self::assertEquals(8, RoundSongs::count());
    }

    public function test_multiple_rounds()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs, 4);

        self::assertEquals(2, Round::count());
        self::assertEquals(8, RoundSongs::count());
    }

    public function test_specific_start()
    {
        $round_start = new Carbon(fake()->dateTimeThisMonth());
        RoundAllocateFacade::songs($this->stage, $this->songs, null, $round_start);

        $round = Round::first();
        self::assertEquals($round_start, $round->starts_at);
    }

    #[Depends('test_multiple_rounds')]
    public function test_consecutive_rounds()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs, 2);

        $rounds     = Round::get();
        $last_round = null;
        foreach ($rounds as $round)
        {
            if ($last_round)
            {
                self::assertGreaterThan($last_round->starts_at, $round->starts_at);
                self::assertGreaterThan($last_round->ends_at, $round->starts_at);
            }
            $last_round = $round;
        }
    }

}
