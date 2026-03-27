<?php

namespace Tests\Feature\Controllers\API\Subscribers;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/subscribers';

    private array $all_ids;

    private array $delete_ids;

    protected function setUp(): void
    {
        parent::setUp();

        $this->all_ids = Subscriber::factory(8)->create()->pluck('id')->toArray();
        $this->delete_ids = fake()->randomElements($this->all_ids);
    }

    public function test_as_guest(): void
    {
        $response = $this->deleteJson(self::ENDPOINT, ['subscriber_ids' => $this->delete_ids]);
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->deleteJson(self::ENDPOINT, ['subscriber_ids' => $this->delete_ids]);
        $response->assertRedirectToRoute('admin.subscribers');
    }

    #[Depends('test_as_user')]
    public function test_deletes_subscribers(): void
    {
        $this->actingAs($this->user)->deleteJson(self::ENDPOINT, ['subscriber_ids' => $this->delete_ids]);
        $subscribers = Subscriber::whereIn('id', $this->delete_ids)->get();
        self::assertCount(0, $subscribers);

        $subscribers = Subscriber::whereNotIn('id', $this->delete_ids)->get();
        self::assertGreaterThan(0, $subscribers->count());
    }
}
