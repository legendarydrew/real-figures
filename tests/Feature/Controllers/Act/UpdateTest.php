<?php

namespace Controllers\Act;

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

    public function test_updates_act()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertOk();

        $this->act->refresh();
        self::assertEquals($this->payload['name'], $this->act->name);
    }

    public function test_invalid_act()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

    #[Depends('test_updates_act')]
    public function test_structure(): void
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);
        $response->assertJsonStructure([
            'id',
            'name',
            'slug',
            'has_profile'
        ]);
    }

    #[Depends('test_updates_act')]
    public function test_updates_existing_profile()
    {
        ActProfile::factory()->for($this->act)->createOne();
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $response                 = $this->putJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $response->assertOk();

        $this->act->load('profile');
        self::assertEquals($this->payload['profile']['description'], $this->act->profile->description);
    }

    #[Depends('test_updates_act')]
    public function test_updates_and_creates_profile()
    {
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $response                 = $this->putJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $response->assertOk();
        $response->assertJsonPath('has_profile', true);
    }

    #[Depends('test_updates_act')]
    public function test_updates_and_deletes_profile()
    {
        ActProfile::factory()->for($this->act)->createOne();
        $this->payload['profile'] = null;
        $response                 = $this->putJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $response->assertOk();
        $response->assertJsonPath('has_profile', false);
    }

}
