<?php

namespace Controllers\Back;

use App\Models\GoldenBuzzer;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class GoldenBuzzersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest()
    {
        $response = $this->get(route('admin.golden-buzzers'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.golden-buzzers'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('back/golden-buzzers'));
    }

    #[Depends('test_as_user')]
    public function test_with_golden_buzzers()
    {
        Stage::factory()->withRounds()->create();
        GoldenBuzzer::factory(21)->create();

        $response = $this->actingAs($this->user)->get(route('admin.golden-buzzers'));

        $response->assertOk();
    }

}
