<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Inertia\Inertia;
use Inertia\Response;

class SubscribersPostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('back/subscribers-post-page', [
            'subscriberCount' => fn() => Subscriber::confirmed()->count()
        ]);
    }

}
