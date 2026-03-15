<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Redaelfillali\GoogleAnalyticsEvents\GoogleAnalyticsService;

/**
 * SubscriberRemoveController
 * Responsible for removing an existing subscriber.
 *
 * @package App\Http\Controllers\Front
 */
class SubscriberRemoveController extends Controller
{

    public function show(int $subscriber_id, string $code): RedirectResponse
    {
        $subscriber = Subscriber::whereConfirmationCode($code)->find($subscriber_id);
        if ($subscriber)
        {
            $subscriber->delete();
            Session::flash('message', "{$subscriber->email} has been removed. Thank you for your time!");
            app(GoogleAnalyticsService::class)->sendEvent('subscriber', ['value' => -1]);
        }
        else
        {
            Session::flash('message', "Invalid subscriber.");
        }

        return to_route('home');
    }
}
