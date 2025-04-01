<?php

namespace Controllers\Act;

use App\Models\Act;
use App\Models\ActProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/acts/%u';

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act = Act::factory()->withProfile()->create();
    }

    public function test_valid_row(): void
    {
        $response = $this->deleteJson(sprintf(self::ENDPOINT, $this->act->id));
        $response->assertNoContent();
    }

    public function test_invalid_row(): void
    {
        $response = $this->deleteJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_valid_row')]
    public function test_deletes_act_and_profile(): void
    {
        $this->deleteJson(sprintf(self::ENDPOINT, $this->act->id));
        $act         = Act::find($this->act->id);
        $act_profile = ActProfile::whereActId($this->act->id)->first();

        self::assertNull($act);
        self::assertNull($act_profile);
    }
}
