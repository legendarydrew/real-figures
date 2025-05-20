<?php

namespace Tests\Feature\Controllers\API\ContactMessages;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/messages';

    private array $all_message_ids;
    private array $delete_message_ids;

    protected function setUp(): void
    {
        parent::setUp();

        $this->all_message_ids    = ContactMessage::factory(8)->create()->pluck('id')->toArray();
        $this->delete_message_ids = fake()->randomElements($this->all_message_ids);
    }

    public function test_as_guest()
    {
        $response = $this->deleteJson(self::ENDPOINT, ['message_ids' => $this->delete_message_ids]);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->deleteJson(self::ENDPOINT, ['message_ids' => $this->delete_message_ids]);
        $response->assertRedirectToRoute('admin.contact');
    }

    #[Depends('test_as_user')]
    public function test_deletes_messages()
    {
        $this->actingAs($this->user)->deleteJson(self::ENDPOINT, ['message_ids' => $this->delete_message_ids]);
        $messages = ContactMessage::whereIn('id', $this->delete_message_ids)->get();
        self::assertCount(0, $messages);

        $messages = ContactMessage::whereNotIn('id', $this->delete_message_ids)->get();
        self::assertGreaterThan(0, $messages->count());
    }
}
