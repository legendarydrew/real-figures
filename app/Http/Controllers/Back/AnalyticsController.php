<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('back/analytics-page', []);
    }

}
