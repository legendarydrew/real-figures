<?php

namespace Tests;

use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

abstract class TestCase extends BaseTestCase
{
    protected const string ENDPOINT = '';
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        // Add languages.
        Language::factory(10)->create();

        // Replacement fake image provider.
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));
    }
}
