<?php

namespace Tests;

use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected const string ENDPOINT = '';
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        // Add languages.
        Language::factory(20)->create();
    }
}
