<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Transformers\ActTransformer;
use Illuminate\View\View;

/**
 * ActsController
 * A page that displays information about each Act.
 *
 * @package App\Http\Controllers\Front
 */
class ActsController extends Controller
{
    public function index(): View
    {
        $acts = Act::whereHas('songs')->get();
        if ($acts->isNotEmpty())
        {
            return view('front.acts', [
                'acts' => fractal($acts->sortBy('name'), new ActTransformer(), '')
                    ->parseIncludes(['genres', 'profileContent', 'accolades'])
                    ->toArray()
            ]);
        }
        abort(404);
    }

}
