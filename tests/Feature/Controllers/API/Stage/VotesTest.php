<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class VotesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/stages/%u/votes';
    protected const int ROUND_COUNT = 4;
    private array $payload;

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stage = Stage::factory()->createOne();
        Round::factory(self::ROUND_COUNT)->ended()->withSongs()->withOutcomes()->for($this->stage)->create();
    }

    public function test_as_guest()
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_includes_results_for_all_rounds()
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertJsonCount(self::ROUND_COUNT);
    }

    #[Depends('test_as_user')]
    public function test_stage_not_ended()
    {
        $this->stage = Stage::factory()->createOne();
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertStatus(412);

        $this->stage = Stage::factory()->withRounds()->createOne();
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertStatus(412);
    }

    #[Depends('test_as_user')]
    public function test_invalid_stage()
    {
        $this->stage = Stage::factory()->createOne();
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

}
