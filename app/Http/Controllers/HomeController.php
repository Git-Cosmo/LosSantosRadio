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

class HomeController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast,
        protected IcecastService $icecast
    ) {}

    /**
     * Display the new homepage.
     */
    public function index()
    {
        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();
            $history = $this->azuraCast->getHistory(5);
            $station = $this->azuraCast->getStation();
            $streamStatus = $this->icecast->getStatus();
        } catch (AzuraCastException $e) {
            return view('home', [
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

        return view('home', [
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
}
