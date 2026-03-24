<?php

namespace Controllers\API\Analytics;

use App\Http\Controllers\API\Analytics\GoldenBuzzersMadeController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GoldenBuzzersMadeTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/golden-buzzers/made';
    protected const int    DAY_COUNT = 7;

    public function test_as_guest()
    {
        $response = $this->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);
        $response->assertUnauthorized();
    }

    public function test_no_data()
    {
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
            [
                'date'             => $date,
                'eventName'        => 'dialog_open',
                'customEvent:type' => GoldenBuzzersMadeController::DIALOG_ID,
                'eventCount'       => fake()->numberBetween(1, 60)
            ],
            [
                'date'             => $date,
                'eventName'        => GoldenBuzzersMadeController::EVENT_NAME,
                'customEvent:type' => '(not set)',
                'eventCount'       => fake()->numberBetween(1, 60)
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
