<?php

namespace Tests\Feature\Controllers\API\ContactMessages;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/messages/%u';

    private ContactMessage $message;


    protected function setUp(): void
    {
        parent::setUp();

        $this->message = ContactMessage::factory()->createOne();
    }

    public function test_as_guest()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->message->id));
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->message->id));
        $response->assertSuccessful();
    }

    #[Depends('test_as_user')]
    public function test_marks_message_as_read()
    {
        $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->message->id));
        $this->message->refresh();

        self::assertNotNull($this->message->read_at);
    }

    #[Depends('test_as_user')]
    public function test_already_read_message()
    {
        $this->message->update([
            'read_at' => now()
        ]);

        $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, $this->message->id));
        $this->message->refresh();

        self::assertNotNull($this->message->read_at);
    }

    #[Depends('test_as_user')]
    public function test_invalid_message()
    {
        $response = $this->actingAs($this->user)->putJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }
}
