<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class ManualVoteShowTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/stages/%u/manual-vote';

    private Stage $stage;

    private Round $round;

    private array $song_ids;

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage    = Stage::factory()->createOne();
        $this->round    = Round::factory()->for($this->stage)->ended()->createOne();
        $this->song_ids = Song::factory(8)->withAct()->create()->pluck('id')->toArray();

        foreach ($this->song_ids as $song_id)
        {
            RoundSongs::create([
                'round_id' => $this->round->id,
                'song_id'  => $song_id,
            ]);
        }
    }

    public function test_as_guest(): void
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        self::assertTrue($this->stage->requiresManualVote());

        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('back/manual-vote-page')
                                                          ->has('stage')
                                                          ->has('rounds')
        );
    }

    #[Depends('test_as_user')]
    public function test_invalid_stage(): void
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_as_user')]
    public function test_no_manual_vote_required(): void
    {
        $stage = Stage::factory()->over()->withResults()->createOne();
        self::assertFalse($stage->requiresManualVote());

        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $stage->id));
        $response->assertRedirectToRoute('admin.stages');
    }

    #[Depends('test_as_user')]
    public function test_only_rounds_requiring_vote()
    {
        $stage                = Stage::factory()->createOne();
        $rounds_with_votes    = Round::factory(2)->for($stage)->ended()->withSongs()->withVotes()->withOutcomes()->create();
        $rounds_without_votes = Round::factory(2)->for($stage)->ended()->withSongs()->create();
        $rounds_without_votes->each(function (Round $round)
        {
            $round->songs->each(function (Song $song) use ($round)
            {
                RoundOutcome::factory()->for($round)->create([
                    'song_id'      => $song->id,
                    'first_votes'  => 0,
                    'second_votes' => 0,
                    'third_votes'  => 0,
                ]);
            });
        });

        $valid_rounds   = $rounds_without_votes->pluck('id');
        $invalid_rounds = $rounds_with_votes->pluck('id');

        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $stage->id));
        $response->assertOk();
        // Thanks to duck.ai for this test.
        $response->assertInertia(fn(Assert $page) => $page->component('back/manual-vote-page')
                                                          ->has('rounds', $valid_rounds->count(), fn(Assert $page) => $page->where('id', function (int $id) use ($valid_rounds, $invalid_rounds)
                                                          {
                                                              self::assertContains($id, $valid_rounds);
                                                              self::assertNotContains($id, $invalid_rounds);
                                                              return true;
                                                          })->etc() // no need to check other fields.
                                                          ));
    }
}
