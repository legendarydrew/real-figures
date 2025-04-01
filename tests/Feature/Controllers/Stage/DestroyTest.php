<?php

namespace Tests\Feature\Controllers\Stage;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/stages/%u';

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->create();
        Round::factory(4)->for($this->stage)->create();
    }

    public function test_valid_row(): void
    {
        $response = $this->deleteJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertNoContent();
    }

    public function test_invalid_row(): void
    {
        $response = $this->deleteJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_valid_row')]
    public function test_deletes_stage_and_rounds(): void
    {
        $this->deleteJson(sprintf(self::ENDPOINT, $this->stage->id));
        $stage  = Stage::find($this->stage->id);
        $rounds = Round::whereStageId($this->stage->id)->first();

        self::assertNull($stage);
        self::assertNull($rounds);
    }
}
