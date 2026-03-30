<?php

namespace Tests\Feature\Controllers\Back\Dashboard;

use App\Models\Act;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\GoldenBuzzer;
use App\Models\Round;
use App\Models\Song;
use App\Models\Stage;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class DataTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        \Analytics::fake();
    }

    public function test_with_donations(): void
    {
        Donation::factory(10)->create();
        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->where('donations.count', 10));
    }

    public function test_with_golden_buzzers(): void
    {
        Stage::factory()->withRounds()->create();
        GoldenBuzzer::factory(10)->create();

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->where('donations.golden_buzzers', 10));
    }

    public function test_with_subscribers(): void
    {
        Subscriber::factory(10)->create();
        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->where('subscriber_count', 10));
    }

    public function test_with_messages(): void
    {
        ContactMessage::factory(10)->create();
        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->where('message_count', 10));
    }

    public function test_with_page_views_analytics(): void
    {
        \Analytics::fake(collect([
            ['date' => now()->subDay(), 'screenPageViews' => fake()->numberBetween(0, 100), 'activeUsers' => fake()->numberBetween(0, 200)],
        ]));
        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->has('analytics_views', 15));
    }

    public function test_with_song_plays_analytics(): void
    {
        $act = Act::factory()->createOne();
        Song::factory()->withPlays()->create([
            'act_id' => $act->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->has('analytics_views', 15));
    }

    public function test_with_votes_analytics(): void
    {
        $stage = Stage::factory()->createOne();
        Round::factory()->for($stage)->withSongs()->withVotes()->create();

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->has('votes', 8));
    }
}
