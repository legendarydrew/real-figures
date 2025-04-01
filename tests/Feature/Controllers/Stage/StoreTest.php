<?php

namespace Tests\Feature\Controllers\Stage;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/stages';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function test_creates_stage()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();
    }

    #[Depends('test_creates_stage')]
    public function test_structure(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertJsonStructure([
            'id',
            'title',
            'description'
        ]);
    }

}
