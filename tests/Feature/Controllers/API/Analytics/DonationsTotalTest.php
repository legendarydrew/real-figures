<?php

namespace Tests\Feature\Controllers\API\Analytics;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DonationsTotalTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT  = 'api/analytics/donations/total';
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
        $response->assertJsonCount(self::DAY_COUNT, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'date',
                    'd',
                    'b'
                ]
            ],
            'keys'
        ]);
    }

    public function test_with_data()
    {
        Donation::factory(10)->create(new Sequence([
            'created_at' => fake()->dateTimeBetween('-7 days'),
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(self::DAY_COUNT, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'date',
                    'd',
                    'b'
                ]
            ],
            'keys'
        ]);
    }
}
