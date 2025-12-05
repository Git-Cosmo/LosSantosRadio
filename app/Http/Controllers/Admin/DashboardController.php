<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\SongRequest;
use App\Models\User;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_requests' => SongRequest::count(),
            'pending_requests' => SongRequest::where('status', SongRequest::STATUS_PENDING)->count(),
            'played_requests' => SongRequest::where('status', SongRequest::STATUS_PLAYED)->count(),
            'total_news' => News::count(),
            'published_news' => News::where('is_published', true)->count(),
        ];

        $recentActivity = Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get();

        $recentRequests = SongRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'recentRequests' => $recentRequests,
        ]);
    }
}
