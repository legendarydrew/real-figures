<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\GoldenBuzzer;
use App\Transformers\DonationTransformer;
use Inertia\Inertia;
use Inertia\Response;

/**
 * DonorController
 * A (hopefully simple) page that displays a list of Donations and Golden Buzzers.
 *
 * @package App\Http\Controllers\Front
 */
class DonorWallController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('front/donor-wall', [
            'donations' => fn() => fractal(Donation::orderByDesc('id')->get(), new DonationTransformer())->toArray(),
            'buzzers'   => fn() => fractal(GoldenBuzzer::orderByDesc('id')->get(), new DonationTransformer())->toArray(),
        ]);
    }

}
