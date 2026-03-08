<?php

use App\Http\Controllers\API\ActController;
use App\Http\Controllers\API\Analytics\CollapseController;
use App\Http\Controllers\API\Analytics\ReferrersController;
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

        Route::get('analytics/collapse', [CollapseController::class, 'index'])->name('analytics.collapse');
        Route::get('analytics/referrers', [ReferrersController::class, 'index'])->name('analytics.referrers');
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
