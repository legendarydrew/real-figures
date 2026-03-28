<?php

namespace Tests\Feature\Controllers\API;

use App\Models\Act;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class VoteTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/vote';

    protected function setUp(): void
    {
        parent::setUp();
        $stage       = Stage::factory()->create();
        $this->round = Round::factory()->withSongs(4)->create([
            'stage_id'  => $stage->id,
            'starts_at' => now(),
            'ends_at' => now()->addDay(),
        ]);
        $this->songs = $this->round->songs;

        $this->payload = [
            'round_id' => $this->round->id,
            'starts_at' => now(),
            'ends_at' => now()->addDay(),
            'first_choice_id' => $this->songs->get(0)->id,
            'second_choice_id' => $this->songs->get(1)->id,
            'third_choice_id' => $this->songs->get(2)->id,
        ];
    }

    public final function test_request(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();
    }

    #[Depends('test_request')]
    public final function test_casts_vote(): void
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

    #[Depends('test_request')]
    public final function test_invalid_round(): void
    {
        $this->payload['round_id'] = 404;
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();
    }

    #[Depends('test_request')]
    public final function test_expired_round(): void
    {
        $this->round->update(['ends_at' => now()]);
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    #[Depends('test_request')]
    public final function test_later_round(): void
    {
        $this->round->update([
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDays(2),
        ]);
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    #[Depends('test_request')]
    public final function test_no_songs(): void
    {
        // First song.
        $payload  = [...$this->payload, 'first_choice_id' => null, 'second_choice_id' => null, 'third_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    #[Depends('test_request')]
    public final function test_invalid_song(): void
    {
        // First song.
        $payload = [...$this->payload, 'first_choice_id' => 404];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Second song.
        $payload = [...$this->payload, 'second_choice_id' => 404];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Third song.
        $payload = [...$this->payload, 'third_choice_id' => 404];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    #[Depends('test_request')]
    public final function test_same_songs(): void
    {
        // First song.
        $payload  = [...$this->payload, 'second_choice_id' => $this->payload['first_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [...$this->payload, 'third_choice_id' => $this->payload['first_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Second song.
        $payload  = [...$this->payload, 'first_choice_id' => $this->payload['second_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [...$this->payload, 'third_choice_id' => $this->payload['second_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        // Third song.
        $payload  = [...$this->payload, 'first_choice_id' => $this->payload['third_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [...$this->payload, 'second_choice_id' => $this->payload['third_choice_id']];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    #[Depends('test_request')]
    public final function test_song_not_in_round(): void
    {
        $act = Act::factory()->withSong()->create();
        $new_song = $act->songs()->first();

        // First song.
        $payload = [...$this->payload, 'first_choice_id' => $new_song->id];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertBadRequest();

        // Second song.
        $payload = [...$this->payload, 'second_choice_id' => $new_song->id];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertBadRequest();

        // Third song.
        $payload = [...$this->payload, 'third_choice_id' => $new_song->id];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertBadRequest();
    }

    #[Depends('test_request')]
    public final function test_one_song(): void
    {
        $payload  = [...$this->payload, 'first_choice_id' => null, 'second_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [...$this->payload, 'second_choice_id' => null, 'third_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertCreated();

        $payload  = [...$this->payload, 'first_choice_id' => null, 'third_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();
    }

    #[Depends('test_request')]
    public final function test_two_songs(): void
    {
        $payload  = [...$this->payload, 'first_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertUnprocessable();

        $payload  = [...$this->payload, 'second_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertCreated();

        $payload  = [...$this->payload, 'third_choice_id' => null];
        $response = $this->postJson(self::ENDPOINT, $payload);
        $response->assertCreated();
    }

}
