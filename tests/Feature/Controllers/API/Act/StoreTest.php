<?php

namespace Tests\Feature\Controllers\API\Act;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/acts';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'name' => fake()->name,
        ];
    }

    public function test_as_guest(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.acts.edit', ['id' => 1]);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_without_slug(): void
    {
        $this->payload['slug'] = null;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals(Str::slug($this->payload['name']), $act->slug);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_with_custom_slug(): void
    {
        $this->payload['slug'] = fake()->word;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($this->payload['slug'], $act->slug);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_without_profile(): void
    {
        unset($this->payload['profile']);
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($this->payload['name'], $act->name);
        self::assertNull($act->profile);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_with_profile(): void
    {
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($act->name, $this->payload['name']);
        self::assertInstanceOf(ActProfile::class, $act->profile);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_with_image(): void
    {
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));
        $this->payload['new_image'] = fake()->image();
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.acts.edit', ['id' => 1]);

        $act = Act::first();
        self::assertInstanceOf(Act::class, $act);
        self::assertIsString($act->image);
    }

    #[Depends('test_as_user')]
    public function test_updates_and_removes_image(): void
    {
        $this->payload['new_image'] = null;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertNull($act->picture);
    }

    #[Depends('test_as_user')]
    public function test_create_with_same_name(): void
    {
        Act::create($this->payload);

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.acts.edit', ['id' => 2]);
    }
}
