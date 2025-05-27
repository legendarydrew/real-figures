<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RulesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access()
    {
        $response = $this->get(route('rules'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/rules'));
    }

}
