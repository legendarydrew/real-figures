<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * HomeController
 * The home page of the site.
 *
 * @package App\Http\Controllers\Front
 */
class RulesController extends Controller
{

    public function index(): View
    {
        return view('front.rules');
    }

}
