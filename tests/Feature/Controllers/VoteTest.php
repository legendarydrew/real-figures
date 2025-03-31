<?php

namespace Tests\Feature\Controllers;

use App\Models\Act;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\Song;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/vote';

    protected function setUp(): void
    {
        parent::setUp();
        $act         = Act::factory()->create();
        $this->songs = Song::factory()->count(3)->create(['act_id' => $act->id]);

        $stage       = Stage::factory()->create();
        $this->round = Round::factory()->create([
            'stage_id'  => $stage->id,
            'starts_at' => now(),
            'ends_at'   => now()->addDay(),
        ]);

        RoundSongs::create([
            'round_id' => $this->round->id,
            'song_id'  => $this->songs->get(0)->id
        ]);
        RoundSongs::create([
            'round_id' => $this->round->id,
            'song_id'  => $this->songs->get(1)->id
        ]);
        RoundSongs::create([
            'round_id' => $this->round->id,
            'song_id'  => $this->songs->get(2)->id
        ]);

        $this->payload = [
            'round_id'         => $this->round->id,
            'starts_at'        => now(),
            'ends_at'          => now()->addDay(),
            'first_choice_id'  => $this->songs->get(0)->id,
            'second_choice_id' => $this->songs->get(1)->id,
            'third_choice_id'  => $this->songs->get(2)->id
        ];
    }

    /**
     * A basic feature test example.
     */
    public function test_request(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();
    }

    public function test_casts_vote()
    {
        self::assertEquals(0, $this->round->votes()->count());
        $this->postJson(self::ENDPOINT, $this->payload);
        $this->round->refresh();
        self::assertEquals(1, $this->round->votes()->count());

        $vote = $this->round->votes()->first();
        self::assertEquals($this->songs->get('0')->id, $vote->first_choice_id);
        self::assertEquals($this->songs->get('1')->id, $vote->second_choice_id);
        self::assertEquals($this->songs->get('2')->id, $vote->third_choice_id);
    }

    public function test_invalid_round()
    {
        $this->payload['round_id'] = 404;
        $response                  = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();
    }

    public function test_expired_round()
    {
        $this->round->update(['ends_at' => now()]);
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    public function test_later_round()
    {
        $this->round->update([
            'starts_at' => now()->addDay(),
            'ends_at'   => now()->addDays(2)
        ]);
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    public function test_missing_song()
    {
        // First song.
        $payload  = [...$this->payload, 'first_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Second song.
        $payload  = [...$this->payload, 'second_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Third song.
        $payload  = [...$this->payload, 'third_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    public function test_invalid_song()
    {
        // First song.
        $payload  = [...$this->payload, 'first_choice_id' => 404];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Second song.
        $payload  = [...$this->payload, 'second_choice_id' => 404];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Third song.
        $payload  = [...$this->payload, 'third_choice_id' => 404];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    public function test_same_songs()
    {
        // First song.
        $payload  = [ ...$this->payload, 'second_choice_id' => $this->payload['first_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [ ...$this->payload, 'third_choice_id' => $this->payload['first_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Second song.
        $payload  = [ ...$this->payload, 'first_choice_id' => $this->payload['second_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [ ...$this->payload, 'third_choice_id' => $this->payload['second_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Third song.
        $payload  = [ ...$this->payload, 'first_choice_id' => $this->payload['third_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [ ...$this->payload, 'second_choice_id' => $this->payload['third_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    public function test_song_not_in_round()
    {
        $act      = Act::factory()->withSong()->create();
        $new_song = $act->songs()->first();

        // First song.
        $payload  = [...$this->payload, 'first_choice_id' => $new_song->id];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertBadRequest();

        // Second song.
        $payload  = [...$this->payload, 'second_choice_id' => $new_song->id];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertBadRequest();

        // Third song.
        $payload  = [...$this->payload, 'third_choice_id' => $new_song->id];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertBadRequest();
    }
}
