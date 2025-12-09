<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Analytic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

/**
 * Analytics Service
 * 
 * Lightweight guest and user tracking for basic analytics.
 * Emphasizes privacy with minimal data storage and TTL-based session tracking.
 */
class AnalyticsService
{
    private const SESSION_TTL = 1800; // 30 minutes

    public function __construct(
        private readonly CacheService $cacheService
    ) {}

    /**
     * Track visitor activity.
     * 
     * @param Request $request
     * @param User|null $user
     * @return void
     */
    public function track(Request $request, ?User $user = null): void
    {
        try {
            $sessionId = $request->session()->getId();
            
            // Check if we already tracked this session recently (within 5 minutes)
            if ($this->cacheService->has(CacheService::NAMESPACE_SESSION, "tracked.{$sessionId}")) {
                // Update last activity time only
                $this->updateLastActivity($sessionId);
                return;
            }

            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());
            $agent->setHttpHeaders($request->headers->all());

            // Get device information
            $deviceType = $this->getDeviceType($agent);
            $browser = $this->getBrowserInfo($agent);
            $platform = $agent->platform() . ' ' . $agent->version($agent->platform());

            // Get IP and location (privacy-conscious)
            $ipAddress = $this->getClientIp($request);
            $location = $this->getLocationFromIp($ipAddress);

            // Store analytics record
            Analytic::create([
                'user_id' => $user?->id,
                'session_id' => $sessionId,
                'ip_address' => $this->anonymizeIp($ipAddress),
                'country_code' => $location['country_code'] ?? null,
                'country_name' => $location['country_name'] ?? null,
                'device_type' => $deviceType,
                'browser' => $browser,
                'platform' => $platform,
                'page_url' => $request->fullUrl(),
                'referrer' => $request->header('referer'),
                'last_activity_at' => now(),
            ]);

            // Cache that we tracked this session (5 minutes)
            $this->cacheService->put(CacheService::NAMESPACE_SESSION, "tracked.{$sessionId}", true, 300);
        } catch (\Exception $e) {
            Log::error('Analytics tracking failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Update last activity timestamp for a session.
     */
    private function updateLastActivity(string $sessionId): void
    {
        Analytic::where('session_id', $sessionId)
            ->latest('last_activity_at')
            ->first()
            ?->update(['last_activity_at' => now()]);
    }

    /**
     * Get device type from agent.
     */
    private function getDeviceType(Agent $agent): string
    {
        if ($agent->isPhone()) {
            return 'mobile';
        }
        if ($agent->isTablet()) {
            return 'tablet';
        }
        if ($agent->isDesktop()) {
            return 'desktop';
        }
        return 'unknown';
    }

    /**
     * Get browser information.
     */
    private function getBrowserInfo(Agent $agent): string
    {
        $browser = $agent->browser();
        $version = $agent->version($browser);
        return $browser . ($version ? " {$version}" : '');
    }

    /**
     * Get client IP address.
     */
    private function getClientIp(Request $request): string
    {
        // Check for forwarded IP (behind proxy/load balancer)
        if ($request->header('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP');
        }
        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            return trim($ips[0]);
        }
        return $request->ip() ?? '0.0.0.0';
    }

    /**
     * Anonymize IP address for privacy (remove last octet).
     */
    private function anonymizeIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';
            return implode('.', $parts);
        }
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            $parts = array_slice($parts, 0, 4);
            return implode(':', $parts) . '::';
        }

        return $ip;
    }

    /**
     * Get location information from IP address.
     * Uses a simple approach - can be enhanced with GeoIP database.
     */
    private function getLocationFromIp(string $ip): array
    {
        // For now, return empty - can be enhanced with GeoIP2 or ipapi.co
        // This avoids external API calls that could slow down requests
        return [
            'country_code' => null,
            'country_name' => null,
        ];
    }

    /**
     * Get current online users count (last 30 minutes).
     */
    public function getOnlineCount(): int
    {
        return $this->cacheService->remember(CacheService::NAMESPACE_SESSION, 'online_count', 60, function () {
            return Analytic::active()->distinct('session_id')->count('session_id');
        });
    }

    /**
     * Get analytics statistics for dashboard.
     */
    public function getStatistics(int $days = 30): array
    {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();
        
        return $this->cacheService->remember(CacheService::NAMESPACE_SESSION, "stats.{$days}days", CacheService::TTL_SHORT, function () use ($startDate, $endDate) {
            $analytics = Analytic::betweenDates($startDate, $endDate);

            return [
                'total_sessions' => $analytics->distinct('session_id')->count('session_id'),
                'total_users' => $analytics->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
                'total_guests' => $analytics->whereNull('user_id')->distinct('session_id')->count('session_id'),
                'by_device' => $analytics->select('device_type')
                    ->groupBy('device_type')
                    ->selectRaw('device_type, count(distinct session_id) as count')
                    ->pluck('count', 'device_type')
                    ->toArray(),
                'by_country' => $analytics->select('country_code', 'country_name')
                    ->whereNotNull('country_code')
                    ->groupBy('country_code', 'country_name')
                    ->selectRaw('country_code, country_name, count(distinct session_id) as count')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get()
                    ->toArray(),
                'top_browsers' => $analytics->select('browser')
                    ->whereNotNull('browser')
                    ->groupBy('browser')
                    ->selectRaw('browser, count(distinct session_id) as count')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->pluck('count', 'browser')
                    ->toArray(),
            ];
        });
    }

    /**
     * Clean up old analytics data (older than 90 days).
     */
    public function cleanup(int $days = 90): int
    {
        $date = now()->subDays($days);
        return Analytic::where('created_at', '<', $date)->delete();
    }
}
