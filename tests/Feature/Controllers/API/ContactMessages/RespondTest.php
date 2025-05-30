<?php

namespace Tests\Feature\Controllers\API\ContactMessages;

use App\Mail\ContactMessageResponse;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class RespondTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/messages/%u/respond';

    private ContactMessage $message;

    private array $payload;


    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->message = ContactMessage::factory()->createOne();
        $this->payload = [
            'response' => fake()->realText()
        ];
    }

    public function test_as_guest()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->message->id), $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->message->id), $this->payload);
        $response->assertSuccessful();
    }

    #[Depends('test_as_user')]
    public function test_sends_mail()
    {
        $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->message->id), $this->payload);
        Mail::assertSent(ContactMessageResponse::class, function (ContactMessageResponse $mail)
        {
            return $mail->hasTo($this->message->email) && $mail->viewData['response'] === $this->payload['response'];
        });

    }
}
