<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ManualVoteStoreTest extends TestCase
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

        $song_ids      = fake()->randomElements($this->song_ids, 3);
        $this->payload = [
            'votes' => [
                [
                    'round_id' => $this->round->id,
                    'song_ids' => [
                        'first'  => $song_ids[0],
                        'second' => $song_ids[1],
                        'third'  => $song_ids[2]
                    ],
                ]
            ]
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnauthorized();

        self::assertCount(0, $this->round->outcomes);
    }

    public function test_cast_manual_vote_when_required()
    {
        self::assertTrue($this->stage->requiresManualVote());

        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertRedirectToRoute('admin.stages');

        self::assertCount(count($this->song_ids), $this->round->outcomes);
    }

    public function test_cast_manual_vote_when_not_required()
    {
        $song_id = fake()->randomElement($this->song_ids);

        // a fake vote.
        RoundVote::create([
            'round_id'         => $this->round->id,
            'first_choice_id'  => $song_id,
            'second_choice_id' => $song_id,
            'third_choice_id'  => $song_id
        ]);

        // a fake outcome.
        $outcome = RoundOutcome::factory()->for($this->round)->create([
            'round_id'     => $this->round->id,
            'song_id'      => $song_id,
            'first_votes'  => $song_id,
            'second_votes' => $song_id,
            'third_votes'  => $song_id
        ]);

        self::assertFalse($this->stage->requiresManualVote());
        self::assertFalse($this->round->requiresManualVote());

        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertRedirectToRoute('admin.stages');

        self::assertCount(1, $this->round->outcomes); // the fake outcome created before.
    }

    public function test_cast_manual_vote_for_invalid_stage()
    {
        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();

        self::assertCount(0, $this->round->outcomes);

    }

    public function test_cast_manual_vote_for_invalid_round()
    {
        $this->payload['votes'][0]['round_id'] = 404;

        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnprocessable();

        self::assertCount(0, $this->round->outcomes);
    }

    public function test_cast_manual_vote_for_non_existent_song()
    {
        $this->payload['votes'][0]['song_ids']['first'] = 404;

        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnprocessable();

        self::assertCount(0, $this->round->outcomes);
    }

    public function test_cast_manual_vote_for_invalid_song()
    {
        $this->payload['votes'][0]['song_ids']['first'] = Song::factory()->withAct()->create()->id;

        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertBadRequest();

        self::assertCount(0, $this->round->outcomes);
    }

    public function test_manual_vote_creates_outcomes()
    {
        $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);

        self::assertTrue($this->round->outcomes->every(fn($outcome) => $outcome->was_manual));

        $outcomes = $this->round->outcomes;
        self::assertCount($this->round->songs->count(), $outcomes);

        // TODO Ensure the Songs received the correct votes.
        // This involves checking the outcomes for the correct number of first, second and third choice votes
        // for each Song.
        /*
         $first_choices  = array_count_values($votes->pluck('first_choice_id')->toArray());
         $second_choices = array_count_values($votes->pluck('second_choice_id')->toArray());
         $third_choices  = array_count_values($votes->pluck('third_choice_id')->toArray());
        */
    }
}
