<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;

/**
 * SubscriberConfirmController
 * A very simple endpoint for confirming Subscribers.
 *
 * @package App\Http\Controllers\Front
 */
class SubscriberConfirmController extends Controller
{
    public function show(int $subscriber_id, string $code): RedirectResponse
    {
        $subscriber = Subscriber::whereConfirmationCode($code)->find($subscriber_id);

        if ($subscriber)
        {
            $subscriber->update([
                'confirmed' => true
            ]);
            return to_route('home')->with(['confirmed_subscribe' => true]);
        }

        abort(404);
    }
}
