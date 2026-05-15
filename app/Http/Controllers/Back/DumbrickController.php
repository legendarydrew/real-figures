<?php

namespace App\Http\Controllers\Back;

use App\Facades\ContestFacade as Contest;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DumbrickController extends Controller
{
    public function index(): Response
    {
        $current_round = Contest::getCurrentStage()?->getCurrentRound();
        return Inertia::render('back/dumbrick-page', [
            'currentRound' => fn() => $current_round?->full_title ?? null,
        ]);
    }
}
