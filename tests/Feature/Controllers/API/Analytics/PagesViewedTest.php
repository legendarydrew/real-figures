<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PagesViewedTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/pages';
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
        $response->assertJsonCount(0, 'grouped');
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure([
            'grouped' => [
                '*' => [
                    'url',
                    'count'
                ]
            ],
            'data'    => [
                '*' => [
                    'title',
                    'url',
                    'count'
                ]
            ]
        ]);
    }

    public function test_with_data()
    {
        \Analytics::fake(collect([
            [
                'pageTitle'       => fake()->sentence,
                'fullPageUrl'     => 'realfigures.local/news/some-article',
                'screenPageViews' => fake()->numberBetween(1, 200)
            ],
            [
                'pageTitle'       => fake()->sentence,
                'fullPageUrl'     => 'realfigures.local/contest',
                'screenPageViews' => fake()->numberBetween(1, 200)
            ]
            // NOTE: GA provides the fullPageUrl *without* the protocol.
            // fake()->url sometimes gives us http instead of https.
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2, 'grouped');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'grouped' => [
                '*' => [
                    'url',
                    'count'
                ]
            ],
            'data'    => [
                '*' => [
                    'title',
                    'url',
                    'count'
                ]
            ]
        ]);
    }
}
