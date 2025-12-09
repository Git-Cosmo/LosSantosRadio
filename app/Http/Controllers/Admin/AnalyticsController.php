<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}

    /**
     * Display analytics dashboard.
     */
    public function index(Request $request): View
    {
        $days = $request->get('days', 30);
        
        $statistics = $this->analyticsService->getStatistics($days);
        $onlineCount = $this->analyticsService->getOnlineCount();

        return view('admin.analytics.index', [
            'statistics' => $statistics,
            'onlineCount' => $onlineCount,
            'days' => $days,
        ]);
    }
}
