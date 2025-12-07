<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FreeGame;
use App\Models\Game;
use App\Models\GameDeal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GamesApiController extends Controller
{
    /**
     * List all games with pagination and filters.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:100'],
            'genre' => ['nullable', 'string', 'max:50'],
            'platform' => ['nullable', 'string', 'max:50'],
            'with_deals' => ['nullable', 'boolean'],
            'min_rating' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sort' => ['nullable', 'string', 'in:rating,release_date,title'],
            'order' => ['nullable', 'string', 'in:asc,desc'],
        ]);

        $query = Game::query();

        // Search
        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by genre
        if (! empty($validated['genre'])) {
            $query->whereJsonContains('genres', ['name' => $validated['genre']]);
        }

        // Filter by platform
        if (! empty($validated['platform'])) {
            $query->whereJsonContains('platforms', ['name' => $validated['platform']]);
        }

        // Filter by games with deals
        if (! empty($validated['with_deals'])) {
            $query->withDeals();
        }

        // Filter by minimum rating
        if (isset($validated['min_rating'])) {
            $query->highlyRated($validated['min_rating']);
        }

        // Sorting
        $sort = $validated['sort'] ?? 'release_date';
        $order = $validated['order'] ?? 'desc';
        $query->orderBy($sort, $order);

        $perPage = $validated['per_page'] ?? 20;
        $games = $query->paginate($perPage);

        return response()->json($games);
    }

    /**
     * Get a specific game by slug.
     */
    public function show(Game $game): JsonResponse
    {
        $game->load(['deals' => function ($query) {
            $query->where('is_on_sale', true)->with('store');
        }]);

        return response()->json($game);
    }

    /**
     * List all active deals with filters.
     */
    public function deals(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'min_savings' => ['nullable', 'integer', 'min:1', 'max:100'],
            'store_id' => ['nullable', 'integer', 'exists:game_stores,id'],
            'sort' => ['nullable', 'string', 'in:savings_percent,sale_price,title'],
            'order' => ['nullable', 'string', 'in:asc,desc'],
        ]);

        $query = GameDeal::with(['store', 'game'])->onSale();

        // Filter by minimum savings
        if (isset($validated['min_savings'])) {
            $query->minSavings($validated['min_savings']);
        }

        // Filter by store
        if (isset($validated['store_id'])) {
            $query->where('store_id', $validated['store_id']);
        }

        // Sorting
        $sort = $validated['sort'] ?? 'savings_percent';
        $order = $validated['order'] ?? 'desc';
        $query->orderBy($sort, $order);

        $perPage = $validated['per_page'] ?? 20;
        $deals = $query->paginate($perPage);

        return response()->json($deals);
    }

    /**
     * List all active free games.
     */
    public function freeGames(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'platform' => ['nullable', 'string', 'max:50'],
            'store' => ['nullable', 'string', 'max:50'],
        ]);

        $query = FreeGame::active();

        // Filter by platform
        if (! empty($validated['platform'])) {
            $query->where('platform', $validated['platform']);
        }

        // Filter by store
        if (! empty($validated['store'])) {
            $query->where('store', $validated['store']);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $validated['per_page'] ?? 20;
        $games = $query->paginate($perPage);

        return response()->json($games);
    }

    /**
     * Search games API endpoint.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'max:100'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = $validated['q'];
        $limit = $validated['limit'] ?? 10;

        // Search using Scout
        $games = Game::search($query)
            ->take($limit)
            ->get()
            ->map(function ($game) {
                return [
                    'id' => $game->id,
                    'title' => $game->title,
                    'slug' => $game->slug,
                    'cover_image' => $game->cover_image,
                    'rating' => $game->rating,
                    'release_date' => $game->release_date?->format('Y-m-d'),
                ];
            });

        return response()->json(['results' => $games]);
    }
}

