<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewportTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/viewports';

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
        $response->assertJsonCount(0, 'keys');
        $response->assertJsonCount(self::DAY_COUNT + 1, 'data');
        $response->assertJsonCount(0, 'table');
        $response->assertJsonStructure([
            'keys',
            'data' => [
                '*' => [
                    'date',
                    'total',
                ],
            ],
            'table' => [
                '*' => [
                    'viewport',
                    'views',
                ],
            ],
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'date' => now()->subDay(),
                'customEvent:visitor_viewport' => '1280x900',
                'screenPageViews' => fake()->numberBetween(1, 200),
            ],
            [
                'date' => now()->subDays(2),
                'customEvent:visitor_viewport' => '1280x900',
                'screenPageViews' => fake()->numberBetween(1, 200),
            ],
            [
                'date' => now()->subDay(),
                'customEvent:visitor_viewport' => '320x640',
                'screenPageViews' => fake()->numberBetween(1, 200),
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2, 'keys');  // two sets of dimensions.
        $response->assertJsonCount(self::DAY_COUNT + 1, 'data');  // two different dates.
        $response->assertJsonCount(2, 'table'); // two sets of dimensions.
        $response->assertJsonStructure([
            'keys',
            'data' => [
                '*' => [
                    'date',
                    'total',
                ],
            ],
            'table' => [
                '*' => [
                    'viewport',
                    'views',
                ],
            ],
        ]);
    }
}
