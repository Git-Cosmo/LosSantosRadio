<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DjProfileController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SongRequestController as AdminSongRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\PollsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RadioController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SongRatingController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\StationsController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Main radio page
Route::get('/', [RadioController::class, 'index'])->name('home');

// SEO: Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// News pages
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});

// Schedule page
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

// Songs page
Route::get('/songs', [SongsController::class, 'index'])->name('songs');

// Leaderboard page
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Events pages
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventsController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventsController::class, 'show'])->name('show');
});

// Polls pages
Route::prefix('polls')->name('polls.')->group(function () {
    Route::get('/', [PollsController::class, 'index'])->name('index');
    Route::get('/{slug}', [PollsController::class, 'show'])->name('show');
    Route::post('/{poll}/vote', [PollsController::class, 'vote'])->name('vote');
    Route::get('/{poll}/results', [PollsController::class, 'results'])->name('results');
});

// Games pages
Route::prefix('games')->name('games.')->group(function () {
    Route::get('/free', [\App\Http\Controllers\GamesController::class, 'free'])->name('free');
    Route::get('/deals', [\App\Http\Controllers\GamesController::class, 'deals'])->name('deals');
    Route::get('/deals/{deal}', [\App\Http\Controllers\GamesController::class, 'showDeal'])->name('deals.show');
});

// Videos pages
Route::prefix('videos')->name('videos.')->group(function () {
    Route::get('/ylyl', [\App\Http\Controllers\VideosController::class, 'ylyl'])->name('ylyl');
    Route::get('/clips', [\App\Http\Controllers\VideosController::class, 'clips'])->name('clips');
    Route::get('/{video}', [\App\Http\Controllers\VideosController::class, 'show'])->name('show');
});

// Search (with rate limiting to prevent abuse)
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->middleware('throttle:30,1')->name('search');
Route::get('/api/search', [\App\Http\Controllers\SearchController::class, 'search'])->middleware('throttle:60,1')->name('search.api');

// User profiles (public)
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');

// Radio API endpoints with caching
Route::prefix('api/radio')->name('radio.')->middleware('cache.api:30')->group(function () {
    Route::get('/now-playing', [RadioController::class, 'nowPlaying'])->name('now-playing');
    Route::get('/history', [RadioController::class, 'history'])->name('history');
    Route::get('/status', [RadioController::class, 'status'])->name('status');
});

// Stations API endpoints with caching
Route::prefix('api/stations')->name('stations.api.')->middleware('cache.api:60')->group(function () {
    Route::get('/', [StationsController::class, 'list'])->name('list');
    Route::get('/now-playing', [StationsController::class, 'nowPlaying'])->name('now-playing');
});

// Playlists API endpoints with caching
Route::prefix('api/playlists')->name('playlists.api.')->middleware('cache.api:300')->group(function () {
    Route::get('/', [PlaylistsController::class, 'index'])->name('index');
    Route::get('/active', [PlaylistsController::class, 'active'])->name('active');
    Route::get('/current', [PlaylistsController::class, 'current'])->name('current');
});

// Song rating endpoints
Route::prefix('api/ratings')->name('ratings.')->group(function () {
    Route::post('/', [SongRatingController::class, 'rate'])->name('rate');
    Route::get('/song/{songId}', [SongRatingController::class, 'show'])->name('show');
    Route::get('/trending', [SongRatingController::class, 'trending'])->middleware('cache.api:60')->name('trending');
});

// Song requests
Route::prefix('requests')->name('requests.')->group(function () {
    Route::get('/', [SongRequestController::class, 'index'])->name('index');
    Route::get('/search', [SongRequestController::class, 'search'])->name('search');
    Route::get('/queue', [SongRequestController::class, 'queue'])->middleware('cache.api:30')->name('queue');
    Route::get('/limits', [SongRequestController::class, 'checkLimits'])->name('limits');
    Route::post('/', [SongRequestController::class, 'store'])->name('store');
});

// Leaderboard API endpoint with caching
Route::get('/api/leaderboard', [LeaderboardController::class, 'api'])->middleware('cache.api:60')->name('leaderboard.api');

/*
|--------------------------------------------------------------------------
| Social Authentication Routes
|--------------------------------------------------------------------------
|
| OAuth callbacks can be configured with either /auth/{provider}/callback
| or /login/{provider}/callback URLs. Both patterns are supported for
| flexibility with different OAuth provider configurations.
|
*/

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/{provider}', [SocialAuthController::class, 'redirect'])->name('redirect');
    Route::get('/{provider}/callback', [SocialAuthController::class, 'callback'])->name('callback');
});

