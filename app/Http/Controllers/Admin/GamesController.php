<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreeGame;
use App\Models\GameDeal;
use App\Models\GameStore;
use App\Services\CheapSharkService;
use App\Services\RedditScraperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GamesController extends Controller
{
    public function __construct(
        protected CheapSharkService $cheapShark,
        protected RedditScraperService $redditScraper
    ) {}

    /**
     * Display games dashboard.
     */
    public function index(): View
    {
        return view('admin.games.index', [
            'freeGamesCount' => FreeGame::active()->count(),
            'dealsCount' => GameDeal::onSale()->count(),
            'storesCount' => GameStore::active()->count(),
            'recentGames' => FreeGame::latest()->take(5)->get(),
            'recentDeals' => GameDeal::onSale()->latest()->take(5)->get(),
        ]);
    }

    /**
     * Display free games list.
     */
    public function freeGames(): View
    {
        $games = FreeGame::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.games.free', [
            'games' => $games,
        ]);
    }

    /**
     * Display deals list.
     */
    public function deals(): View
    {
        $deals = GameDeal::with('store')
            ->orderBy('savings_percent', 'desc')
            ->paginate(20);

        return view('admin.games.deals', [
            'deals' => $deals,
        ]);
    }

    /**
     * Display stores list.
     */
    public function stores(): View
    {
        $stores = GameStore::withCount('deals')
            ->orderBy('name')
            ->get();

        return view('admin.games.stores', [
            'stores' => $stores,
        ]);
    }

    /**
     * Show form to create a free game.
     */
    public function createFreeGame(): View
    {
        return view('admin.games.free-create');
    }

    /**
     * Store a new free game.
     */
    public function storeFreeGame(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'nullable|string|max:100',
            'store' => 'nullable|string|max:100',
            'url' => 'required|url',
            'image_url' => 'nullable|url',
            'expires_at' => 'nullable|date',
        ]);

        $validated['source'] = 'manual';
        $validated['is_active'] = true;

        FreeGame::create($validated);

        return redirect()->route('admin.games.free')
            ->with('success', 'Free game created successfully.');
    }

    /**
     * Show form to edit a free game.
     */
    public function editFreeGame(FreeGame $freeGame): View
    {
        return view('admin.games.free-edit', [
            'game' => $freeGame,
        ]);
    }

    /**
     * Update a free game.
     */
    public function updateFreeGame(Request $request, FreeGame $freeGame): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'nullable|string|max:100',
            'store' => 'nullable|string|max:100',
            'url' => 'required|url',
            'image_url' => 'nullable|url',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $freeGame->update($validated);

        return redirect()->route('admin.games.free')
            ->with('success', 'Free game updated successfully.');
    }

    /**
     * Delete a free game.
     */
    public function destroyFreeGame(FreeGame $freeGame): RedirectResponse
    {
        $freeGame->delete();

        return redirect()->route('admin.games.free')
            ->with('success', 'Free game deleted successfully.');
    }

    /**
     * Sync deals from CheapShark.
     */
    public function syncDeals(): RedirectResponse
    {
        $storesCount = $this->cheapShark->syncStores()->count();
        $dealsCount = $this->cheapShark->syncDeals();

        return redirect()->route('admin.games.deals')
            ->with('success', "Synced {$storesCount} stores and {$dealsCount} deals from CheapShark.");
    }

    /**
     * Sync free games from Reddit.
     */
    public function syncFreeGames(): RedirectResponse
    {
        $count = $this->redditScraper->syncFreeGames();

        return redirect()->route('admin.games.free')
            ->with('success', "Synced {$count} free games from Reddit.");
    }
}
