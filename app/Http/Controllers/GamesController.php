<?php

namespace App\Http\Controllers;

use App\Models\FreeGame;
use App\Models\Game;
use App\Models\GameDeal;
use App\Models\GameStore;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GamesController extends Controller
{
    /**
     * Display games landing page.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'genre' => ['nullable', 'string', 'max:50'],
            'platform' => ['nullable', 'string', 'max:50'],
            'with_deals' => ['nullable', 'boolean'],
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

        // Filter by games with deals
        if (! empty($validated['with_deals'])) {
            $query->withDeals();
        }

        $games = $query->orderBy('release_date', 'desc')
            ->with(['deals' => function ($q) {
                $q->where('is_on_sale', true)->orderBy('savings_percent', 'desc');
            }])
            ->paginate(24);

        $topDeals = GameDeal::with('store')
            ->onSale()
            ->minSavings(50)
            ->orderBy('savings_percent', 'desc')
            ->limit(6)
            ->get();

        $freeGames = FreeGame::active()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('games.index', [
            'games' => $games,
            'topDeals' => $topDeals,
            'freeGames' => $freeGames,
            'filters' => $validated,
        ]);
    }

    /**
     * Display free games.
     */
    public function free(Request $request): View
    {
        $games = FreeGame::active()
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        return view('games.free', [
            'games' => $games,
        ]);
    }

    /**
     * Show a specific free game.
     */
    public function showFreeGame(FreeGame $game): View
    {
        $relatedGames = FreeGame::active()
            ->where('id', '!=', $game->id)
            ->where('store', $game->store)
            ->limit(4)
            ->get();

        return view('games.free-game', [
            'game' => $game,
            'relatedGames' => $relatedGames,
        ]);
    }

    /**
     * Display game deals.
     */
    public function deals(Request $request): View
    {
        // Validate input parameters to prevent injection and ensure data integrity
        $validated = $request->validate([
            'min_savings' => ['nullable', 'integer', 'min:1', 'max:100'],
            'store' => ['nullable', 'integer', 'exists:game_stores,id'],
        ]);

        $query = GameDeal::with('store')
            ->onSale()
            ->orderBy('savings_percent', 'desc');

        // Filter by minimum savings
        if (isset($validated['min_savings'])) {
            $query->minSavings((int) $validated['min_savings']);
        }

        // Filter by store
        if (isset($validated['store'])) {
            $query->where('store_id', $validated['store']);
        }

        $deals = $query->paginate(24);
        $stores = GameStore::active()->orderBy('name')->get();

        return view('games.deals', [
            'deals' => $deals,
            'stores' => $stores,
            'filters' => [
                'min_savings' => $validated['min_savings'] ?? null,
                'store' => $validated['store'] ?? null,
            ],
        ]);
    }

    /**
     * Show a specific deal.
     */
    public function showDeal(GameDeal $deal): View
    {
        $deal->load(['store', 'game']);

        $relatedDeals = GameDeal::with('store')
            ->onSale()
            ->where('id', '!=', $deal->id)
            ->where('store_id', $deal->store_id)
            ->limit(4)
            ->get();

        return view('games.deal', [
            'deal' => $deal,
            'relatedDeals' => $relatedDeals,
        ]);
    }

    /**
     * Show a specific game.
     */
    public function show(Game $game): View
    {
        $game->load(['deals' => function ($query) {
            $query->where('is_on_sale', true)->with('store')->orderBy('savings_percent', 'desc');
        }]);

        // Get related games based on shared genres
        if (empty($game->genres)) {
            $relatedGames = collect();
        } else {
            $relatedGames = Game::where('id', '!=', $game->id)
                ->where(function ($q) use ($game) {
                    foreach ($game->genres as $genre) {
                        $q->orWhereJsonContains('genres', $genre);
                    }
                })
                ->limit(4)
                ->get();
        }

        return view('games.show', [
            'game' => $game,
            'relatedGames' => $relatedGames,
        ]);
    }
}
