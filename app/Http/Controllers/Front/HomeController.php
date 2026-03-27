<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * HomeController
 * The home page of the site.
 */
class HomeController extends Controller
{
    public function index(): View
    {
        return view('front.home');
    }
}
