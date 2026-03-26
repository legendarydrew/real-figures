<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CountriesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/countries';
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
        $response->assertJsonCount(0, 'continents');
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure([
            'continents' => [
                '*' => [
                    'continent',
                    'views'
                ]
            ],
            'data'       => [
                '*' => [
                    'flag',
                    'country',
                    'continent',
                    'views'
                ]
            ]
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'country'         => fake()->country,
                'countryId'       => fake()->countryISOAlpha3,
                'continent'       => fake()->word,
                'screenPageViews' => fake()->numberBetween(1, 200)
            ],
            [
                'country'         => fake()->country,
                'countryId'       => fake()->countryISOAlpha3,
                'continent'       => fake()->word,
                'screenPageViews' => fake()->numberBetween(1, 200)
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2, 'continents');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'continents' => [
                '*' => [
                    'continent',
                    'views'
                ]
            ],
            'data'       => [
                '*' => [
                    'flag',
                    'country',
                    'continent',
                    'views'
                ]
            ]
        ]);
    }
}
