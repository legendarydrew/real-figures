<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReferrersTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/referrers';
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
                'referrer',
                'count'
            ]
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'pageReferrer'    => fake()->url,
                'screenPageViews' => fake()->numberBetween(1, 200)
            ],
            [
                'pageReferrer'    => fake()->url,
                'screenPageViews' => fake()->numberBetween(1, 200)
            ]
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => [
                'referrer',
                'count'
            ]
        ]);
    }
}
