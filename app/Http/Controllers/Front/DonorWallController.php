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
        $donations             = Donation::orderByDesc('id')->paginate();
        $transformed_donations = fractal($donations->items(), new DonationTransformer())->toArray();

        $buzzers             = GoldenBuzzer::orderByDesc('id')->paginate();
        $transformed_buzzers = fractal($buzzers->items(), new DonationTransformer())->toArray();

        return Inertia::render('front/donations', [
            'donations' => fn() => [
                'currentPage'  => $donations->currentPage(),
                'hasMorePages' => $donations->hasMorePages(),
                'rows'         => $donations->currentPage() === 1 ? $transformed_donations : Inertia::merge($transformed_donations)
            ],
            'buzzers'   => fn() => [
                'currentPage'  => $buzzers->currentPage(),
                'hasMorePages' => $buzzers->hasMorePages(),
                'rows'         => $buzzers->currentPage() === 1 ? $transformed_buzzers : Inertia::merge($transformed_buzzers)
            ],
        ]);
    }

}
