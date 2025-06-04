<?php

namespace App\Mail;

use App\Models\Subscriber;
use App\Models\SubscriberPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriberPostMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private Subscriber $subscriber, private SubscriberPost $post)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('contest.subscriber.subject', ['title' => $this->post->title]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscriber-post',
            with: ['subscriber' => $this->subscriber, 'post' => $this->post],
        );
    }

}
