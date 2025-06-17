<?php

namespace Tests\Feature\Controllers\API;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class LanguagesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/languages';

    public function test_as_guest()
    {
        $response = $this->getJson(self::ENDPOINT);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_structure()
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertJsonCount(20);
        $response->assertJsonStructure(['*' => ['code', 'name']]);
    }

}
