<?php

namespace App\Jobs;

use App\Models\Game;
use App\Services\IgdbService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateIGDBJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 120;

    /**
     * Rate limit delay in microseconds between IGDB API requests.
     * 250ms (250000 microseconds) is used to avoid hitting IGDB rate limits.
     */
    private const RATE_LIMIT_DELAY_MICROSECONDS = 250000;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ?int $gameId = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(IgdbService $igdb): void
    {
        if (! $igdb->isConfigured()) {
            Log::warning('UpdateIGDBJob: IGDB not configured, skipping');

            return;
        }

        try {
            if ($this->gameId) {
                // Update specific game
                $this->updateGame($this->gameId, $igdb);
            } else {
                // Update games that need refreshing (older than 7 days)
                $this->updateStaleGames($igdb);
            }
        } catch (\Exception $e) {
            Log::error('UpdateIGDBJob: Failed to update IGDB data', [
                'game_id' => $this->gameId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Update a specific game.
     */
    protected function updateGame(int $gameId, IgdbService $igdb): void
    {
        $game = Game::find($gameId);

        if (! $game || ! $game->igdb_id) {
            Log::warning('UpdateIGDBJob: Game not found or missing IGDB ID', ['game_id' => $gameId]);

            return;
        }

        $igdbData = $igdb->getGameById($game->igdb_id);

        if (! $igdbData) {
            Log::warning('UpdateIGDBJob: No IGDB data found', ['igdb_id' => $game->igdb_id]);

            return;
        }

        $this->updateGameFromIGDB($game, $igdbData, $igdb);
        Log::info('UpdateIGDBJob: Updated game from IGDB', ['game_id' => $gameId]);
    }

    /**
     * Update games that haven't been updated in 7 days.
     */
    protected function updateStaleGames(IgdbService $igdb): void
    {
        $games = Game::whereNotNull('igdb_id')
            ->where('updated_at', '<', now()->subDays(7))
            ->limit(50)
            ->get();

        $updated = 0;

        foreach ($games as $game) {
            $igdbData = $igdb->getGameById($game->igdb_id);

            if ($igdbData) {
                $this->updateGameFromIGDB($game, $igdbData, $igdb);
                $updated++;

                // Rate limiting to avoid hitting IGDB API limits
                usleep(self::RATE_LIMIT_DELAY_MICROSECONDS);
            }
        }

        Log::info('UpdateIGDBJob: Updated stale games', ['count' => $updated]);
    }

    /**
     * Update game model from IGDB data.
     */
    protected function updateGameFromIGDB(Game $game, array $igdbData, IgdbService $igdb): void
    {
        $game->update([
            'title' => $igdbData['name'] ?? $game->title,
            'description' => $igdbData['summary'] ?? $game->description,
            'storyline' => $igdbData['storyline'] ?? $game->storyline,
            'cover_image' => isset($igdbData['cover']['url'])
                ? $igdb->formatCoverUrl($igdbData['cover']['url'], 'cover_big')
                : $game->cover_image,
            'screenshots' => isset($igdbData['screenshots'])
                ? array_map(fn ($s) => $igdb->formatCoverUrl($s['url'], 'screenshot_med'), $igdbData['screenshots'])
                : $game->screenshots,
            'genres' => $igdbData['genres'] ?? $game->genres,
            'platforms' => $igdbData['platforms'] ?? $game->platforms,
            'websites' => $igdbData['websites'] ?? $game->websites,
            'rating' => $igdbData['rating'] ?? $game->rating,
            'rating_count' => $igdbData['rating_count'] ?? $game->rating_count,
            'aggregated_rating' => $igdbData['aggregated_rating'] ?? $game->aggregated_rating,
            'aggregated_rating_count' => $igdbData['aggregated_rating_count'] ?? $game->aggregated_rating_count,
            'release_date' => isset($igdbData['first_release_date'])
                ? \Carbon\Carbon::createFromTimestamp($igdbData['first_release_date'])
                : $game->release_date,
            'igdb_url' => $igdbData['url'] ?? $game->igdb_url,
        ]);
    }
}
