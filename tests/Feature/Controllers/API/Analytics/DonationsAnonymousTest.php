<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class DonationsAnonymousTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/donations/anonymous';

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
                    'name',
                    'count',
                ],
            ],
        ]);
    }

    public function test_with_data(): void
    {
        \Analytics::fake(collect([
            [
                'date' => now()->subDay(),
                'customEvent:anonymous' => true,
                'eventCount' => fake()->numberBetween(1, 200),
            ],
            [
                'date' => now()->subDays(2),
                'customEvent:anonymous' => false,
                'eventCount' => fake()->numberBetween(1, 200),
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
                    'name',
                    'count',
                ],
            ],
        ]);
    }
}
