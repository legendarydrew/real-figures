<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberPostRequest;
use App\Mail\SubscriberPostMessage;
use App\Models\Subscriber;
use App\Models\SubscriberPost;
use Illuminate\Support\Facades\Mail;

/**
 * SubscriberPostController
 * Used to create Subscriber posts and send emails to Subscribers.
 * We won't bother to attempt to send emails if there are no Subscribers! #WeAintFatherMcKenzie
 *
 * @package App\Http\Controllers\API
 */
class SubscriberPostController extends Controller
{

    public function store(SubscriberPostRequest $request): \Illuminate\Http\RedirectResponse
    {
        // Create a SubscriberPost, regardless of whether we have Subscribers.
        // Why? Because we might want to add a site section where past posts can be shown.

        $post = SubscriberPost::create([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        // Only send email if we have [confirmed!] Subscribers.
        $subscribers = Subscriber::whereConfirmed(true)->get();
        if ($subscribers->isNotEmpty())
        {
            foreach ($subscribers as $subscriber)
            {
                Mail::to($subscriber)->send(new SubscriberPostMessage($subscriber, $post));
            }
        }

        return to_route('admin.subscribers')->with('subscribers',  $subscribers->count());
    }
}
