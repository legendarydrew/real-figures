<?php

namespace Controllers\API;

use App\Models\GoldenBuzzer;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class GoldenBuzzerBreakdownTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/golden-buzzers/breakdown';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        Stage::factory()->withRounds()->createOne();
        GoldenBuzzer::factory(10)->create();
    }

    public function test_as_guest()
    {
        $response = $this->getJson(self::ENDPOINT);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_structure()
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertJsonStructure([
            'rounds' => [
                '*' => [
                    'round_id',
                    'round_title',
                    'amount_raised'
                ]
            ],
            'songs'  => [
                '*' => [
                    'song',
                    'buzzer_count',
                    'amount_raised'
                ]
            ]
        ]);
    }

}
