<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}

    /**
     * Get basic analytics statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $days = min(90, max(1, (int) $request->input('days', 30)));
        
        $statistics = $this->analyticsService->getStatistics($days);
        $onlineCount = $this->analyticsService->getOnlineCount();

        return response()->json([
            'success' => true,
            'data' => [
                'online_now' => $onlineCount,
                'period_days' => $days,
                'statistics' => $statistics,
            ],
        ]);
    }

    /**
     * Get online user count.
     */
    public function online(): JsonResponse
    {
        $onlineCount = $this->analyticsService->getOnlineCount();

        return response()->json([
            'success' => true,
            'data' => [
                'online_count' => $onlineCount,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }
}
