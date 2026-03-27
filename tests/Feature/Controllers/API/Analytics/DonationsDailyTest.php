<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DonationsDailyTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/donations/daily';

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
        $response->assertJsonCount(self::DAY_COUNT + 1);
        $response->assertJsonStructure([
            '*' => [
                'date',
                'eventValue',
            ],
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'date' => now()->subDays(3),
                'eventValue' => fake()->numberBetween(1, 20),
            ],
            [
                'date' => now()->subDays(2),
                'eventValue' => fake()->numberBetween(1, 20),
            ],
            [
                'date' => now()->subDay(),
                'eventValue' => fake()->numberBetween(1, 20),
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(self::DAY_COUNT + 1);
        $response->assertJsonStructure([
            '*' => [
                'date',
                'eventValue',
            ],
        ]);
    }
}
