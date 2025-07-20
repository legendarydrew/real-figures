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

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/acts';

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
        $response->assertRedirectToRoute('admin.acts.edit', ['id' => 1]);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_without_slug()
    {
        $this->payload['slug'] = null;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals(Str::slug($this->payload['name']), $act->slug);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_with_custom_slug()
    {
        $this->payload['slug'] = fake()->word;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($this->payload['slug'], $act->slug);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_without_profile()
    {
        unset($this->payload['profile']);
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($this->payload['name'], $act->name);
        self::assertNull($act->profile);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_with_profile()
    {
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertEquals($act->name, $this->payload['name']);
        self::assertInstanceOf(ActProfile::class, $act->profile);
    }

    #[Depends('test_as_user')]
    public function test_creates_act_with_image()
    {
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));
        $this->payload['image'] = Image::read(fake()->image())->encode()->toDataUri();
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertIsString($act->image);
    }

    #[Depends('test_as_user')]
    public function test_updates_and_removes_image()
    {
        $this->payload['image'] = null;
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertNull($act->picture);
    }

    #[Depends('test_as_user')]
    public function test_adds_meta_members()
    {
        $this->payload['meta'] = [
            'members' => [
                [ 'name' => 'Max Power', 'role' => 'Bad Boy' ],
                [ 'name' => 'Jess Chillin', 'role' => 'Bad Girl' ],
            ]
        ];
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $act = Act::first();
        self::assertCount(count($this->payload['meta']['members']), $act->members);
    }

}
