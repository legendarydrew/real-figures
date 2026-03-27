<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActViewsTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/acts';
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
            'data'  => [
                '*' => [
                    'date',
                    'total'
                ]
            ],
            'table' => [
                '*' => [
                    'act',
                    'count'
                ]
            ]
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'date'            => now()->subDay(),
                'customEvent:act' => fake()->slug,
                'eventCount'      => fake()->numberBetween(1, 200)
            ],
            [
                'date'            => now()->subDays(2),
                'customEvent:act' => fake()->slug,
                'eventCount'      => fake()->numberBetween(1, 200)
            ],
            [
                'date'            => now()->subDay(),
                'customEvent:act' => fake()->slug,
                'eventCount'      => fake()->numberBetween(1, 200)
            ]
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(3, 'keys');
        $response->assertJsonCount(self::DAY_COUNT + 1, 'data');
        $response->assertJsonCount(3, 'table');
        $response->assertJsonStructure([
            'data'  => [
                '*' => [
                    'date',
                ]
            ],
            'table' => [
                '*' => [
                    'act',
                    'count'
                ]
            ]
        ]);
    }
}
