<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * ContactController
 * The site's contact form.
 */
class ContactController extends Controller
{
    public function index(): View
    {
        return view('front.contact');
    }
}
