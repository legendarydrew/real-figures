<?php

use App\Http\Controllers\API\ActController;
use App\Http\Controllers\API\StageController;
use App\Http\Controllers\API\VoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function ()
{
    return Inertia::render('welcome');
})->name('home');

// ----------------------------------------------------------------------------
// API endpoints.
// ----------------------------------------------------------------------------
Route::prefix('/api')->middleware(['auth', 'verified'])->group(function ()
{
    Route::get('acts', [ActController::class, 'index']);
    Route::get('acts/{id}', [ActController::class, 'show'])->name('acts.show');
    Route::post('acts', [ActController::class, 'store'])->name('acts.store');
    Route::patch('acts/{id}', [ActController::class, 'update'])->name('acts.update');
    Route::delete('acts/{id}', [ActController::class, 'destroy'])->name('acts.destroy');

    Route::get('stages', [StageController::class, 'index'])->name('stages.index');
    Route::get('stages/{id}', [StageController::class, 'show'])->name('stages.edit');
    Route::post('stages', [StageController::class, 'store'])->name('stages.create');
    Route::patch('stages/{id}', [StageController::class, 'update'])->name('stages.update');
    Route::delete('stages/{id}', [StageController::class, 'destroy'])->name('stages.delete');

    Route::post('vote', [VoteController::class, 'store']);
});

Route::middleware(['auth', 'verified'])->group(function ()
{
    Route::get('dashboard', function ()
    {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

// Back office pages.
Route::middleware('auth')->group(function ()
{
    Route::get('/admin/acts', [\App\Http\Controllers\Back\ActsController::class, 'index'])->name('admin.acts');
    Route::get('/admin/songs', [\App\Http\Controllers\Back\SongsController::class, 'index'])->name('admin.songs');
    Route::get('/admin/stages', [\App\Http\Controllers\Back\StagesController::class, 'index'])->name('admin.stages');
});
