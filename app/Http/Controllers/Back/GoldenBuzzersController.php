<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\GoldenBuzzer;
use App\Transformers\ContactMessageTransformer;
use App\Transformers\GoldenBuzzerTransformer;
use Inertia\Inertia;
use Inertia\Response;

class GoldenBuzzersController extends Controller
{

    public function index(): Response
    {
        $rows         = GoldenBuzzer::orderByDesc('id')->paginate();
        $is_first_page    = $rows->currentPage() === 1;
        $transformed_data = fractal($rows->items(), new GoldenBuzzerTransformer())->toArray();

        return Inertia::render('back/golden-buzzers', [
            'count' => fn() => GoldenBuzzer::count(),
            'rows'     => $is_first_page
                ? $transformed_data
                : Inertia::merge(fn() => $transformed_data),
            'isFirstPage'  => fn() => $is_first_page,
            'currentPage'  => fn() => $rows->currentPage(),
            'hasMorePages' => fn() => $rows->hasMorePages(),
        ]);
    }
}
