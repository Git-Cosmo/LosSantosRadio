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
     * Uses Laravel Scout for better search performance and relevance.
     */
    protected function performSearch(string $query, int $limit = 20): array
    {
        $results = [];

        // Search News using Scout
        $news = News::search($query)
            ->where('is_published', true)
            ->take($limit)
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

        // Search Events using Scout
        $events = Event::search($query)
            ->where('is_published', true)
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'event',
                    'title' => $item->title,
                    'url' => route('events.show', $item->slug),
                    'description' => \Str::limit(strip_tags($item->description), 150),
                    'date' => $item->starts_at?->format('M d, Y') ?? 'TBD',
                ];
            });

        $results = array_merge($results, $events->toArray());

        // Search Polls using Scout
        $polls = \App\Models\Poll::search($query)
            ->where('is_active', true)
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'poll',
                    'title' => $item->question,
                    'url' => route('polls.show', $item->slug),
                    'description' => \Str::limit(strip_tags($item->description ?? ''), 150),
                    'date' => $item->created_at->diffForHumans(),
                ];
            });

        $results = array_merge($results, $polls->toArray());

        // Search Free Games using Scout
        $games = FreeGame::search($query)
            ->where('is_active', true)
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'free_game',
                    'title' => $item->title,
                    'url' => route('games.free.show', $item),
                    'description' => \Str::limit($item->description, 150),
                    'date' => $item->created_at->diffForHumans(),
                ];
            });

        $results = array_merge($results, $games->toArray());

        // Search Game Deals using Scout
        $deals = GameDeal::search($query)
            ->where('is_on_sale', true)
            ->take($limit)
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

        // Search Videos using Scout
        $videos = Video::search($query)
            ->where('is_active', true)
            ->take($limit)
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

        // Return limited results
        return array_slice($results, 0, $limit * 2);
    }
}
