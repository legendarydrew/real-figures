<?php

namespace Tests\Feature\Controllers\Stage;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ManualVoteTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = 'api/stages/%u/manual-vote';

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

        // a fake outcome.
        $outcome = RoundOutcome::factory()->for($this->round)->create([
            'round_id'     => $this->round->id,
            'song_id'      => $song_id,
            'first_votes'  => $song_id,
            'second_votes' => $song_id,
            'third_votes'  => $song_id
        ]);

        self::assertFalse($this->stage->requiresManualVote());

        $response = $this->actingAs($this->user)->postJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertRedirectToRoute('admin.stages');

        $outcome->delete();

        self::assertCount(0, $this->round->outcomes);
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

        $outcome = $this->round->outcomes->first(fn($outcome) => $outcome->song_id === $this->payload['votes'][0]['song_ids']['first']);
        self::assertEquals(1, $outcome->first_votes);
        self::assertEquals(0, $outcome->second_votes);
        self::assertEquals(0, $outcome->third_votes);

        $outcome = $this->round->outcomes->first(fn($outcome) => $outcome->song_id === $this->payload['votes'][0]['song_ids']['second']);
        self::assertEquals(0, $outcome->first_votes);
        self::assertEquals(1, $outcome->second_votes);
        self::assertEquals(0, $outcome->third_votes);

        $outcome = $this->round->outcomes->first(fn($outcome) => $outcome->song_id === $this->payload['votes'][0]['song_ids']['third']);
        self::assertEquals(0, $outcome->first_votes);
        self::assertEquals(0, $outcome->second_votes);
        self::assertEquals(1, $outcome->third_votes);

        $picked   = [$this->payload['votes'][0]['song_ids']['first'], $this->payload['votes'][0]['song_ids']['second'], $this->payload['votes'][0]['song_ids']['third']];
        $outcomes = $this->round->outcomes->filter(fn($outcome) => !in_array($outcome->song_id, $picked));
        foreach ($outcomes as $outcome)
        {
            self::assertEquals(0, $outcome->first_votes);
            self::assertEquals(0, $outcome->second_votes);
            self::assertEquals(0, $outcome->third_votes);
        }
    }
}
