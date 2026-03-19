<?php

use App\Http\Controllers\API\ActController;
use App\Http\Controllers\API\Analytics\ActViewsController;
use App\Http\Controllers\API\Analytics\BrowsersController;
use App\Http\Controllers\API\Analytics\CollapseController;
use App\Http\Controllers\API\Analytics\CountriesController;
use App\Http\Controllers\API\Analytics\DonationsAnonymousController;
use App\Http\Controllers\API\Analytics\DonationsDailyController;
use App\Http\Controllers\API\Analytics\DonationsMadeController;
use App\Http\Controllers\API\Analytics\DonationsTotalController;
use App\Http\Controllers\API\Analytics\GoldenBuzzersMadeController;
use App\Http\Controllers\API\Analytics\OperatingSystemsController;
use App\Http\Controllers\API\Analytics\OutboundController;
use App\Http\Controllers\API\Analytics\PagesController;
use App\Http\Controllers\API\Analytics\PageViewsController;
use App\Http\Controllers\API\Analytics\PlatformController;
use App\Http\Controllers\API\Analytics\PlaysController;
use App\Http\Controllers\API\Analytics\ReferrersController;
use App\Http\Controllers\API\Analytics\SongPlaysController;
use App\Http\Controllers\API\Analytics\SubscribersController;
use App\Http\Controllers\API\Analytics\UserTypesController;
use App\Http\Controllers\API\Analytics\VotesController;
use App\Http\Controllers\API\BuzzerController;
use App\Http\Controllers\API\ContactMessagesController;
use App\Http\Controllers\API\ContactMessagesRespondController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\GoldenBuzzerBreakdownController;
use App\Http\Controllers\API\LanguagesController;
use App\Http\Controllers\API\NewsPromptController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\SongPlayController;
use App\Http\Controllers\API\StageAllocateController;
use App\Http\Controllers\API\StageController;
use App\Http\Controllers\API\StageManualVoteController;
use App\Http\Controllers\API\StageRoundsController;
use App\Http\Controllers\API\StageVotesController;
use App\Http\Controllers\API\StageWinnersController;
use App\Http\Controllers\API\SubscriberPostController;
use App\Http\Controllers\API\VoteController;
use App\Http\Controllers\Back\NewsController;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------------------------------
// API endpoints.
// ----------------------------------------------------------------------------
Route::prefix('/api')->group(function ()
{
    // Routes accessible without authentication.
    Route::post('donation', [DonationController::class, 'store']);
    Route::post('golden-buzzer', [BuzzerController::class, 'store']);
    Route::get('languages', [LanguagesController::class, 'index'])->name('languages');
    Route::post('messages', [ContactMessagesController::class, 'store']);
    Route::put('songs/{id}/play', [SongPlayController::class, 'update'])->name('play');
    Route::post('subscribers', [\App\Http\Controllers\API\SubscribersController::class, 'store'])->name('subscribe');
    Route::post('vote', [VoteController::class, 'store'])->name('vote');

    // Routes accessible with authentication.
    Route::middleware(['auth', 'verified'])->group(function ()
    {
        Route::get('acts', [ActController::class, 'index']);
        Route::get('acts/{id}', [ActController::class, 'show'])->name('acts.show');
        Route::post('acts', [ActController::class, 'store'])->name('acts.store');
        Route::patch('acts/{id}', [ActController::class, 'update'])->name('acts.update');
        Route::delete('acts/{id}', [ActController::class, 'destroy'])->name('acts.destroy');

        Route::get('analytics/acts', [ActViewsController::class, 'index'])->name('analytics.acts');
        Route::get('analytics/browsers', [BrowsersController::class, 'index'])->name('analytics.browsers');
        Route::get('analytics/collapse', [CollapseController::class, 'index'])->name('analytics.collapse');
        Route::get('analytics/countries', [CountriesController::class, 'index'])->name('analytics.countries');
        Route::get('analytics/donations/anonymous', [DonationsAnonymousController::class, 'index'])->name('analytics.donations.anonymous');
        Route::get('analytics/donations/daily', [DonationsDailyController::class, 'index'])->name('analytics.donations.daily');
        Route::get('analytics/donations/made', [DonationsMadeController::class, 'index'])->name('analytics.donations.made');
        Route::get('analytics/donations/total', [DonationsTotalController::class, 'index'])->name('analytics.donations.total');
        Route::get('analytics/golden-buzzers/made', [GoldenBuzzersMadeController::class, 'index'])->name('analytics.golden-buzzers.made');
        Route::get('analytics/os', [OperatingSystemsController::class, 'index'])->name('analytics.os');
        Route::get('analytics/outbound', [OutboundController::class, 'index'])->name('analytics.outbound');
        Route::get('analytics/page-views', [PageViewsController::class, 'index'])->name('analytics.page-views');
        Route::get('analytics/pages', [PagesController::class, 'index'])->name('analytics.pages');
        Route::get('analytics/platform', [PlatformController::class, 'index'])->name('analytics.platform');
        Route::get('analytics/plays', [PlaysController::class, 'index'])->name('analytics.plays');
        Route::get('analytics/referrers', [ReferrersController::class, 'index'])->name('analytics.referrers');
        Route::get('analytics/songs', [SongPlaysController::class, 'index'])->name('analytics.songs');
        Route::get('analytics/subscribers', [SubscribersController::class, 'index'])->name('analytics.subscribers');
        Route::get('analytics/user-types', [UserTypesController::class, 'index'])->name('analytics.user-types');
        Route::get('analytics/votes', [VotesController::class, 'index'])->name('analytics.votes');

        Route::get('golden-buzzers/breakdown', [GoldenBuzzerBreakdownController::class, 'index']);

        Route::post('news/generate', [\App\Http\Controllers\API\NewsGenerateController::class, 'store'])->name('news.generate');
        Route::post('news/prompt', [NewsPromptController::class, 'store'])->name('news.prompt');

        Route::post('news', [NewsController::class, 'store'])->name('news.store');
        Route::put('news/{id}', [NewsController::class, 'update'])->name('news.update');

        Route::put('messages/{id}', [ContactMessagesController::class, 'update'])->name('messages.read');
        Route::put('messages/{id}/respond', [ContactMessagesRespondController::class, 'update'])->name('messages.respond');
        Route::delete('messages', [ContactMessagesController::class, 'destroy'])->name('messages.destroy');

        Route::post('songs', [SongController::class, 'store'])->name('songs.store');
        Route::patch('songs/{id}', [SongController::class, 'update'])->name('songs.update');
        Route::delete('songs/{id}', [SongController::class, 'destroy'])->name('songs.destroy');

        Route::get('stages', [StageController::class, 'index'])->name('stages.index');
        Route::get('stages/{id}', [StageController::class, 'show'])->name('stages.show');
        Route::post('stages', [StageController::class, 'store'])->name('stages.store');
        Route::patch('stages/{id}', [StageController::class, 'update'])->name('stages.update');
        Route::delete('stages/{id}', [StageController::class, 'destroy'])->name('stages.destroy');
        Route::post('stages/{id}/allocate', [StageAllocateController::class, 'store'])->name('stages.allocate');
        Route::get('stages/{id}/manual-vote', [StageManualVoteController::class, 'show'])->name('stages.manual-vote.show');
        Route::post('stages/{id}/manual-vote', [StageManualVoteController::class, 'store'])->name('stages.manual-vote.store');
        Route::get('stages/{id}/rounds', [StageRoundsController::class, 'show'])->name('stages.rounds');
        Route::get('stages/{id}/votes', [StageVotesController::class, 'show'])->name('stages.votes');
        Route::post('stages/{id}/winners', [StageWinnersController::class, 'store'])->name('stages.winners');

        Route::delete('subscribers', [\App\Http\Controllers\API\SubscribersController::class, 'destroy'])->name('subscribers.destroy');
        Route::post('subscribers/post', [SubscriberPostController::class, 'store'])->name('subscribers.post');
    });
});
