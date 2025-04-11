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
Route::get('/api/acts', [ActController::class, 'index']);
Route::get('/api/acts/{id}', [ActController::class, 'show']);
Route::post('/api/acts', [ActController::class, 'store']);
Route::put('/api/acts/{id}', [ActController::class, 'update']);
Route::delete('/api/acts/{id}', [ActController::class, 'destroy']);

Route::get('/api/stages', [StageController::class, 'index']);
Route::get('/api/stages/{id}', [StageController::class, 'show']);
Route::post('/api/stages', [StageController::class, 'store']);
Route::put('/api/stages/{id}', [StageController::class, 'update']);
Route::delete('/api/stages/{id}', [StageController::class, 'destroy']);

Route::post('/api/vote', [VoteController::class, 'store']);

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
    Route::get('/admin/stages', [\App\Http\Controllers\Back\StagesController::class, 'index'])->name('admin.stages');
});
