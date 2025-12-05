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
        $query = GameDeal::with('store')
            ->onSale()
            ->orderBy('savings_percent', 'desc');

        // Filter by minimum savings
        if ($request->filled('min_savings')) {
            $query->minSavings((int) $request->min_savings);
        }

        // Filter by store
        if ($request->filled('store')) {
            $query->where('store_id', $request->store);
        }

        $deals = $query->paginate(24);
        $stores = GameStore::active()->orderBy('name')->get();

        return view('games.deals', [
            'deals' => $deals,
            'stores' => $stores,
            'filters' => [
                'min_savings' => $request->min_savings,
                'store' => $request->store,
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
