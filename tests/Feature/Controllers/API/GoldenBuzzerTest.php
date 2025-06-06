<?php

namespace Tests\Feature\Controllers\API;

use App\Facades\PaypalServiceFacade;
use App\Mail\GoldenBuzzerConfirmation;
use App\Models\GoldenBuzzer;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class GoldenBuzzerTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/golden-buzzer';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $stage         = Stage::factory()->createOne();
        $round         = Round::factory()->withSongs()->createOne([
            'stage_id' => $stage->id,
        ]);
        $song          = fake()->randomElement($round->songs);
        $this->payload = [
            'round_id'       => $round->id,
            'song_id'        => $song->id,
            'transaction_id' => fake()->uuid,
            'message'        => fake()->text,
        ];
    }

    public function test_as_guest()
    {
        $this->mockSuccessfulCapture();
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertCreated();
    }

    #[Depends('test_as_guest')]
    public function test_success_creates_donation()
    {
        $this->mockSuccessfulCapture();
        $this->postJson(self::ENDPOINT, $this->payload);
        $donation = GoldenBuzzer::whereTransactionId($this->payload['transaction_id'])->first();
        self::assertInstanceOf(GoldenBuzzer::class, $donation);

        self::assertEquals($this->payload['song_id'], $donation->song_id);
        self::assertEquals('John Doe', $donation->name);
        self::assertEquals(100.00, $donation->amount);
        self::assertEquals('USD', $donation->currency);
        self::assertEquals($this->payload['message'], $donation->message);
    }

    #[Depends('test_as_guest')]
    public function test_success_sends_email()
    {
        $this->mockSuccessfulCapture();
        $this->postJson(self::ENDPOINT, $this->payload);
        Mail::assertSent(GoldenBuzzerConfirmation::class, function (GoldenBuzzerConfirmation $mail)
        {
            return $mail->hasTo('customer@example.com');
        });
    }

    #[Depends('test_as_guest')]
    public function test_failed_denied()
    {
        $this->mockDeniedCapture();
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();

        $donation = GoldenBuzzer::whereTransactionId($this->payload['transaction_id'])->first();
        self::assertNull($donation);
        Mail::assertNotSent(GoldenBuzzerConfirmation::class);
    }

    public function test_failed_unprocessable()
    {
        $this->mockUnprocessableCapture();
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();

        $donation = GoldenBuzzer::whereTransactionId($this->payload['transaction_id'])->first();
        self::assertNull($donation);
        Mail::assertNotSent(GoldenBuzzerConfirmation::class);
    }

    protected function mockSuccessfulCapture(): void
    {
        PaypalServiceFacade::shouldReceive('verifyOrder')->andReturn([
            "id"             => $this->payload['transaction_id'],
            "status"         => "COMPLETED",
            "purchase_units" => [
                [
                    "reference_id" => "d9f80740-38f0-11e8-b467-0ed5f89f718b",
                    "payments"     => [
                        "captures" => [
                            [
                                "id"                          => "3C679366HH908993F",
                                "status"                      => "COMPLETED",
                                "amount"                      => [
                                    "currency_code" => "USD",
                                    "value"         => "100.00"
                                ],
                                "seller_protection"           => [
                                    "status"             => "ELIGIBLE",
                                    "dispute_categories" => [
                                        "ITEM_NOT_RECEIVED",
                                        "UNAUTHORIZED_TRANSACTION"
                                    ]
                                ],
                                "final_capture"               => true,
                                "disbursement_mode"           => "INSTANT",
                                "seller_receivable_breakdown" => [
                                    "gross_amount" => [
                                        "currency_code" => "USD",
                                        "value"         => "100.00"
                                    ],
                                    "paypal_fee"   => [
                                        "currency_code" => "USD",
                                        "value"         => "3.00"
                                    ],
                                    "net_amount"   => [
                                        "currency_code" => "USD",
                                        "value"         => "97.00"
                                    ]
                                ],
                                "create_time"                 => "2018-04-01T21:20:49Z",
                                "update_time"                 => "2018-04-01T21:20:49Z",
                            ]
                        ]
                    ]
                ]
            ],
            "payer"          => [
                "name"          => [
                    "given_name" => "John",
                    "surname"    => "Doe"
                ],
                "email_address" => "customer@example.com",
                "payer_id"      => "QYR5Z8XDVJNXQ"
            ],
        ]);
    }

    protected function mockDeniedCapture(): void
    {
        // Something like this is returned with a 403 HTTP error.
        PaypalServiceFacade::shouldReceive('verifyOrder')->andReturn([
            "name"    => "NOT_AUTHORIZED",
            "details" => [
                [
                    "issue"       => "PERMISSION_DENIED",
                    "description" => "You do not have permission to access or perform operations on this resource."
                ]
            ],
            "message" => "Authorization failed due to insufficient permissions.",
        ]);
    }

    protected function mockUnprocessableCapture(): void
    {
        // Something like this is returned with a 422 HTTP error.
        PaypalServiceFacade::shouldReceive('verifyOrder')->andReturn([
            "name"    => "UNPROCESSABLE_ENTITY",
            "details" => [
                [
                    "issue"       => "PAYER_ACTION_REQUIRED",
                    "description" => "Transaction cannot complete successfully, instruct the buyer to return to PayPal."
                ]
            ],
            "message" => "The requested action could not be performed, semantically incorrect, or failed business validation.",
        ]);
    }
}
