<?php

namespace App\Livewire;

use App\Models\SongRequest;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ListenerAnalytics extends Component
{
    public string $timeframe = 'week';

    public array $requestStats = [];

    public array $topSongs = [];

    public array $topRequesters = [];

    public array $dailyRequests = [];

    public function mount(): void
    {
        $this->loadAnalytics();
    }

    public function setTimeframe(string $timeframe): void
    {
        $this->timeframe = $timeframe;
        $this->loadAnalytics();
    }

    public function loadAnalytics(): void
    {
        $cacheKey = "analytics_{$this->timeframe}";

        $analytics = Cache::remember($cacheKey, 300, function () {
            return [
                'requestStats' => $this->calculateRequestStats(),
                'topSongs' => $this->calculateTopSongs(),
                'topRequesters' => $this->calculateTopRequesters(),
                'dailyRequests' => $this->calculateDailyRequests(),
            ];
        });

        $this->requestStats = $analytics['requestStats'];
        $this->topSongs = $analytics['topSongs'];
        $this->topRequesters = $analytics['topRequesters'];
        $this->dailyRequests = $analytics['dailyRequests'];
    }

    protected function getDateRange(): array
    {
        return match ($this->timeframe) {
            'today' => [now()->startOfDay(), now()],
            'week' => [now()->subWeek(), now()],
            'month' => [now()->subMonth(), now()],
            'year' => [now()->subYear(), now()],
            default => [now()->subWeek(), now()],
        };
    }

    protected function calculateRequestStats(): array
    {
        [$startDate, $endDate] = $this->getDateRange();

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

    protected function calculateTopSongs(): array
    {
        [$startDate, $endDate] = $this->getDateRange();

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

    protected function calculateTopRequesters(): array
    {
        [$startDate, $endDate] = $this->getDateRange();

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

    protected function calculateDailyRequests(): array
    {
        [$startDate, $endDate] = $this->getDateRange();

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

    public function render()
    {
        return view('livewire.listener-analytics');
    }
}
