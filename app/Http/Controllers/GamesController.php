<?php

namespace App\Http\Controllers;

use App\Models\FreeGame;
use App\Models\GameDeal;
use App\Models\GameStore;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GamesController extends Controller
{
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
        $deal->load('store');

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
}
