<?php

namespace Controllers\Front\Subscriber;

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
        $url        = sprintf(self::ENDPOINT, $subscriber->id, $subscriber->confirmation_code);
        $response   = $this->get($url);
        $response->assertRedirectToRoute('home');

        $this->expectException(ModelNotFoundException::class);
        $subscriber->refresh();

        Mail::assertNothingSent();
    }

    public function test_remove_valid_confirmed_subscriber()
    {
        $subscriber = Subscriber::factory()->confirmed()->create();

        $url      = sprintf(self::ENDPOINT, $subscriber->id, $subscriber->confirmation_code);
        $response = $this->get($url);
        $response->assertRedirectToRoute('home');

        $this->expectException(ModelNotFoundException::class);
        $subscriber->refresh();

        Mail::assertNothingSent();
    }

    public function test_invalid_id()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = sprintf(self::ENDPOINT, 404, $subscriber->confirmation_code);
        $response   = $this->get($url);
        $response->assertRedirectToRoute('home');

        $subscriber->refresh();
        self::assertInstanceOf(Subscriber::class, $subscriber);

        Mail::assertNothingSent();
    }

    public function test_invalid_code()
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url        = sprintf(self::ENDPOINT, $subscriber->id, '404');
        $response   = $this->get($url);
        $response->assertRedirectToRoute('home');

        $subscriber->refresh();
        self::assertInstanceOf(Subscriber::class, $subscriber);

        Mail::assertNothingSent();
    }

    public function test_no_id()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = sprintf(self::ENDPOINT, '', $subscriber->confirmation_code);
        $response   = $this->get($url);
        $response->assertRedirectToRoute('home');

        $subscriber->refresh();
        self::assertInstanceOf(Subscriber::class, $subscriber);
    }

    public function test_no_code()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = sprintf(self::ENDPOINT, $subscriber->id, '');
        $response   = $this->get($url);
        $response->assertNotFound();

        $subscriber->refresh();
        self::assertInstanceOf(Subscriber::class, $subscriber);
    }
}
