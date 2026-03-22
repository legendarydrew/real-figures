<?php

namespace Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/platform';
    protected const int    DAY_COUNT = 7;

    public function test_as_guest()
    {
        $response = $this->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);
        $response->assertUnauthorized();
    }

    public function test_no_data()
    {
        \Analytics::fake(collect());

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(0);
        $response->assertJsonStructure([
            '*' => [
                'platform',
                'views'
            ]
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'platform' => fake()->unique()->word,
                'screenPageViews' => fake()->numberBetween(1, 200)
            ],
            [
                'platform' => fake()->unique()->word,
                'screenPageViews' => fake()->numberBetween(1, 200)
            ]
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => [
                'platform',
                'views'
            ]
        ]);
    }
}
