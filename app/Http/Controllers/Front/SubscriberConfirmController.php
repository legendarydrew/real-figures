<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\SubscriberConfirmation;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Redaelfillali\GoogleAnalyticsEvents\GoogleAnalyticsService;

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
            $previously_confirmed = $subscriber->confirmed;
            $subscriber->update([
                'confirmed' => true
            ]);

            if (!$previously_confirmed)
            {
                Mail::to($subscriber->email)->send(new SubscriberConfirmation());
            }

            app(GoogleAnalyticsService::class)->sendEvent('subscriber', ['value' => 1]);
            Session::flash('message', "{$subscriber->email} has been confirmed for subscription!");

            return to_route('home');
            // https://inertiajs.com/shared-data#flash-messages
        }

        abort(404);
    }
}
