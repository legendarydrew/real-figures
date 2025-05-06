<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberRequest;
use App\Mail\SubscriberConfirm;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class SubscribersController extends Controller
{

    public function store(SubscriberRequest $request): JsonResponse
    {
        // If there is an existing Subscriber, but it has not been confirmed,
        // send another confirmation email.
        $subscriber = Subscriber::whereEmail($request->input('email'))->first();
        if ($subscriber)
        {
            if ($subscriber->confirmed)
            {
                return response()->json('The email address is already subscribed.', 422);
            }
        }
        else
        {
            $subscriber = Subscriber::factory()->unconfirmed()->createOne([
                'email' => $request->input('email')
            ]);
        }

        // Send the [potential] Subscriber a confirmation email.
        Mail::to($subscriber->email)->send(new SubscriberConfirm($subscriber));

        return response()->json(null, 201);
    }

    public function destroy(): RedirectResponse
    {
        $subscriber_ids = request('subscriber_ids');
        Subscriber::whereIn('id', $subscriber_ids)->delete();

        return to_route('admin.subscribers');
    }
}
