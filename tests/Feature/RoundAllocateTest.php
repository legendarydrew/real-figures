<?php

namespace Tests\Feature;

use App\Exceptions\DataException;
use App\Facades\RoundAllocateFacade;
use App\Models\Round;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class RoundAllocateTest extends TestCase
{
    use DatabaseMigrations;

    private Stage                                    $stage;
    private \Illuminate\Database\Eloquent\Collection $songs;

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

        $rounds = Round::whereStageId($this->stage->id)->count();
        self::assertEquals(0, $rounds);
    }

    public function test_less_than_two_songs()
    {
        $this->expectException(DataException::class);
        RoundAllocateFacade::songs($this->stage, $this->songs->slice(0, 1));

        $rounds = Round::whereStageId($this->stage->id)->count();
        self::assertEquals(0, $rounds);
    }

    public function test_at_least_two_songs()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs->slice(0, 2));

        $rounds = Round::whereStageId($this->stage->id)->count();
        self::assertEquals(1, $rounds);
    }

    #[Depends('test_at_least_two_songs')]
    public function test_all_in_one_round()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs);

        $rounds = Round::whereStageId($this->stage->id)->get();
        self::assertEquals(1, $rounds->count());
        self::assertEquals('Final Round', $rounds->first()->title);

        self::assertEquals(8, $rounds->first()->songs->count());
    }

    #[Depends('test_at_least_two_songs')]
    public function test_multiple_rounds()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs, 4);

        $rounds = Round::whereStageId($this->stage->id)->get();
        self::assertEquals(2, $rounds->count());

        foreach ($rounds as $index => $round)
        {
            self::assertEquals('Round ' . ($index + 1), $round->title);
            self::assertEquals(4, $round->songs()->count());
        }
    }

    #[Depends('test_at_least_two_songs')]
    public function test_specific_start()
    {
        $round_start = new Carbon(fake()->dateTimeThisMonth());
        RoundAllocateFacade::songs($this->stage, $this->songs, null, $round_start);

        $round = Round::whereStageId($this->stage->id)->first();
        self::assertEquals($round_start, $round->starts_at);
    }

    #[Depends('test_multiple_rounds')]
    public function test_consecutive_rounds()
    {
        RoundAllocateFacade::songs($this->stage, $this->songs, 2);

        $rounds = Round::whereStageId($this->stage->id)->get();
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
