<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Round;
use App\Models\Stage;
use App\Transformers\RoundTransformer;
use App\Transformers\StageTransformer;
use Inertia\Inertia;
use Inertia\Response;

/**
 * HomeController
 * The home page of the mini-site.
 * This is actually more complex than we might think: although on the same page, we will want to
 * display different content based on how far the contest has progressed.
 *
 * @package App\Http\Controllers\Front
 */
class HomeController extends Controller
{

    /*
     *
     * * BEFORE THE CONTEST
     * - display a generic home page with information about the contest.
     * - IF STAGES AND ROUNDS HAVE BEEN CREATED
     *   - display a countdown timer for the first Round.
     *
     * CONTEST HAS BEGUN
     * - AND A ROUND IS UNDERWAY
     *   - display the current Round for voting on.
     *   - display the previous Rounds (in the current Stage) for viewing.
     * - AND ALL ROUNDS IN THE CURRENT STAGE ARE OVER
     *   - BUT WINNERS HAVE NOT BEEN DECIDED
     *     - display a message saying that votes are being calculated.
     *     - (should we display a video announcing the winners?)
     *   - AND WINNERS HAVE BEEN DECIDED (for Stages except the Final)
     *     - display the winners and runners-up, along with votes.
     * END OF CONTEST
     * - display a thank-you message, along with the outcome of the contest.
     *
     * For convenience, we're assuming that all created Stages are part of the same contest.
     */

    public function index(): Response
    {
        // Check that there are Stages defined.
        $stages = Stage::all();

        if ($stages->isNotEmpty())
        {
            // Check whether the contest is over (i.e. all Stages are "over").
            if ($stages->every(fn(Stage $stage) => $stage->isOver()))
            {
                return Inertia::render('front/home/over');
            }

            // Go through each Stage, checking its status.
            $current_stage = $this->getCurrentStage();

            // Is there an active Stage?
            if ($current_stage)
            {
                // TODO if all Rounds have ended, display a different page (we're calculating the results).

                // Display information about the current Round and any previous (ended) Rounds.
                // If there is no current Round, add a timestamp for counting down to the start of the first Round.
                $current_round   = $current_stage->rounds->first(fn(Round $round) => $round->isActive());
                $previous_rounds = null;
                if ($current_round)
                {
                    $component       = 'front/home/round';
                    $previous_rounds = $current_stage->rounds->filter(fn(Round $round) => $round->id < $current_round->id);
                    $countdown       = $current_round->ends_at->toISOString();
                }
                else
                {
                    $component = 'front/home/countdown';
                    $countdown = $current_stage->rounds->first()->starts_at->toISOString();
                }

                return Inertia::render($component, [
                    'stage' => fn() => fractal($current_stage, StageTransformer::class)->parseIncludes(['description'])->toArray(),
                    'currentRound'   => fn() => fractal($current_round, RoundTransformer::class, '')->toArray(),
                    'previousRounds' => fn() => fractal($previous_rounds, RoundTransformer::class, '')->toArray(),
                    'countdown'      => fn() => $countdown,
                ]);
            }
        }

        // The default page version, introducing the contest.
        // If we get here and here are Stages, this must mean that all Stages are "inactive".
        return Inertia::render('front/home/intro');
    }

    protected function getCurrentStage(): Stage|null
    {
        $stages         = Stage::all();
        $previous_stage = null;
        foreach ($stages as $stage)
        {
            if ($stage->isInactive())
            {
                // The Stage has no Rounds - go no further.
                return $previous_stage;
            }
            elseif ($stage->isOver())
            {
                // The current Stage will be the last "over" Stage.
                // This would occur if we want to display the winners of the last Stage.
                $previous_stage = $stage;
            }
            else
            {
                // Any of the other states.
                return $stage;
            }
        }
    }
}
