<?php

namespace App\Livewire;

use App\Models\SongRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Leaderboard extends Component
{
    public string $timeframe = 'all';

    public int $limit = 10;

    public function mount(string $timeframe = 'all', int $limit = 10): void
    {
        $this->timeframe = $timeframe;
        $this->limit = $limit;
    }

    public function setTimeframe(string $timeframe): void
    {
        $this->timeframe = $timeframe;
    }

    public function getLeaders(): array
    {
        $query = SongRequest::query()
            ->select('user_id', DB::raw('COUNT(*) as request_count'))
            ->whereNotNull('user_id')
            ->where('status', '!=', SongRequest::STATUS_REJECTED);

        // Apply timeframe filter
        switch ($this->timeframe) {
            case 'today':
                $query->where('created_at', '>=', now()->startOfDay());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->startOfMonth());
                break;
        }

        $leaders = $query->groupBy('user_id')
            ->orderByDesc('request_count')
            ->limit($this->limit)
            ->with('user')
            ->get();

        return $leaders->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'user' => $item->user,
                'request_count' => $item->request_count,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.leaderboard', [
            'leaders' => $this->getLeaders(),
        ]);
    }
}
