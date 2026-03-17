<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\SubscriberConfirmation;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Redaelfillali\GoogleAnalyticsEvents\GoogleAnalyticsService;

/**
 * SubscriberConfirmController
 * A very simple endpoint for confirming Subscribers.
 *
 * @package App\Http\Controllers\Front
 */
class SubscriberConfirmController extends Controller
{
    public function show(int $subscriber_id, string $code): View
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

            // TRACK new subscriber.
            app(GoogleAnalyticsService::class)->sendEvent('subscriber', ['value' => 1]);

            return view('front.subscriber-confirmed');
        }

        abort(404);
    }
}
