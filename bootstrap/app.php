<?php

use App\Http\Middleware\ComingSoonMiddleware;
use App\Models\MediaItem;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            // Custom route model binding for MediaItem to resolve by slug
            Route::bind('mediaItem', function (string $value) {
                return MediaItem::where('slug', $value)->firstOrFail();
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'cache.api' => \App\Http\Middleware\CacheApiResponse::class,
        ]);

        // Add Coming Soon middleware to web routes
        $middleware->web(append: [
            ComingSoonMiddleware::class,
            \App\Http\Middleware\TrackAnalytics::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
