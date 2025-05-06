<?php

namespace Controllers\Subscribers;

use App\Mail\SubscriberConfirm;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = 'api/subscribers';

    private array $payload;


    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->payload = [
            'email' => fake()->email(),
        ];
    }

    public function test_creates_subscriber()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();

        $subscriber = Subscriber::whereEmail($this->payload['email'])->first();
        self::assertInstanceOf(Subscriber::class, $subscriber);
        self::assertNotNull($subscriber->confirmation_code);
        self::assertFalse((bool)$subscriber->confirmed);

        Mail::assertSent(SubscriberConfirm::class, fn(SubscriberConfirm $mail) => $mail->hasTo($this->payload['email']));
    }

    public function test_invalid_email()
    {
        $this->payload['email'] = fake()->word();
        $response               = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();

        $subscriber = Subscriber::whereEmail($this->payload['email'])->first();
        self::assertNull($subscriber);

        Mail::assertNothingSent();
    }

    public function test_existing_unconfirmed_email()
    {
        Subscriber::factory()->unconfirmed()->createOne(['email' => $this->payload['email']]);

        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();

        $subscriber = Subscriber::whereEmail($this->payload['email'])->first();
        self::assertInstanceOf(Subscriber::class, $subscriber);
        self::assertNotNull($subscriber->confirmation_code);
        self::assertFalse((bool)$subscriber->confirmed);

        Mail::assertSent(SubscriberConfirm::class, fn(SubscriberConfirm $mail) => $mail->hasTo($this->payload['email']));
    }

    public function test_existing_confirmed_email()
    {
        Subscriber::factory()->confirmed()->createOne(['email' => $this->payload['email']]);

        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();

        $subscriber = Subscriber::whereEmail($this->payload['email'])->first();
        self::assertInstanceOf(Subscriber::class, $subscriber);
        self::assertNotNull($subscriber->confirmation_code);
        self::assertTrue((bool)$subscriber->confirmed);

        Mail::assertNothingSent();
    }
}
