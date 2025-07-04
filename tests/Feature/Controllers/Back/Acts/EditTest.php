<?php

namespace Tests\Feature\Controllers\Back\Acts;

use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    use DatabaseMigrations;

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();
        $this->act = Act::factory()->create();
    }

    public function test_as_guest()
    {
        $response = $this->get(route('admin.acts.edit', ['id' => $this->act->id]));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.acts.edit', ['id' => $this->act->id]));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('back/act-edit')
                                                          ->has('act')
                                                          ->has('genreList'));
    }

}
