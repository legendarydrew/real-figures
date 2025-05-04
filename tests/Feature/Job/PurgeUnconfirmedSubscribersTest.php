<?php

namespace Job;

use App\Jobs\PurgeUnconfirmedSubscribers;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PurgeUnconfirmedSubscribersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_purge_subscribers_before_time()
    {
        Subscriber::factory()->count(5)->confirmed()->create();
        Subscriber::factory()->count(5)->unconfirmed()->create();

        self::travel(1)->second();
        PurgeUnconfirmedSubscribers::dispatch();

        $subscribers = Subscriber::all();
        self::assertCount(10, $subscribers);
    }

    public function test_purge_subscribers_after_time()
    {
        Subscriber::factory()->count(5)->confirmed()->create([
            'created_at' => now()->subDay()
        ]);
        Subscriber::factory()->count(5)->unconfirmed()->create([
            'created_at' => now()->subDay()
        ]);

        self::travel(1)->second();
        PurgeUnconfirmedSubscribers::dispatch();

        $subscribers = Subscriber::all();
        self::assertCount(5, $subscribers);
        self::assertTrue($subscribers->every(fn(Subscriber $subscriber) => $subscriber->confirmed));
    }
}
