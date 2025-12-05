<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        // Only allow staff/admin access
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['admin', 'staff'])) {
            abort(403);
        }

        $timeframe = $request->get('timeframe', 'week');

        $analytics = $this->loadAnalytics($timeframe);

        return view('analytics.index', [
            'timeframe' => $timeframe,
            'requestStats' => $analytics['requestStats'],
            'topSongs' => $analytics['topSongs'],
            'topRequesters' => $analytics['topRequesters'],
            'dailyRequests' => $analytics['dailyRequests'],
        ]);
    }

    protected function loadAnalytics(string $timeframe): array
    {
        $cacheKey = "analytics_{$timeframe}";

        return Cache::remember($cacheKey, 300, function () use ($timeframe) {
            return [
                'requestStats' => $this->calculateRequestStats($timeframe),
                'topSongs' => $this->calculateTopSongs($timeframe),
                'topRequesters' => $this->calculateTopRequesters($timeframe),
                'dailyRequests' => $this->calculateDailyRequests($timeframe),
            ];
        });
    }

    protected function getDateRange(string $timeframe): array
    {
        return match ($timeframe) {
            'today' => [now()->startOfDay(), now()],
            'week' => [now()->subWeek(), now()],
            'month' => [now()->subMonth(), now()],
            'year' => [now()->subYear(), now()],
            default => [now()->subWeek(), now()],
        };
    }

    protected function calculateRequestStats(string $timeframe): array
    {
        [$startDate, $endDate] = $this->getDateRange($timeframe);

        $total = SongRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        $played = SongRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', SongRequest::STATUS_PLAYED)
            ->count();
        $pending = SongRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', SongRequest::STATUS_PENDING)
            ->count();
        $rejected = SongRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', SongRequest::STATUS_REJECTED)
            ->count();

        return [
            'total' => $total,
            'played' => $played,
            'pending' => $pending,
            'rejected' => $rejected,
            'success_rate' => $total > 0 ? round(($played / $total) * 100, 1) : 0,
        ];
    }

    protected function calculateTopSongs(string $timeframe): array
    {
        [$startDate, $endDate] = $this->getDateRange($timeframe);

        return SongRequest::selectRaw('song_title, song_artist, COUNT(*) as request_count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('song_title', 'song_artist')
            ->orderByDesc('request_count')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'title' => $item->song_title,
                'artist' => $item->song_artist,
                'count' => $item->request_count,
            ])
            ->toArray();
    }

    protected function calculateTopRequesters(string $timeframe): array
    {
        [$startDate, $endDate] = $this->getDateRange($timeframe);

        return SongRequest::selectRaw('user_id, COUNT(*) as request_count')
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('request_count')
            ->limit(10)
            ->with('user:id,name,avatar')
            ->get()
            ->map(fn ($item) => [
                'user' => $item->user ? [
                    'name' => $item->user->name,
                    'avatar' => $item->user->avatar_url,
                ] : null,
                'count' => $item->request_count,
            ])
            ->filter(fn ($item) => $item['user'] !== null)
            ->values()
            ->toArray();
    }

    protected function calculateDailyRequests(string $timeframe): array
    {
        [$startDate, $endDate] = $this->getDateRange($timeframe);

        return SongRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => $item->date,
                'count' => $item->count,
            ])
            ->toArray();
    }
}
