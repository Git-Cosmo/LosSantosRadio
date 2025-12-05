<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\RadioController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SongRatingController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\StationsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Main radio page
Route::get('/', [RadioController::class, 'index'])->name('home');

// News pages
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});

// Schedule page
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

// Songs page
Route::get('/songs', [SongsController::class, 'index'])->name('songs');

// Stations page
Route::get('/stations', [StationsController::class, 'index'])->name('stations');

// Radio API endpoints
Route::prefix('api/radio')->name('radio.')->group(function () {
    Route::get('/now-playing', [RadioController::class, 'nowPlaying'])->name('now-playing');
    Route::get('/history', [RadioController::class, 'history'])->name('history');
    Route::get('/status', [RadioController::class, 'status'])->name('status');
});

// Stations API endpoints
Route::prefix('api/stations')->name('stations.api.')->group(function () {
    Route::get('/', [StationsController::class, 'list'])->name('list');
    Route::get('/now-playing', [StationsController::class, 'nowPlaying'])->name('now-playing');
});

// Playlists API endpoints
Route::prefix('api/playlists')->name('playlists.api.')->group(function () {
    Route::get('/', [PlaylistsController::class, 'index'])->name('index');
    Route::get('/active', [PlaylistsController::class, 'active'])->name('active');
    Route::get('/current', [PlaylistsController::class, 'current'])->name('current');
});

// Song rating endpoints
Route::prefix('api/ratings')->name('ratings.')->group(function () {
    Route::post('/', [SongRatingController::class, 'rate'])->name('rate');
    Route::get('/song/{songId}', [SongRatingController::class, 'show'])->name('show');
    Route::get('/trending', [SongRatingController::class, 'trending'])->name('trending');
});

// Song requests
Route::prefix('requests')->name('requests.')->group(function () {
    Route::get('/', [SongRequestController::class, 'index'])->name('index');
    Route::get('/search', [SongRequestController::class, 'search'])->name('search');
    Route::get('/queue', [SongRequestController::class, 'queue'])->name('queue');
    Route::get('/limits', [SongRequestController::class, 'checkLimits'])->name('limits');
    Route::post('/', [SongRequestController::class, 'store'])->name('store');
});

/*
|--------------------------------------------------------------------------
| Social Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/{provider}', [SocialAuthController::class, 'redirect'])->name('redirect');
    Route::get('/{provider}/callback', [SocialAuthController::class, 'callback'])->name('callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Request history for logged-in users
    Route::get('/my-requests', [SongRequestController::class, 'history'])->name('requests.history');

    // Comments
    Route::post('/news/{slug}/comments', [CommentsController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentsController::class, 'destroy'])->name('comments.destroy');

    // Messaging
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessagesController::class, 'index'])->name('index');
        Route::get('/create', [MessagesController::class, 'create'])->name('create');
        Route::post('/', [MessagesController::class, 'store'])->name('store');
        Route::get('/{id}', [MessagesController::class, 'show'])->name('show');
        Route::put('/{id}', [MessagesController::class, 'update'])->name('update');
        Route::get('/api/unread', [MessagesController::class, 'unreadCount'])->name('unread');
    });

    // Unlink social accounts
    Route::delete('/auth/{provider}/unlink', [SocialAuthController::class, 'unlink'])->name('auth.unlink');

    // Profile and linked accounts
    Route::get('/profile/linked-accounts', function () {
        return view('profile.linked-accounts', [
            'socialAccounts' => auth()->user()->socialAccounts,
        ]);
    })->name('profile.linked-accounts');

    // Analytics (for staff only)
    Route::get('/analytics', function () {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['admin', 'staff'])) {
            abort(403);
        }

        return view('analytics.index');
    })->name('analytics');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Login Route (for guests)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');
