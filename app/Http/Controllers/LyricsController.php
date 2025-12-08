<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use App\Services\LyricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LyricsController extends Controller
{
    public function __construct(
        protected LyricsService $lyricsService,
        protected CacheService $cacheService
    ) {}

    /**
     * Get lyrics for a song
     */
    public function show(Request $request, string $songId): JsonResponse
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        // Check if user can view lyrics
        $canView = $this->lyricsService->canViewLyrics($userId, $sessionId);

        if (!$canView['can_view']) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the free lyrics limit.',
                'reason' => $canView['reason'],
                'required_action' => $canView['required_action'] ?? null,
            ], 403);
        }

        // Get song info from request
        $title = $request->input('title', 'Unknown');
        $artist = $request->input('artist', 'Unknown');

        // Get lyrics
        $lyric = $this->lyricsService->getLyrics($songId, $title, $artist);

        // Track view for guests
        if (!$userId) {
            $this->lyricsService->trackGuestView($sessionId, $songId);
        }

        if (!$lyric || !$lyric->hasLyrics()) {
            return response()->json([
                'success' => false,
                'message' => 'Lyrics not found for this song.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'lyrics' => [
                'id' => $lyric->id,
                'song_id' => $lyric->song_id,
                'title' => $lyric->title,
                'artist' => $lyric->artist,
                'lyrics' => $lyric->formatted_lyrics,
                'source' => $lyric->source,
                'source_url' => $lyric->source_url,
                'is_synced' => $lyric->is_synced,
            ],
            'remaining' => $canView['remaining'] ?? null,
        ]);
    }

    /**
     * Unlock lyrics for guest after time-based requirement
     */
    public function unlock(Request $request): JsonResponse
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        // Only guests need to unlock
        if ($userId) {
            return response()->json([
                'success' => false,
                'message' => 'Registered users have unlimited access.',
            ], 400);
        }

        // Unlock lyrics for this guest
        $this->lyricsService->unlockForGuest($sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Lyrics unlocked! You now have unlimited access for 10 minutes.',
        ]);
    }

    /**
     * Get lyrics viewing status for current user/guest
     */
    public function status(Request $request): JsonResponse
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        $canView = $this->lyricsService->canViewLyrics($userId, $sessionId);

        return response()->json([
            'can_view' => $canView['can_view'],
            'reason' => $canView['reason'],
            'remaining' => $canView['remaining'] ?? null,
            'is_unlocked' => $canView['reason'] === 'unlocked',
            'is_registered' => $userId !== null,
        ]);
    }

    /**
     * Search lyrics
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search query must be at least 2 characters.',
            ], 400);
        }

        $results = $this->lyricsService->searchLyrics($query);

        return response()->json([
            'success' => true,
            'results' => $results->map(function ($lyric) {
                return [
                    'song_id' => $lyric->song_id,
                    'title' => $lyric->title,
                    'artist' => $lyric->artist,
                    'views_count' => $lyric->views_count,
                ];
            }),
        ]);
    }

    /**
     * Get popular lyrics
     */
    public function popular(): JsonResponse
    {
        $lyrics = $this->lyricsService->getPopularLyrics(10);

        return response()->json([
            'success' => true,
            'lyrics' => $lyrics->map(function ($lyric) {
                return [
                    'song_id' => $lyric->song_id,
                    'title' => $lyric->title,
                    'artist' => $lyric->artist,
                    'views_count' => $lyric->views_count,
                ];
            }),
        ]);
    }
}
