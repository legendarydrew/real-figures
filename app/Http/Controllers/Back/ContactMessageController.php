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
        return Inertia::render('back/contact', [
            'messages' => fn() => fractal(ContactMessage::orderByDesc('id')->paginate(), new ContactMessageTransformer())
                ->withResourceName('data')
                ->toArray()
        ]);
    }
}
