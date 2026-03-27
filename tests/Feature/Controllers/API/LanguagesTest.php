<?php

namespace Tests\Feature\Controllers\API;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class LanguagesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/languages';

    public function test_as_guest(): void
    {
        $response = $this->getJson(self::ENDPOINT);
        $response->assertOk();
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_structure(): void
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertJsonCount(10); // see TestCase
        $response->assertJsonStructure(['*' => ['code', 'name']]);
    }
}
