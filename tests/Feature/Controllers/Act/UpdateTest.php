<?php

namespace Tests\Feature\Controllers\Act;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/acts/%u';

    private Act   $act;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act     = Act::factory()->createOne();
        $this->payload = [
            'name' => fake()->name
        ];
    }

    public function test_as_guest()
    {
        $response = $this->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertRedirectToRoute('admin.acts');
    }

    #[Depends('test_as_user')]
    public function test_updates_act()
    {
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertEquals($this->payload['name'], $this->act->name);
    }

    #[Depends('test_as_user')]
    public function test_invalid_act()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

    #[Depends('test_updates_act')]
    public function test_updates_existing_profile()
    {
        ActProfile::factory()->for($this->act)->createOne();
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->load('profile');
        self::assertEquals($this->payload['profile']['description'], $this->act->profile->description);
    }

    #[Depends('test_updates_act')]
    public function test_updates_and_creates_profile()
    {
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->load('profile');
        self::assertInstanceOf(ActProfile::class, $this->act->profile);
        self::assertEquals($this->payload['profile']['description'], $this->act->profile->description);
    }

    #[Depends('test_updates_act')]
    public function test_updates_and_deletes_profile()
    {
        ActProfile::factory()->for($this->act)->createOne();
        $this->payload['profile'] = null;
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->load('profile');
        self::assertNull($this->act->profile);
    }

}
