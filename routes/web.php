<?php

use App\Http\Controllers\ActController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function ()
{
    return view('welcome');
});

// ----------------------------------------------------------------------------
// API endpoints.
// ----------------------------------------------------------------------------
Route::get('/api/acts', [ActController::class, 'index']);
Route::get('/api/acts/{id}', [ActController::class, 'show']);
Route::post('/api/acts', [ActController::class, 'store']);
Route::put('/api/acts/{id}', [ActController::class, 'update']);

Route::post('/api/vote', [VoteController::class, 'store']);
