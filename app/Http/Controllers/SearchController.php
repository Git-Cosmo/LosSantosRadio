<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\FreeGame;
use App\Models\GameDeal;
use App\Models\News;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Display the search page.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = $validated['q'] ?? null;
        $results = [];

        if ($query && strlen($query) >= 2) {
            $results = $this->performSearch($query);
        }

        return view('search.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * API endpoint for search.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = $validated['q'] ?? null;

        if (! $query || strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = $this->performSearch($query, 10);

        return response()->json(['results' => $results]);
    }

    /**
     * Perform a search across multiple models.
     * Note: This uses basic LIKE queries. For better performance at scale,
     * consider implementing Laravel Scout with Meilisearch or database full-text search.
     */
    protected function performSearch(string $query, int $limit = 20): array
    {
        // Sanitize the search query - escape special characters for LIKE
        $escapedQuery = str_replace(['%', '_'], ['\%', '\_'], $query);
        $results = [];

        // Search News
        $news = News::where('title', 'like', "%{$escapedQuery}%")
            ->orWhere('content', 'like', "%{$escapedQuery}%")
            ->published()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'news',
                    'title' => $item->title,
                    'url' => route('news.show', $item->slug),
                    'description' => \Str::limit(strip_tags($item->content), 150),
                    'date' => $item->created_at->diffForHumans(),
                ];
            });

        $results = array_merge($results, $news->toArray());

        // Search Events
        $events = Event::where('title', 'like', "%{$escapedQuery}%")
            ->orWhere('description', 'like', "%{$escapedQuery}%")
            ->published()
            ->orderBy('start_date', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'event',
                    'title' => $item->title,
                    'url' => route('events.show', $item->slug),
                    'description' => \Str::limit(strip_tags($item->description), 150),
                    'date' => $item->start_date?->format('M d, Y') ?? 'TBD',
                ];
            });

        $results = array_merge($results, $events->toArray());

        // Search Free Games
        $games = FreeGame::where('title', 'like', "%{$escapedQuery}%")
            ->orWhere('description', 'like', "%{$escapedQuery}%")
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'free_game',
                    'title' => $item->title,
                    'url' => route('games.free'),
                    'description' => \Str::limit($item->description, 150),
                    'date' => $item->created_at->diffForHumans(),
                ];
            });

        $results = array_merge($results, $games->toArray());

        // Search Game Deals
        $deals = GameDeal::where('title', 'like', "%{$escapedQuery}%")
            ->onSale()
            ->orderBy('savings_percent', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'deal',
                    'title' => $item->title,
                    'url' => route('games.deals.show', $item),
                    'description' => "{$item->formatted_savings} - \${$item->sale_price}",
                    'date' => $item->updated_at->diffForHumans(),
                ];
            });

        $results = array_merge($results, $deals->toArray());

        // Search Videos
        $videos = Video::where('title', 'like', "%{$escapedQuery}%")
            ->orWhere('description', 'like', "%{$escapedQuery}%")
            ->active()
            ->orderBy('posted_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'video',
                    'title' => $item->title,
                    'url' => route('videos.show', $item),
                    'description' => \Str::limit($item->description, 150),
                    'date' => $item->posted_at?->diffForHumans() ?? 'Unknown',
                ];
            });

        $results = array_merge($results, $videos->toArray());

        // Sort by relevance (for now, just return as is)
        return array_slice($results, 0, $limit * 2);
    }
}
