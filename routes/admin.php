<?php

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
use App\Http\Controllers\Back\DonationsController;
use App\Http\Controllers\Back\GoldenBuzzersController;
use App\Http\Controllers\Back\NewsController;
use App\Http\Controllers\Back\NewsGenerateController;
use App\Http\Controllers\Back\SongsController;
use App\Http\Controllers\Back\StagesController;
use App\Http\Controllers\Back\SubscribersController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ----------------------------------------------------------------------------
// Back office pages.
// ----------------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function ()
{
    Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/acts', [ActsController::class, 'index'])->name('admin.acts');
    Route::get('/admin/acts/new', [ActsController::class, 'create'])->name('admin.acts.new');
    Route::get('/admin/acts/{id}', [ActsController::class, 'edit'])->name('admin.acts.edit');
    Route::get('/admin/contact', [ContactMessageController::class, 'index'])->name('admin.contact');
    Route::get('/admin/donations', [DonationsController::class, 'index'])->name('admin.donations');
    Route::get('/admin/golden-buzzers', [GoldenBuzzersController::class, 'index'])->name('admin.golden-buzzers');
    Route::get('/admin/news', [NewsController::class, 'index'])->name('admin.news');
    Route::get('/admin/news/generate', [NewsGenerateController::class, 'index'])->name('admin.news-generate');
    Route::get('/admin/news/new', [NewsController::class, 'create'])->name('admin.news.create');
    Route::get('/admin/news/{id}', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::get('/admin/songs', [SongsController::class, 'index'])->name('admin.songs');
    Route::get('/admin/stages', [StagesController::class, 'index'])->name('admin.stages');
    Route::get('/admin/subscribers', [SubscribersController::class, 'index'])->name('admin.subscribers');
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

    // ----------------------------------------------------------------------------
    // Settings pages.
    // ----------------------------------------------------------------------------
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', fn() => Inertia::render('settings/appearance'))->name('appearance');
});
