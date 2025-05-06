<?php

namespace Controllers\Subscribers;

use App\Models\ContactMessage;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = 'api/subscribers';

    private array $all_ids;
    private array $delete_ids;

    protected function setUp(): void
    {
        parent::setUp();

        $this->all_ids    = Subscriber::factory(8)->create()->pluck('id')->toArray();
        $this->delete_ids = fake()->randomElements($this->all_ids);
    }

    public function test_as_guest()
    {
        $response = $this->deleteJson(self::ENDPOINT, ['subscriber_ids' => $this->delete_ids]);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->deleteJson(self::ENDPOINT, ['subscriber_ids' => $this->delete_ids]);
        $response->assertRedirectToRoute('admin.subscribers');
    }

    #[Depends('test_as_user')]
    public function test_deletes_subscribers()
    {
        $this->actingAs($this->user)->deleteJson(self::ENDPOINT, ['subscriber_ids' => $this->delete_ids]);
        $subscribers = Subscriber::whereIn('id', $this->delete_ids)->get();
        self::assertCount(0, $subscribers);

        $subscribers = Subscriber::whereNotIn('id', $this->delete_ids)->get();
        self::assertGreaterThan(0, $subscribers->count());
    }
}
