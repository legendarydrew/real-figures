<?php

namespace Controllers\API\Analytics;

use App\Models\Donation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DonationsMadeTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/donations/made';
    protected const int    DAY_COUNT = 7;

    public function test_as_guest()
    {
        $response = $this->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);
        $response->assertUnauthorized();
    }

    public function test_no_data()
    {
        Donation::truncate();

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(self::DAY_COUNT + 1);
        $response->assertJsonStructure([
            '*' => [
                'date',
                'started',
                'completed'
            ]
        ]);
    }

    public function test_with_data()
    {
        $date = now()->subDay();
        \Analytics::fake(collect([
            'started'   => [
                'date'             => $date,
                'eventName'        => 'dialog_open',
                'customEvent:type' => 'donate',
                'eventCount'       => fake()->numberBetween(1, 60)
            ],
            'completed' => [
                'date'       => $date,
                'eventCount' => fake()->numberBetween(1, 60)
            ]
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(self::DAY_COUNT + 1);
        $response->assertJsonStructure([
            '*' => [
                'date',
                'started',
                'completed'
            ]
        ]);
    }
}
