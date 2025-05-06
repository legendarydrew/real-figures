<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriberConfirm extends Mailable
{
    use Queueable, SerializesModels;

    private string $confirm_url;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->confirm_url = route('subscriber.confirm', [
            'id'   => $subscriber->id,
            'code' => $subscriber->confirmation_code,
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm your Subscription!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.subscriber-confirm',
            with: ['confirm_url' => $this->confirm_url]
        );
    }

}
