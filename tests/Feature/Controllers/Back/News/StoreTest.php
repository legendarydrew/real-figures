<?php

namespace Tests\Feature\Controllers\Back\News;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'title'   => fake()->sentence(),
            'content' => fake()->paragraph()
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(route('news.store'), $this->payload);

        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->postJson(route('news.store'), $this->payload);

        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);
    }

}
