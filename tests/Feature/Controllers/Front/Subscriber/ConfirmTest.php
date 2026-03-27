<?php

namespace Tests\Feature\Controllers\Front\Subscriber;

use App\Mail\SubscriberConfirmation;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class ConfirmTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'subscriber/confirm/%u/%s';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_confirm_unconfirmed_subscriber(): void
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url = route('subscriber.confirm', ['id' => $subscriber->id, 'code' => $subscriber->confirmation_code]);
        $response = $this->get($url);
        $response->assertOk();
        $response->assertViewIs('front.subscriber-confirmed');

        $subscriber->refresh();
        self::assertTrue((bool) $subscriber->confirmed);

        Mail::assertSent(SubscriberConfirmation::class, function (Mailable $mail) use ($subscriber) {
            return $mail->hasTo($subscriber->email);
        });
    }

    public function test_confirm_confirmed_subscriber(): void
    {
        $subscriber = Subscriber::factory()->confirmed()->create();

        $url = route('subscriber.confirm', ['id' => $subscriber->id, 'code' => $subscriber->confirmation_code]);
        $response = $this->get($url);
        $response->assertOk();
        $response->assertViewIs('front.subscriber-confirmed');

        $subscriber->refresh();
        self::assertTrue((bool) $subscriber->confirmed);

        Mail::assertNothingSent();
    }

    public function test_invalid_id(): void
    {
        $subscriber = Subscriber::factory()->create();
        $url = route('subscriber.confirm', ['id' => 404, 'code' => $subscriber->confirmation_code]);
        $response = $this->get($url);
        $response->assertNotFound();
    }

    public function test_invalid_code(): void
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url = route('subscriber.confirm', ['id' => $subscriber->id, 'code' => 404]);
        $response = $this->get($url);
        $response->assertNotFound();

        $subscriber->refresh();
        self::assertFalse((bool) $subscriber->confirmed);
    }
}
