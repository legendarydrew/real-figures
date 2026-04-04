<?php

namespace App\Http\Controllers\Front;

use App\Facades\ContestFacade;
use App\Facades\ContestFacade as Contest;
use App\Http\Controllers\Controller;
use App\Models\Round;
use App\Models\Song;
use App\Transformers\RoundTransformer;
use App\Transformers\SongTransformer;
use App\Transformers\StageTransformer;
use Illuminate\Contracts\View\View;

/**
 * ContestController
 * This page displays the current status of the contest:
 * - information about the current Stage;
 * - the Songs in the current round.
 */
class ContestController extends Controller
{
    public function index(): View
    {
        /*
         * What we want to display
        +======================+===========+=======+=======+========+===============+
        |    contest state     | countdown | stage | songs | voting | golden buzzer |
        +======================+===========+=======+=======+========+===============+
        | prelaunch            |           |       |       |        |               |
        +----------------------+-----------+-------+-------+--------+---------------+
        | before first round   | y         | y     |       |        |               |
        +----------------------+-----------+-------+-------+--------+---------------+
        | in the current round | y         | y     | y     | y      | y             |
        +----------------------+-----------+-------+-------+--------+---------------+
        | stage over           |           | y     | y     |        | y             |
        +----------------------+-----------+-------+-------+--------+---------------+
        | stage winners chosen |           | y     | y     |        |               |
        +----------------------+-----------+-------+-------+--------+---------------+
        | contest over         |           |       | y     |        |               |
        +----------------------+-----------+-------+-------+--------+---------------+
        (courtesy of https://ozh.github.io/ascii-tables/)
        For convenience, we're assuming that all available Stages are part of the same contest.
        */

        // Check whether the contest is over (i.e. all Stages are "over").
        // In this case, we want to display the winning Acts.
        // We will also list all the Songs that were entered in the Contest.
        $current_stage = Contest::getCurrentStage();

        if (ContestFacade::isOver()) {
            $songs = Song::with(['act'])
                ->whereHas('act')
                ->whereHas('rounds')
                ->get()
                ->sortBy(fn (Song $song) => $song->act->name);

            return view('front.contest.over', [
                'results' => ContestFacade::overallWinners(),
                'songs' => fractal($songs, SongTransformer::class)->toArray(),
            ]);
        }

        // Is there an active Stage?
        if ($current_stage) {
            // Display information about the current Round and any previous (ended) Rounds.
            // If there is no current Round, add a timestamp for counting down to the start of the first Round.
            $current_round = $current_stage->rounds->first(fn (Round $round) => $round->isActive());
            $previous_rounds = null;

            if ($current_stage->hasEnded()) {
                // Display a message about results for the current Stage being tallied.
                // We will also display all previous rounds.
                $template = 'front.contest.stage-end';
                $previous_rounds = $current_stage->rounds;
                $countdown = null;
            } elseif ($current_round) {
                $template = 'front.contest.active-round';
                $previous_rounds = $current_stage->rounds->filter(fn (Round $round) => $round->id < $current_round->id);
                $countdown = $current_round->ends_at->toISOString();
            } else {
                $template = 'front.contest.countdown';
                $countdown = $current_stage->rounds->first()->starts_at->toISOString();
            }

            return view($template, [
                'stage' => fractal($current_stage, StageTransformer::class)->parseIncludes(['description', 'goldenBuzzerPerks'])->toArray(),
                'current_round' => fractal($current_round, RoundTransformer::class, '')->parseIncludes(['full_title', 'playlist'])->toArray(),
                'previous_rounds' => fractal($previous_rounds?->sortByDesc('id'), RoundTransformer::class, '')->parseIncludes(['playlist'])->toArray(),
                'countdown' => $countdown,
                'last_stage' => ContestFacade::isOnLastStage(),
            ]);
        }

        // No current stage: display a generic message.
        // If we get here and there are Stages, this must mean that all Stages are "inactive".
        return view('front.contest.inactive', [
            'stage' => fractal($current_stage, StageTransformer::class)->parseIncludes(['description', 'goldenBuzzerPerks'])->toArray(),
        ]);
    }
}
