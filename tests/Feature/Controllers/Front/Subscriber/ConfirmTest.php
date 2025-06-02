<?php

namespace Tests\Feature\Controllers\Front\Subscriber;

use App\Mail\SubscriberConfirmation;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'subscriber/confirm/%u/%s';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_confirm_unconfirmed_subscriber()
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url        = sprintf(self::ENDPOINT, $subscriber->id, $subscriber->confirmation_code);
        $response   = $this->get($url);
        $response->assertRedirectToRoute('home');

        $subscriber->refresh();
        self::assertTrue((bool)$subscriber->confirmed);

        Mail::assertSent(SubscriberConfirmation::class, function (Mailable $mail) use ($subscriber)
        {
            return $mail->hasTo($subscriber->email);
        });
    }

    public function test_confirm_confirmed_subscriber()
    {
        $subscriber = Subscriber::factory()->confirmed()->create();

        $url      = sprintf(self::ENDPOINT, $subscriber->id, $subscriber->confirmation_code);
        $response = $this->get($url);
        $response->assertRedirectToRoute('home');

        $subscriber->refresh();
        self::assertTrue((bool)$subscriber->confirmed);

        Mail::assertNothingSent();
    }

    public function test_invalid_id()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = sprintf(self::ENDPOINT, 404, $subscriber->confirmation_code);
        $response   = $this->get($url);
        $response->assertNotFound();
    }

    public function test_invalid_code()
    {
        $subscriber = Subscriber::factory()->unconfirmed()->create();
        $url        = sprintf(self::ENDPOINT, $subscriber->id, '404');
        $response   = $this->get($url);
        $response->assertNotFound();

        $subscriber->refresh();
        self::assertFalse((bool)$subscriber->confirmed);
    }

    public function test_no_id()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = sprintf(self::ENDPOINT, '', $subscriber->confirmation_code);
        $response   = $this->get($url);
        $response->assertNotFound();
    }

    public function test_no_code()
    {
        $subscriber = Subscriber::factory()->create();
        $url        = sprintf(self::ENDPOINT, $subscriber->id, '');
        $response   = $this->get($url);
        $response->assertNotFound();
    }
}
