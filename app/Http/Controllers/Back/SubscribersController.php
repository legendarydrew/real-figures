<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Transformers\SubscriberTransformer;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SubscribersController extends Controller
{
    public function index(): Response
    {
        $subscriber_count = Subscriber::confirmed()->count();

        $query = Subscriber::confirmed();
        if ($filter = request()->query('filter'))
        {
            $query->whereLike('email', "%{$filter['email']}%");
        }
        $confirmed_subscribers = $query->orderByDesc('id')->paginate();
        $is_first_page         = $confirmed_subscribers->currentPage() === 1;
        $transformed_data      = fractal($confirmed_subscribers->items(), new SubscriberTransformer())->toArray();

        return Inertia::render('back/subscribers', [
            'subscriberCount' => fn() => $subscriber_count,
            'subscribers'     => $is_first_page
                ? $transformed_data
                : Inertia::merge(fn() => $transformed_data),
            'currentPage'     => fn() => $confirmed_subscribers->currentPage(),
            'nextPage'        => fn() => $confirmed_subscribers->currentPage() + 1,
            'hasMorePages'    => fn() => $confirmed_subscribers->hasMorePages(),
        ]);
    }

}
