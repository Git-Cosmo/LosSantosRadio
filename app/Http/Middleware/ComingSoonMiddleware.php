<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ComingSoonMiddleware
{
    /**
     * Routes that should be accessible even in coming soon mode.
     *
     * @var array<string>
     */
    protected array $allowedRoutes = [
        'coming-soon',
        'api/radio/*',
        'admin/*',
        'login',
        'logout',
        'auth/*',
        'login/*',
    ];

    /**
     * Handle an incoming request.
     *
     * When COMINGSOON env variable is true, redirect all users to the coming soon page.
     * Admin users can bypass this restriction.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if coming soon mode is enabled
        if (! config('app.coming_soon', false)) {
            return $next($request);
        }

        // Allow admin users to bypass
        if (Auth::check()) {
            try {
                $user = Auth::user();
                if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['admin', 'staff'])) {
                    return $next($request);
                }
            } catch (\Exception $e) {
                // If role checking fails, continue to coming soon page
                // This ensures the site doesn't break if roles aren't configured
            }
        }

        // Check if the current route is in the allowed routes
        foreach ($this->allowedRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }

        // Get the stream URL for the coming soon page
        $streamUrl = $this->getStreamUrl();

        return response()->view('coming-soon', [
            'streamUrl' => $streamUrl,
        ]);
    }

    /**
     * Get the stream URL from the Icecast configuration.
     */
    protected function getStreamUrl(): string
    {
        $protocol = config('services.icecast.ssl', false) ? 'https' : 'http';
        $host = config('services.icecast.host', 'localhost');
        $port = config('services.icecast.port', 8000);
        $mount = config('services.icecast.mount', '/stream');

        return "{$protocol}://{$host}:{$port}{$mount}";
    }
}
