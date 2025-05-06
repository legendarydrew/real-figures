<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;

class SubscribersController extends Controller
{

    public function destroy(): RedirectResponse
    {
        $subscriber_ids = request('subscriber_ids');
        Subscriber::whereIn('id', $subscriber_ids)->delete();

        return to_route('admin.subscribers');
    }
}
