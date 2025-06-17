<?php

namespace Tests\Feature\Controllers\API;

use App\Facades\PaypalServiceFacade;
use App\Mail\DonationConfirmation;
use App\Models\Donation;
use App\Models\Language;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class LanguagesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/languages';

    protected function setUp(): void
    {
        parent::setUp();
        Language::factory(20)->create();
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
        $response->assertJsonCount(20);
        $response->assertJsonStructure(['*' => ['code', 'name']]);
    }

}
