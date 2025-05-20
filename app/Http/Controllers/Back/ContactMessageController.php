<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Transformers\ContactMessageTransformer;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessageController extends Controller
{

    public function index(): Response
    {
        // https://dev.to/deondazy/how-to-combine-filters-sorting-and-infinite-scrolling-in-laravel-inertiajs-v2-and-vue-3-24a7
        // This provides the ability to add more results to the list of messages, infinite scrolling style.
        $messages         = ContactMessage::orderByDesc('id')->paginate();
        $is_first_page    = $messages->currentPage() === 1;
        $transformed_data = fractal($messages->items(), new ContactMessageTransformer())->toArray();

        return Inertia::render('back/contact', [
            'messages'     => $is_first_page
                ? $transformed_data
                : Inertia::merge(fn() => $transformed_data),
            'isFirstPage'  => fn() => $is_first_page,
            'currentPage'  => fn() => $messages->currentPage(),
            'hasMorePages' => fn() => $messages->hasMorePages(),
        ]);
    }
}
