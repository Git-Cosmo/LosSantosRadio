<?php

namespace App\Http\Middleware;

use App\Services\AnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests to avoid tracking API calls and form submissions
        if ($request->isMethod('GET') && !$request->is('api/*')) {
            try {
                $this->analyticsService->track($request, $request->user());
            } catch (\Exception $e) {
                // Silently fail - analytics should never break the application
                \Log::debug('Analytics tracking failed', ['error' => $e->getMessage()]);
            }
        }

        return $next($request);
    }
}
