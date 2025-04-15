<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class DonationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->$donation = $donation;
    }

    public function build(): DonationConfirmation
    {
        return $this->subject('Thank you for your donation!')
                    ->view('emails.donation-confirmation')
                    ->with(['donation' => $this->donation]);
    }
}
