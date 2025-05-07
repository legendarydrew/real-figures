<?php

namespace Tests\Feature\Controllers\Subscribers;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = 'subscriber/confirm/%u/%s';

    private Subscriber $subscriber;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscriber = Subscriber::factory()->unconfirmed()->create();
    }

    public function test_confirm_unconfirmed_subscriber()
    {
        $url      = sprintf(self::ENDPOINT, $this->subscriber->id, $this->subscriber->confirmation_code);
        $response = $this->get($url);
        $response->assertRedirectToRoute('home');

        $this->subscriber->refresh();
        self::assertTrue((bool)$this->subscriber->confirmed);
    }

    public function test_confirm_confirmed_subscriber()
    {
        $this->subscriber->update([
            'confirmed' => true
        ]);

        $url      = sprintf(self::ENDPOINT, $this->subscriber->id, $this->subscriber->confirmation_code);
        $response = $this->get($url);
        $response->assertRedirectToRoute('home');

        $this->subscriber->refresh();
        self::assertTrue((bool)$this->subscriber->confirmed);
    }

    public function test_invalid_id()
    {
        $url      = sprintf(self::ENDPOINT, 404, $this->subscriber->confirmation_code);
        $response = $this->get($url);
        $response->assertNotFound();
    }

    public function test_invalid_code()
    {
        $url      = sprintf(self::ENDPOINT, $this->subscriber->id, '404');
        $response = $this->get($url);
        $response->assertNotFound();

        $this->subscriber->refresh();
        self::assertFalse((bool)$this->subscriber->confirmed);
    }

    public function test_no_id()
    {
        $url      = sprintf(self::ENDPOINT, '', $this->subscriber->confirmation_code);
        $response = $this->get($url);
        $response->assertNotFound();
    }

    public function test_no_code()
    {
        $url      = sprintf(self::ENDPOINT, $this->subscriber->id, '');
        $response = $this->get($url);
        $response->assertNotFound();
    }
}