// Alternative /login/{provider}/callback routes for OAuth providers
// Some OAuth applications may be configured with /login instead of /auth prefix
// Provider parameter is constrained to valid OAuth providers to avoid conflicts with the /login page
Route::prefix('login')->group(function () {
    Route::get('/{provider}', [SocialAuthController::class, 'redirect'])
        ->where('provider', 'discord|twitch|steam|battlenet');
    Route::get('/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->where('provider', 'discord|twitch|steam|battlenet');
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

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/achievements', [ProfileController::class, 'achievements'])->name('achievements');
        Route::get('/xp-history', [ProfileController::class, 'xpHistory'])->name('xp-history');
        Route::get('/linked-accounts', function () {
            return view('profile.linked-accounts', [
                'socialAccounts' => auth()->user()->socialAccounts,
            ]);
        })->name('linked-accounts');
    });

    // Unlink social accounts
    Route::delete('/auth/{provider}/unlink', [SocialAuthController::class, 'unlink'])->name('auth.unlink');

    // Analytics (for staff only)
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

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

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication (guest only)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('guest');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(AdminMiddleware::class)->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);

    // News
    Route::resource('news', AdminNewsController::class)->except(['show']);

    // Events
    Route::resource('events', AdminEventController::class)->except(['show']);

    // Polls
    Route::resource('polls', AdminPollController::class)->except(['show']);

    // DJ Profiles
    Route::get('/djs/{dj}/schedules', [DjProfileController::class, 'schedules'])->name('djs.schedules');
    Route::post('/djs/{dj}/schedules', [DjProfileController::class, 'storeSchedule'])->name('djs.schedules.store');
    Route::delete('/djs/{dj}/schedules/{schedule}', [DjProfileController::class, 'destroySchedule'])->name('djs.schedules.destroy');
    Route::resource('djs', DjProfileController::class)->except(['show']);

    // Settings
    Route::resource('settings', SettingController::class)->except(['show']);

    // Song Requests
    Route::get('/requests', [AdminSongRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{songRequest}/edit', [AdminSongRequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{songRequest}', [AdminSongRequestController::class, 'update'])->name('requests.update');
    Route::delete('/requests/{songRequest}', [AdminSongRequestController::class, 'destroy'])->name('requests.destroy');
    Route::post('/requests/{songRequest}/mark-played', [AdminSongRequestController::class, 'markPlayed'])->name('requests.mark-played');
    Route::post('/requests/{songRequest}/reject', [AdminSongRequestController::class, 'reject'])->name('requests.reject');
    Route::post('/requests/{songRequest}/move-up', [AdminSongRequestController::class, 'moveUp'])->name('requests.move-up');
    Route::post('/requests/{songRequest}/move-down', [AdminSongRequestController::class, 'moveDown'])->name('requests.move-down');

    // Activity Log
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::get('/activity/{activity}', [ActivityLogController::class, 'show'])->name('activity.show');

    // Games Admin
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\GamesController::class, 'index'])->name('index');
        Route::get('/free', [\App\Http\Controllers\Admin\GamesController::class, 'freeGames'])->name('free');
        Route::get('/free/create', [\App\Http\Controllers\Admin\GamesController::class, 'createFreeGame'])->name('free.create');
        Route::post('/free', [\App\Http\Controllers\Admin\GamesController::class, 'storeFreeGame'])->name('free.store');
        Route::get('/free/{freeGame}/edit', [\App\Http\Controllers\Admin\GamesController::class, 'editFreeGame'])->name('free.edit');
        Route::put('/free/{freeGame}', [\App\Http\Controllers\Admin\GamesController::class, 'updateFreeGame'])->name('free.update');
        Route::delete('/free/{freeGame}', [\App\Http\Controllers\Admin\GamesController::class, 'destroyFreeGame'])->name('free.destroy');
        Route::get('/deals', [\App\Http\Controllers\Admin\GamesController::class, 'deals'])->name('deals');
        Route::get('/stores', [\App\Http\Controllers\Admin\GamesController::class, 'stores'])->name('stores');
        Route::post('/sync-deals', [\App\Http\Controllers\Admin\GamesController::class, 'syncDeals'])->name('sync-deals');
        Route::post('/sync-free', [\App\Http\Controllers\Admin\GamesController::class, 'syncFreeGames'])->name('sync-free');
    });

    // Videos Admin
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\VideosController::class, 'index'])->name('index');
        Route::get('/ylyl', [\App\Http\Controllers\Admin\VideosController::class, 'ylyl'])->name('ylyl');
        Route::get('/clips', [\App\Http\Controllers\Admin\VideosController::class, 'clips'])->name('clips');
        Route::get('/create', [\App\Http\Controllers\Admin\VideosController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\VideosController::class, 'store'])->name('store');
        Route::get('/{video}/edit', [\App\Http\Controllers\Admin\VideosController::class, 'edit'])->name('edit');
        Route::put('/{video}', [\App\Http\Controllers\Admin\VideosController::class, 'update'])->name('update');
        Route::delete('/{video}', [\App\Http\Controllers\Admin\VideosController::class, 'destroy'])->name('destroy');
        Route::post('/sync', [\App\Http\Controllers\Admin\VideosController::class, 'sync'])->name('sync');
    });

    // Discord Admin
    Route::prefix('discord')->name('discord.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DiscordBotController::class, 'index'])->name('index');
        Route::post('/sync-roles', [\App\Http\Controllers\Admin\DiscordBotController::class, 'syncRoles'])->name('sync-roles');
        Route::post('/sync-users', [\App\Http\Controllers\Admin\DiscordBotController::class, 'syncUsers'])->name('sync-users');
        Route::get('/settings', [\App\Http\Controllers\Admin\DiscordBotController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\DiscordBotController::class, 'updateSettings'])->name('settings.update');
    });
});
