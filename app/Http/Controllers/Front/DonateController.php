<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\GoldenBuzzer;
use App\Transformers\DonationTransformer;
use Illuminate\View\View;

/**
 * DonateController
 * A (hopefully simple) page that displays a list of Donations and Golden Buzzers.
 *
 * @package App\Http\Controllers\Front
 */
class DonateController extends Controller
{
    public function index(): View
    {
        return view('front.donate', [
            'donations' => fractal(Donation::orderByDesc('id')->get(), new DonationTransformer())->toArray(),
            'buzzers'   => fractal(GoldenBuzzer::orderByDesc('id')->get(), new DonationTransformer())->toArray(),
        ]);
    }

}
