<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(Request $request): View
    {
        $timeframe = $request->get('timeframe', 'all');
        $limit = min((int) $request->get('limit', 10), 100);

        $leaders = $this->getLeaders($timeframe, $limit);

        return view('leaderboard.index', [
            'timeframe' => $timeframe,
            'limit' => $limit,
            'leaders' => $leaders,
        ]);
    }

    public function api(Request $request): JsonResponse
    {
        $timeframe = $request->get('timeframe', 'all');
        $limit = min((int) $request->get('limit', 10), 100);

        $leaders = $this->getLeaders($timeframe, $limit);

        return response()->json([
            'success' => true,
            'data' => $leaders,
        ]);
    }

    protected function getLeaders(string $timeframe, int $limit): array
    {
        $query = SongRequest::query()
            ->select('user_id', DB::raw('COUNT(*) as request_count'))
            ->whereNotNull('user_id')
            ->where('status', '!=', SongRequest::STATUS_REJECTED);

        // Apply timeframe filter
        switch ($timeframe) {
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
            ->limit($limit)
            ->get();

        // Eager load users for the results
        $leaders->load('user');

        return $leaders->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'user' => $item->user ? [
                    'name' => $item->user->name,
                    'avatar_url' => $item->user->avatar_url,
                ] : null,
                'request_count' => $item->request_count,
            ];
        })->toArray();
    }
}
