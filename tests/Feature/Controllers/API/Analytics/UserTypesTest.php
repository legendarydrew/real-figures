<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTypesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/user-types';

    protected const int    DAY_COUNT = 7;

    public function test_as_guest(): void
    {
        $response = $this->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);
        $response->assertUnauthorized();
    }

    public function test_no_data(): void
    {
        \Analytics::fake(collect());

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(0);
        $response->assertJsonStructure([
            '*' => [
                'type',
                'count',
            ],
        ]);
    }

    public function test_with_data(): void
    {
        \Analytics::fake(collect([
            [
                'newVsReturning' => 'New',
                'activeUsers' => fake()->numberBetween(1, 200),
            ],
            [
                'newVsReturning' => 'Returning',
                'activeUsers' => fake()->numberBetween(1, 200),
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => [
                'type',
                'count',
            ],
        ]);
    }
}
