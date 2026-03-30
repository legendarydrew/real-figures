<?php

use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ActsController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\ContestController;
use App\Http\Controllers\Front\DonateController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\RulesController;
use App\Http\Controllers\Front\SitemapController;
use App\Http\Controllers\Front\SubscriberConfirmController;
use App\Http\Controllers\Front\SubscriberRemoveController;
use App\Http\Controllers\Front\VotesController;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------------------------------
// Front-facing pages.
// ----------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('about', [AboutController::class, 'index'])->name('about');
Route::get('acts', [ActsController::class, 'index'])->name('acts');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::get('contest', [ContestController::class, 'index'])->name('contest');
Route::get('donate', [DonateController::class, 'index'])->name('donate');
Route::get('news', [NewsController::class, 'index'])->name('news');
Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('rules', [RulesController::class, 'index'])->name('rules');
Route::get('subscriber/{id}/{code}', [SubscriberConfirmController::class, 'show'])->name('subscriber.confirm');
Route::get('subscriber/remove/{id}/{code}', [SubscriberRemoveController::class, 'show'])->name('subscriber.remove');
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('votes', [VotesController::class, 'index'])->name('votes');

// RSS feed.
Route::feeds();
