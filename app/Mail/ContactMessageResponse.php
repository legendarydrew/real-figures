<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageResponse extends Mailable
{
    use Queueable, SerializesModels;

    private string         $response;
    private ContactMessage $message;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactMessage $message, string $response)
    {
        $this->message  = $message;
        $this->response = $response;

        $this->viewData = [
            'original_message' => $this->message,
            'response'         => $response,
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A response to your message',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.contact-message-response',
        );
    }

}
