<?php

namespace Controllers\API\Analytics;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Analytics\Facades\Analytics;
use Tests\TestCase;

final class PlaylistTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/analytics/playlist';

    protected const int    DAY_COUNT = 7;

    public function test_as_guest(): void
    {
        $response = $this->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);
        $response->assertUnauthorized();
    }

    public function test_no_data(): void
    {
        Analytics::fake(collect());

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(0, 'keys');
        $response->assertJsonCount(self::DAY_COUNT * 24 + 1, 'data');
        $response->assertJsonCount(0, 'table');
        $response->assertJsonStructure([
            'keys',
            'data'  => [
                '*' => [
                    'time',
                ],
            ],
            'table' => [
                '*' => [
                    'button',
                    'count',
                ],
            ],
        ]);
    }

    public function test_with_data(): void
    {
        $stage  = Stage::factory()->withRounds()->create();
        $rounds = $stage->rounds;
        Analytics::fake(collect([
            [
                'dateHour'             => now()->subDay()->format('YmdH'),
                'customEvent:round_id' => $rounds[0]->id,
                'customEvent:label'    => 'prev',
                'eventCount'           => fake()->numberBetween(1, 20),
            ],
            [
                'dateHour'             => now()->subDay()->format('YmdH'),
                'customEvent:round_id' => $rounds[0]->id,
                'customEvent:label'    => 'next',
                'eventCount'           => fake()->numberBetween(1, 20),
            ],
            [
                'dateHour'             => now()->subDays(2)->format('YmdH'),
                'customEvent:round_id' => $rounds[0]->id,
                'customEvent:label'    => 'prev',
                'eventCount'           => fake()->numberBetween(1, 20),
            ],
            [
                'dateHour'             => now()->subDays(2)->format('YmdH'),
                'customEvent:round_id' => $rounds[0]->id,
                'customEvent:label'    => 'next',
                'eventCount'           => fake()->numberBetween(1, 20),
            ],
        ]));

        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT, ['days' => self::DAY_COUNT]);

        $response->assertOk();
        $response->assertJsonCount(2, 'keys');
        $response->assertJsonCount(self::DAY_COUNT * 24 + 1, 'data');
        $response->assertJsonCount(2, 'table');
        $response->assertJsonStructure([
            'keys',
            'data'  => [
                '*' => [
                    'time',
                ],
            ],
            'table' => [
                '*' => [
                    'button',
                    'count',
                ],
            ],
        ]);
    }
}
