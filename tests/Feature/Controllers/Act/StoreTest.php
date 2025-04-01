<?php

namespace Controllers\Act;

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

    public function test_creates_post()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();
    }

    #[Depends('test_creates_post')]
    public function test_structure(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertJsonStructure([
            'id',
            'name',
            'slug',
            'has_profile'
        ]);
    }

    #[Depends('test_structure')]
    public function test_creates_post_without_profile()
    {
        $this->payload['profile'] = null;
        $response = $this->postJson(self::ENDPOINT, $this->payload);

        $response->assertCreated();
        $response->assertJsonPath('has_profile', false);
    }

    #[Depends('test_structure')]
    public function test_creates_post_with_profile()
    {
        $this->payload['profile'] = ['description' => fake()->paragraph];
        $response = $this->postJson(self::ENDPOINT, $this->payload);

        $response->assertCreated();
        $response->assertJsonPath('has_profile', true);
    }

}
