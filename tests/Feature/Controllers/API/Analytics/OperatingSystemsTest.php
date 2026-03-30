<?php

namespace Tests\Feature\Controllers\API\Analytics;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class OperatingSystemsTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/os';

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
        $response->assertJsonCount(0);
        $response->assertJsonStructure([
            '*' => [
                'operatingSystem',
                'screenPageViews',
            ],
        ]);
    }

    public function test_with_data(): void
    {
        \Analytics::fake(collect([
            [
                'operatingSystem' => fake()->unique()->word,
                'screenPageViews' => fake()->numberBetween(1, 200),
            ],
            [
                'operatingSystem' => fake()->unique()->word,
                'screenPageViews' => fake()->numberBetween(1, 200),
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => [
                'operatingSystem',
                'screenPageViews',
            ],
        ]);
    }
}
