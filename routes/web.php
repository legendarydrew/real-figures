<?php

use App\Http\Controllers\API\ActController;
use App\Http\Controllers\API\BuzzerController;
use App\Http\Controllers\API\ContactMessagesController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\StageAllocateController;
use App\Http\Controllers\API\StageController;
use App\Http\Controllers\API\StageManualVoteController;
use App\Http\Controllers\API\StageRoundsController;
use App\Http\Controllers\API\StageWinnersController;
use App\Http\Controllers\API\VoteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Back\ActsController;
use App\Http\Controllers\Back\ContactMessageController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\SongsController;
use App\Http\Controllers\Back\StagesController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Models\Round;
use App\Transformers\RoundTransformer;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ----------------------------------------------------------------------------
// Front-facing pages.
// ----------------------------------------------------------------------------
Route::get('/', [\App\Http\Controllers\Front\HomeController::class, 'index'])->name('home');
Route::get('contest-rules', fn() => Inertia::render('front/rules'))->name('rules');


// ----------------------------------------------------------------------------
// Our famous Kitchen Sink page (only available in debug mode).
// ----------------------------------------------------------------------------
if (app()->hasDebugModeEnabled())
{
    Route::get('kitchen-sink', fn() => Inertia::render('kitchen-sink', [
        'round' => fractal(Round::active()->first(), RoundTransformer::class)->toArray()
    ]))->name('kitchen-sink');
}

// ----------------------------------------------------------------------------
// API endpoints.
// ----------------------------------------------------------------------------
Route::prefix('/api')->group(function ()
{
    // Routes accessible without authentication.
    Route::post('donation', [DonationController::class, 'store']);
    Route::post('golden-buzzer', [BuzzerController::class, 'store']);
    Route::post('vote', [VoteController::class, 'store'])->name('vote');

    // Routes accessible with authentication.
    Route::middleware(['auth', 'verified'])->group(function ()
    {
        Route::get('acts', [ActController::class, 'index']);
        Route::get('acts/{id}', [ActController::class, 'show'])->name('acts.show');
        Route::post('acts', [ActController::class, 'store'])->name('acts.store');
        Route::patch('acts/{id}', [ActController::class, 'update'])->name('acts.update');
        Route::delete('acts/{id}', [ActController::class, 'destroy'])->name('acts.destroy');

        Route::put('messages/{id}', [ContactMessagesController::class, 'update'])->name('messages.update');
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
        Route::post('stages/{id}/winners', [StageWinnersController::class, 'store'])->name('stages.winners');
    });

});

// ----------------------------------------------------------------------------
// Back office pages.
// ----------------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function ()
{
    Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/acts', [ActsController::class, 'index'])->name('admin.acts');
    Route::get('/admin/contact', [ContactMessageController::class, 'index'])->name('admin.contact');
    Route::get('/admin/songs', [SongsController::class, 'index'])->name('admin.songs');
    Route::get('/admin/stages', [StagesController::class, 'index'])->name('admin.stages');
});


// ----------------------------------------------------------------------------
// Authentication pages.
// ----------------------------------------------------------------------------
Route::middleware('guest')->group(function ()
{
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function ()
{
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
         ->middleware(['signed', 'throttle:6,1'])
         ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
         ->middleware('throttle:6,1')
         ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ----------------------------------------------------------------------------
// Settings pages.
// ----------------------------------------------------------------------------
Route::middleware('auth')->group(function ()
{
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', fn() => Inertia::render('settings/appearance'))->name('appearance');
});
