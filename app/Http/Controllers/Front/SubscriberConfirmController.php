<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\SubscriberConfirmation;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

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

            Mail::to($subscriber->email)->send(new SubscriberConfirmation());

            Session::flash('message', "{$subscriber->email} has been confirmed for subscription!");
            Session::flash('track', [
                'category' => 'Action',
                'action'   => 'Subscribe',
                'label'    => 'Confirmed'
            ]);

            return to_route('home');
            // https://inertiajs.com/shared-data#flash-messages
        }

        abort(404);
    }
}
