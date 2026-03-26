<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SongPlaysTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/plays';
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
        $response->assertJsonCount(self::DAY_COUNT * 24 + 1);
        $response->assertJsonStructure([
            '*' => [
                'time',
                'count'
            ]
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'dateHour'       => now()->subDays(3)->format('YmdH'),
                'eventCount' => fake()->numberBetween(1, 20)
            ],
            [
                'dateHour'       => now()->subDays(2)->format('YmdH'),
                'eventCount' => fake()->numberBetween(1, 20)
            ],
            [
                'dateHour'       => now()->subDay()->format('YmdH'),
                'eventCount' => fake()->numberBetween(1, 20)
            ]
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(self::DAY_COUNT * 24 + 1);
        $response->assertJsonStructure([
            '*' => [
                'time',
                'count'
            ]
        ]);
    }
}
