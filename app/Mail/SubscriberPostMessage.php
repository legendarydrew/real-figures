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

    public function __construct(private Subscriber $subscriber, private SubscriberPost $post)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('contest.subscriber.subject', ['title' => $this->post->title]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.subscriber-post',
            with: ['subscriber' => $this->subscriber, 'post' => $this->post],
        );
    }

}
