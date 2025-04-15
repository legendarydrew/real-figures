<?php

namespace App\Mail;

use App\Models\GoldenBuzzer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class GoldenBuzzerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public GoldenBuzzer $donation;

    public function __construct(GoldenBuzzer $donation)
    {
        $this->$donation = $donation;
    }

    public function build(): GoldenBuzzerConfirmation
    {
        return $this->subject('Thank you for your Golden Buzzer donation!')
                    ->view('emails.golden-buzzer-confirmation')
                    ->with(['donation' => $this->donation]);
    }
}
