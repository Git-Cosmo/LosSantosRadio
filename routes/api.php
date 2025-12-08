<?php

use App\Http\Controllers\Api\GamesApiController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NowPlayingController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\RadioController;
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

// Radio API endpoints for player and stats
Route::prefix('radio')->name('api.radio.')->group(function () {
    Route::get('/now-playing', [RadioController::class, 'nowPlaying'])->name('now-playing');
    Route::get('/stats', [RadioController::class, 'status'])->name('stats');
});

// Station-related API endpoints
Route::prefix('station/{stationId}')->name('api.station.')->group(function () {
    // Song request endpoints (AzuraCast compatible)
    Route::get('/request', [StationController::class, 'requestableList'])->name('requestable.list');
    Route::post('/request/{requestId}', [StationController::class, 'submitRequest'])->name('request.submit');

    // Playlist schedule endpoints
    Route::get('/playlists', [StationController::class, 'playlists'])->name('playlists');
});

// Search API
Route::prefix('search')->name('api.search.')->group(function () {
    Route::get('/', [SearchController::class, 'search'])->name('index');
    Route::get('/instant', [SearchController::class, 'instant'])->name('instant');
});

// Games API endpoints
Route::prefix('games')->name('api.games.')->group(function () {
    Route::get('/', [GamesApiController::class, 'index'])->name('index');
    Route::get('/search', [GamesApiController::class, 'search'])->name('search');
    Route::get('/{game:slug}', [GamesApiController::class, 'show'])->name('show');
});

// Deals API endpoints
Route::prefix('deals')->name('api.deals.')->group(function () {
    Route::get('/', [GamesApiController::class, 'deals'])->name('index');
});

// Free Games API endpoints
Route::prefix('free-games')->name('api.free-games.')->group(function () {
    Route::get('/', [GamesApiController::class, 'freeGames'])->name('index');
});

// Lyrics API endpoints
Route::middleware(['web'])->prefix('lyrics')->name('api.lyrics.')->group(function () {
    Route::get('/{songId}', [\App\Http\Controllers\LyricsController::class, 'show'])->name('show');
    Route::post('/unlock', [\App\Http\Controllers\LyricsController::class, 'unlock'])->name('unlock');
    Route::get('/status', [\App\Http\Controllers\LyricsController::class, 'status'])->name('status');
    Route::get('/search', [\App\Http\Controllers\LyricsController::class, 'search'])->name('search');
    Route::get('/popular', [\App\Http\Controllers\LyricsController::class, 'popular'])->name('popular');
});

// Media API (for admin media management)
// Uses web middleware for session-based authentication with admin middleware check
Route::middleware(['web', 'auth'])->prefix('media')->name('api.media.')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::post('/', [MediaController::class, 'upload'])->name('upload');
    Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
});
