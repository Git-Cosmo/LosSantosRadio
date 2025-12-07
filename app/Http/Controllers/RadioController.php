<?php

namespace App\Http\Controllers;

use App\Exceptions\AzuraCastException;
use App\Models\Event;
use App\Models\FreeGame;
use App\Models\GameDeal;
use App\Models\News;
use App\Models\Poll;
use App\Services\AzuraCastService;
use App\Services\IcecastService;
use Illuminate\Http\JsonResponse;

class RadioController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast,
        protected IcecastService $icecast
    ) {}

    /**
     * Display the main radio page.
     */
    public function index()
    {
        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();
            $history = $this->azuraCast->getHistory(10);
            $station = $this->azuraCast->getStation();
            $streamStatus = $this->icecast->getStatus();
        } catch (AzuraCastException $e) {
            return view('radio.index', [
                'error' => 'Unable to connect to the radio station. Please try again later.',
                'nowPlaying' => null,
                'history' => collect(),
                'station' => null,
                'streamStatus' => $this->icecast->getStatus(),
                'recentNews' => collect(),
                'upcomingEvents' => collect(),
                'activePolls' => collect(),
                'topGameDeals' => collect(),
                'freeGames' => collect(),
            ]);
        }

        // Fetch additional homepage content
        $recentNews = News::published()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        $upcomingEvents = Event::upcoming()
            ->withCount('likes')
            ->orderBy('starts_at', 'asc')
            ->limit(3)
            ->get();

        $activePolls = Poll::active()
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        // Fetch game deals and free games for enhanced homepage
        try {
            $topGameDeals = GameDeal::onSale()
                ->minSavings(50)
                ->orderBy('savings_percent', 'desc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $topGameDeals = collect();
        }

        try {
            $freeGames = FreeGame::active()
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $freeGames = collect();
        }

        return view('radio.index', [
            'nowPlaying' => $nowPlaying,
            'history' => $history,
            'station' => $station,
            'streamStatus' => $streamStatus,
            'streamUrl' => $this->icecast->getStreamUrl(),
            'recentNews' => $recentNews,
            'upcomingEvents' => $upcomingEvents,
            'activePolls' => $activePolls,
            'topGameDeals' => $topGameDeals,
            'freeGames' => $freeGames,
        ]);
    }

    /**
     * Get now playing data as JSON (for AJAX updates).
     */
    public function nowPlaying(): JsonResponse
    {
        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();

            return response()->json([
                'success' => true,
                'data' => $nowPlaying->toArray(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch now playing data.',
            ], 503);
        }
    }

    /**
     * Get song history as JSON.
     */
    public function history(int $limit = 20): JsonResponse
    {
        try {
            $history = $this->azuraCast->getHistory($limit);

            return response()->json([
                'success' => true,
                'data' => $history->map(fn ($item) => $item->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch song history.',
            ], 503);
        }
    }

    /**
     * Get stream status as JSON.
     */
    public function status(): JsonResponse
    {
        $status = $this->icecast->getStatus();

        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();
            $status['listeners'] = max($status['listeners'], $nowPlaying->listeners);
        } catch (AzuraCastException) {
            // Keep Icecast data as fallback
        }

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }
}
