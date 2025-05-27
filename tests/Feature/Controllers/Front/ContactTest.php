<?php

namespace Tests\Feature\Controllers\Front;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access()
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/contact'));
    }

}
