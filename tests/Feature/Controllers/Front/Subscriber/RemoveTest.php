<?php

namespace Tests\Feature\Controllers\Front\Subscriber;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RemoveTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'subscriber/remove/%u/%s';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_remove_valid_unconfirmed_subscriber()
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url        = route('subscriber.remove', ['id' => $subscriber->id, 'code' => $subscriber->confirmation_code]);
        $response   = $this->get($url);
        $response->assertOk();
        $response->assertViewIs('front.subscriber-removed');

        $this->expectException(ModelNotFoundException::class);
        $subscriber->refresh();

        Mail::assertNothingSent();
    }

    public function test_remove_valid_confirmed_subscriber()
    {
        $subscriber = Subscriber::factory()->confirmed()->create();

        $url        = route('subscriber.remove', ['id' => $subscriber->id, 'code' => $subscriber->confirmation_code]);
        $response = $this->get($url);
        $response->assertOk();
        $response->assertViewIs('front.subscriber-removed');

        $this->expectException(ModelNotFoundException::class);
        $subscriber->refresh();

        Mail::assertNothingSent();
    }

    public function test_invalid_id()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = route('subscriber.remove', ['id' => 404, 'code' => $subscriber->confirmation_code]);
        $response   = $this->get($url);
        $response->assertNotFound();

        $subscriber->refresh();
        self::assertInstanceOf(Subscriber::class, $subscriber);

        Mail::assertNothingSent();
    }

    public function test_invalid_code()
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url        = route('subscriber.remove', ['id' => $subscriber->id, 'code' => 404]);
        $response   = $this->get($url);
        $response->assertNotFound();

        $subscriber->refresh();
        self::assertInstanceOf(Subscriber::class, $subscriber);

        Mail::assertNothingSent();
    }

}
