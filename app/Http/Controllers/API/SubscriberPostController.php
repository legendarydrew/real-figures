<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberPostRequest;
use App\Mail\SubscriberPostMessage;
use App\Models\Subscriber;
use App\Models\SubscriberPost;
use Illuminate\Http\JsonResponse;
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

    /**
     * Create a new SubscriberPost and email it to confirmed Subscribers.
     * We want to return the number of Subscribers the post was sent to.
     *
     * @param SubscriberPostRequest $request
     * @return JsonResponse
     */
    public function store(SubscriberPostRequest $request): JsonResponse
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

        return response()->json(['subscribers' => $subscribers->count()], 201);
    }
}
