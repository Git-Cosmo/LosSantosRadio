<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NowPlayingController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Now Playing API endpoints (high-performance updates)
Route::prefix('nowplaying')->name('api.nowplaying.')->group(function () {
    Route::get('/', [NowPlayingController::class, 'index'])->name('index');
    Route::get('/sse-config', [NowPlayingController::class, 'sseConfig'])->name('sse-config');
    Route::get('/sse', [NowPlayingController::class, 'sseProxy'])->name('sse');
});

// Station-related API endpoints
Route::prefix('station/{stationId}')->name('api.station.')->group(function () {
    // Song request endpoints (AzuraCast compatible)
    Route::get('/request', [StationController::class, 'requestableList'])->name('requestable.list');
    Route::post('/request/{requestId}', [StationController::class, 'submitRequest'])->name('request.submit');
});

// Search API
Route::prefix('search')->name('api.search.')->group(function () {
    Route::get('/', [SearchController::class, 'search'])->name('index');
    Route::get('/instant', [SearchController::class, 'instant'])->name('instant');
});

// Media API (for admin media management)
// Uses web middleware for session-based authentication with admin middleware check
Route::middleware(['web', 'auth'])->prefix('media')->name('api.media.')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::post('/', [MediaController::class, 'upload'])->name('upload');
    Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
});
