<?php

namespace Tests\Feature\Controllers\API\ContactMessages;

use App\Models\ContactMessage;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelPackageTools\Concerns\Package\HasInertia;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;
    use HasInertia;

    protected const string ENDPOINT = 'api/messages';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'body' => fake()->paragraph(),
            'token' => fake()->uuid(),
        ];
    }

    public function test_successful_verify(): void
    {
        // https://stackoverflow.com/a/72342214/4073160
        // https://laravel.com/docs/9.x/http-client#faking-specific-urls
        Http::fake([
            'https://challenges.cloudflare.com/*' => Http::response(['success' => true]),
        ]);

        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertSuccessful();

        $message = ContactMessage::whereEmail($this->payload['email'])->first();
        self::assertInstanceOf(ContactMessage::class, $message);
        self::assertEquals($this->payload['name'], $message->name);
        self::assertEquals($this->payload['body'], $message->body);
        self::assertFalse((bool) $message->is_spam);
    }

    public function test_failed_verify(): void
    {
        Http::fake([
            'https://challenges.cloudflare.com/*' => Http::response(['success' => false]),
        ]);

        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertSuccessful();

        $message = ContactMessage::whereEmail($this->payload['email'])->first();
        self::assertInstanceOf(ContactMessage::class, $message);
        self::assertEquals($this->payload['name'], $message->name);
        self::assertEquals($this->payload['body'], $message->body);
        self::assertTrue((bool) $message->is_spam);
    }

    public function test_subscribe_false(): void
    {
        Http::fake([
            'https://challenges.cloudflare.com/*' => Http::response(['success' => true]),
        ]);

        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertSuccessful();

        $subscription = Subscriber::whereEmail($this->payload['email'])->first();
        self::assertNull($subscription);

        $this->payload['subscribe'] = false;
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertSuccessful();

        $subscription = Subscriber::whereEmail($this->payload['email'])->first();
        self::assertNull($subscription);
    }

    public function test_subscribe_true(): void
    {
        Http::fake([
            'https://challenges.cloudflare.com/*' => Http::response(['success' => true]),
        ]);

        $this->payload['subscribe'] = true;
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertSuccessful();

        $subscription = Subscriber::whereEmail($this->payload['email'])->first();

        self::assertInstanceOf(Subscriber::class, $subscription);
        self::assertFalse((bool) $subscription->confirmed);
    }

    public function test_already_subscribed(): void
    {
        Http::fake([
            'https://challenges.cloudflare.com/*' => Http::response(['success' => true]),
        ]);

        $this->payload['subscribe'] = true;
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertSuccessful();

        $subscription = Subscriber::whereEmail($this->payload['email'])->first();

        self::assertInstanceOf(Subscriber::class, $subscription);
    }
}
