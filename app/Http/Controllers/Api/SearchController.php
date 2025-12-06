<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\FreeGame;
use App\Models\GameDeal;
use App\Models\News;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Search across multiple content types.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'in:all,news,events,games,videos'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = $validated['q'] ?? null;
        $type = $validated['type'] ?? 'all';
        $limit = $validated['limit'] ?? 20;

        if (! $query || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'results' => [],
                'total' => 0,
            ]);
        }

        $results = $this->performSearch($query, $type, $limit);

        return response()->json([
            'success' => true,
            'results' => $results,
            'total' => count($results),
        ]);
    }

    /**
     * Instant search for real-time suggestions.
     */
    public function instant(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = $validated['q'] ?? null;

        if (! $query || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'results' => [],
            ]);
        }

        $results = $this->performSearch($query, 'all', 8);

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    /**
     * Perform search across multiple models.
     * Laravel's parameter binding in Eloquent automatically prevents SQL injection.
     */
    protected function performSearch(string $query, string $type, int $limit): array
    {
        $results = [];

        // Search News
        if ($type === 'all' || $type === 'news') {
            $news = News::where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->published()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'news',
                        'id' => $item->id,
                        'title' => $item->title,
                        'url' => route('news.show', $item->slug),
                        'description' => Str::limit(strip_tags($item->content), 150),
                        'date' => $item->created_at->toIso8601String(),
                        'date_formatted' => $item->created_at->diffForHumans(),
                    ];
                });

            $results = array_merge($results, $news->toArray());
        }

        // Search Events
        if ($type === 'all' || $type === 'events') {
            $events = Event::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->published()
                ->orderBy('start_date', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'event',
                        'id' => $item->id,
                        'title' => $item->title,
                        'url' => route('events.show', $item->slug),
                        'description' => Str::limit(strip_tags($item->description), 150),
                        'date' => $item->start_date?->toIso8601String(),
                        'date_formatted' => $item->start_date?->format('M d, Y') ?? 'TBD',
                    ];
                });

            $results = array_merge($results, $events->toArray());
        }

        // Search Games
        if ($type === 'all' || $type === 'games') {
            // Free Games
            $games = FreeGame::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->active()
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'free_game',
                        'id' => $item->id,
                        'title' => $item->title,
                        'url' => route('games.free'),
                        'description' => Str::limit($item->description, 150),
                        'date' => $item->created_at->toIso8601String(),
                        'date_formatted' => $item->created_at->diffForHumans(),
                    ];
                });

            $results = array_merge($results, $games->toArray());

            // Game Deals
            $deals = GameDeal::where('title', 'like', "%{$query}%")
                ->onSale()
                ->orderBy('savings_percent', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'deal',
                        'id' => $item->id,
                        'title' => $item->title,
                        'url' => route('games.deals.show', $item),
                        'description' => "{$item->formatted_savings} - \${$item->sale_price}",
                        'date' => $item->updated_at->toIso8601String(),
                        'date_formatted' => $item->updated_at->diffForHumans(),
                    ];
                });

            $results = array_merge($results, $deals->toArray());
        }

        // Search Videos
        if ($type === 'all' || $type === 'videos') {
            $videos = Video::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->active()
                ->orderBy('posted_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => 'video',
                        'id' => $item->id,
                        'title' => $item->title,
                        'url' => route('videos.show', $item),
                        'description' => Str::limit($item->description, 150),
                        'date' => $item->posted_at?->toIso8601String(),
                        'date_formatted' => $item->posted_at?->diffForHumans() ?? 'Unknown',
                    ];
                });

            $results = array_merge($results, $videos->toArray());
        }

        // Limit total results to double the requested limit since we search across
        // multiple content types and want to provide a reasonable spread of results
        // without returning an overwhelming number for a single query
        $maxTotalResults = $limit * 2;

        return array_slice($results, 0, $maxTotalResults);
    }
}
