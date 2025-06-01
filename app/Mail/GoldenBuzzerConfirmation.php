<?php

namespace App\Mail;

use App\Models\GoldenBuzzer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


/**
 * GoldenBuzzerConfirmation
 * A thank-you email for supporting a Song with a Golden Buzzer.
 *
 * @package App\Mail
 */

class GoldenBuzzerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public GoldenBuzzer $donation;

    public function __construct(GoldenBuzzer $donation)
    {
        $this->$donation = $donation;

        $this->viewData = [
            'donation' => $donation
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for hitting the Golden Buzzer!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.golden-buzzer-confirmation',
        );
    }
}
