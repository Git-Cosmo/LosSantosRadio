<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $maxAge = '30'): Response
    {
        $maxAgeInt = (int) $maxAge;
        $response = $next($request);

        // Only cache successful JSON responses
        if ($response instanceof JsonResponse && $response->getStatusCode() === 200) {
            $response->headers->set('Cache-Control', "public, max-age={$maxAgeInt}");
            $response->headers->set('Vary', 'Accept');
        }

        return $response;
    }
}
