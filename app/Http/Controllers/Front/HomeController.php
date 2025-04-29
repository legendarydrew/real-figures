<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

/**
 * HomeController
 * The home page of the mini-site.
 * This is actually more complex than we might think: although on the same page, we will want to
 * display different content based on how far the contest has progressed.
 *
 * BEFORE THE CONTEST
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
 *
 * @package App\Http\Controllers\Front
 */
class HomeController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('home');
    }
}
