<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PageViewsTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/page-views';

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
        $response->assertJsonCount(self::DAY_COUNT + 1);
        $response->assertJsonStructure([
            '*' => [
                'date',
                'screenPageViews',
                'activeUsers',
            ],
        ]);
    }

    public function test_with_data(): void
    {
        \Analytics::fake(collect([
            [
                'date' => now()->subDays(3),
                'screenPageViews' => fake()->numberBetween(1, 200),
                'activeUsers' => fake()->numberBetween(1, 20),
            ],
            [
                'date' => now()->subDays(2),
                'screenPageViews' => fake()->numberBetween(1, 200),
                'activeUsers' => fake()->numberBetween(1, 20),
            ],
            [
                'date' => now()->subDay(),
                'screenPageViews' => fake()->numberBetween(1, 200),
                'activeUsers' => fake()->numberBetween(1, 20),
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(self::DAY_COUNT + 1);
        $response->assertJsonStructure([
            '*' => [
                'date',
                'screenPageViews',
                'activeUsers',
            ],
        ]);
    }
}
