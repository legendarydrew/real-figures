<?php

namespace Tests\Feature\Controllers\API\Act;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Intervention\Image\Laravel\Facades\Image;
use PHPUnit\Framework\Attributes\Depends;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Str;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/acts/%u';

    private Act   $act;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act     = Act::factory()->withPicture()->createOne();
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
        $response->assertRedirectToRoute('admin.acts.edit', ['id' => $this->act->id]);
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

    #[Depends('test_as_user')]
    public function test_updates_act_without_slug()
    {
        $this->payload['slug'] = null;
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $act = Act::first();
        self::assertEquals(Str::slug($this->payload['name']), $act->slug);
    }

    #[Depends('test_as_user')]
    public function test_updates_act_with_custom_slug()
    {
        $this->payload['slug'] = fake()->word;
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $act = Act::first();
        self::assertEquals($this->payload['slug'], $act->slug);
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
        unset($this->payload['profile']);
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->load('profile');
        self::assertNull($this->act->profile);
    }

    #[Depends('test_updates_act')]
    public function test_updates_and_adds_image()
    {
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));
        $this->payload['image'] = Image::read(fake()->image())->encode()->toDataUri();
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertIsString($this->act->image);
    }

    #[Depends('test_updates_act')]
    public function test_updates_and_removes_image()
    {
        $this->payload['image'] = null;
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertNull($this->act->image);
    }

}
