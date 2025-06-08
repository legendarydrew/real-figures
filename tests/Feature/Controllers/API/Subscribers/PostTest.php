<?php

namespace Tests\Feature\Controllers\API\Subscribers;

use App\Mail\SubscriberPostMessage;
use App\Models\Subscriber;
use App\Models\SubscriberPost;
use DavidBadura\FakerMarkdownGenerator\FakerProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/subscribers/post';

    private array $payload;


    protected function setUp(): void
    {
        parent::setUp();
        fake()->addProvider(FakerProvider::class);

        Mail::fake();

        $this->payload = [
            'title' => fake()->sentence(),
            'body'  => fake()->markdown(),
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();
    }

    #[Depends('test_as_user')]
    public function test_creates_subscriber_post()
    {
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $post = SubscriberPost::first();
        self::assertInstanceOf(SubscriberPost::class, $post);
        self::assertEquals($this->user->id, $post->user_id);
        self::assertEquals($this->payload['title'], $post->title);
        self::assertEquals($this->payload['body'], $post->body);
    }

    #[Depends('test_as_user')]
    public function test_without_subscribers()
    {
        self::assertEquals(0, Subscriber::count());
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertJsonPath('subscribers', 0);
        Mail::assertNothingOutgoing();
    }

    #[Depends('test_as_user')]
    public function test_with_subscribers()
    {
        Subscriber::factory()->count(10)->confirmed()->create();
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertJsonPath('subscribers', 10);
        Mail::assertSent(SubscriberPostMessage::class);
        Mail::assertOutgoingCount(10);
    }
}
