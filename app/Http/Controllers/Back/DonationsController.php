<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Transformers\DonationTransformer;
use Inertia\Inertia;
use Inertia\Response;

class DonationsController extends Controller
{

    public function index(): Response
    {
        $rows             = Donation::orderByDesc('id')->paginate();
        $is_first_page    = $rows->currentPage() === 1;
        $transformed_data = fractal($rows->items(), new DonationTransformer())->parseIncludes(['amount'])->toArray();

        return Inertia::render('back/donations', [
            'count'        => fn() => Donation::count(),
            'rows'         => $is_first_page
                ? $transformed_data
                : Inertia::merge(fn() => $transformed_data),
            'isFirstPage'  => fn() => $is_first_page,
            'currentPage'  => fn() => $rows->currentPage(),
            'hasMorePages' => fn() => $rows->hasMorePages(),
        ]);
    }
}
