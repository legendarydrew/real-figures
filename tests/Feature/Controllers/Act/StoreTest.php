<?php

namespace Tests\Feature\Controllers\Act;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/acts';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'name' => fake()->name
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
        $response->assertRedirectToRoute('admin.acts');
    }

    #[Depends('test_as_user')]
    public function test_creates_post_without_profile()
    {
        $this->payload['profile'] = null;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($act->name, $this->payload['name']);
        self::assertNull($act->profile);
    }

    #[Depends('test_as_user')]
    public function test_creates_post_with_profile()
    {
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($act->name, $this->payload['name']);
        self::assertInstanceOf(ActProfile::class, $act->profile);
    }

}
