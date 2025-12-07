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
        protected RedditScraperService $redditScraper,
        protected \App\Services\IgdbService $igdb
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
            'gamesCount' => \App\Models\Game::count(),
            'recentGames' => FreeGame::latest()->take(5)->get(),
            'recentDeals' => GameDeal::onSale()->latest()->take(5)->get(),
            'igdbConfigured' => $this->igdb->isConfigured(),
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

    /**
     * Search and import game from IGDB.
     */
    public function searchIgdb(Request $request): \Illuminate\Http\JsonResponse
    {
        if (! $this->igdb->isConfigured()) {
            return response()->json([
                'error' => 'IGDB API is not configured. Please set IGDB_CLIENT_ID and IGDB_CLIENT_SECRET in .env',
            ], 400);
        }

        $validated = $request->validate([
            'query' => 'required|string|max:100',
        ]);

        $results = $this->igdb->searchGames($validated['query'], 10);

        return response()->json(['results' => $results]);
    }

    /**
     * Import game from IGDB.
     */
    public function importFromIgdb(Request $request): RedirectResponse
    {
        if (! $this->igdb->isConfigured()) {
            return redirect()->back()->with('error', 'IGDB API is not configured.');
        }

        $validated = $request->validate([
            'igdb_id' => 'required|integer',
        ]);

        // Check if game already exists
        $existing = \App\Models\Game::where('igdb_id', $validated['igdb_id'])->first();
        if ($existing) {
            return redirect()->route('admin.games.index')
                ->with('info', "Game '{$existing->title}' already exists.");
        }

        $igdbData = $this->igdb->getGameById($validated['igdb_id']);

        if (! $igdbData) {
            return redirect()->back()->with('error', 'Failed to fetch game data from IGDB.');
        }

        // Create game
        $game = \App\Models\Game::create([
            'igdb_id' => $igdbData['id'],
            'title' => $igdbData['name'],
            'description' => $igdbData['summary'] ?? null,
            'storyline' => $igdbData['storyline'] ?? null,
            'cover_image' => isset($igdbData['cover']['url'])
                ? $this->igdb->formatCoverUrl($igdbData['cover']['url'], 'cover_big')
                : null,
            'screenshots' => isset($igdbData['screenshots'])
                ? array_map(fn ($s) => $this->igdb->formatCoverUrl($s['url'], 'screenshot_med'), $igdbData['screenshots'])
                : null,
            'genres' => $igdbData['genres'] ?? null,
            'platforms' => $igdbData['platforms'] ?? null,
            'websites' => $igdbData['websites'] ?? null,
            'rating' => $igdbData['rating'] ?? null,
            'rating_count' => $igdbData['rating_count'] ?? null,
            'aggregated_rating' => $igdbData['aggregated_rating'] ?? null,
            'aggregated_rating_count' => $igdbData['aggregated_rating_count'] ?? null,
            'release_date' => isset($igdbData['first_release_date'])
                ? \Carbon\Carbon::createFromTimestamp($igdbData['first_release_date'])
                : null,
            'igdb_url' => $igdbData['url'] ?? null,
        ]);

        return redirect()->route('admin.games.index')
            ->with('success', "Game '{$game->title}' imported from IGDB.");
    }
}
