<?php

namespace App\Http\Controllers;

use App\Models\SongRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class SongRatingController extends Controller
{
    /**
     * Rate a song (upvote or downvote).
     */
    public function rate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'song_id' => 'required|string|max:255',
            'song_title' => 'required|string|max:255',
            'song_artist' => 'required|string|max:255',
            'rating' => 'required|integer|in:-1,1',
        ]);

        $userId = auth()->id();
        $ipAddress = $request->ip();

        // Rate limiting: max 30 ratings per minute per IP
        $rateLimitKey = 'rating:'.($userId ?? $ipAddress);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 30)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many rating attempts. Please try again later.',
            ], 429);
        }
        RateLimiter::hit($rateLimitKey, 60);

        // Check for existing rating
        $existingRating = SongRating::hasRated($validated['song_id'], $userId, $ipAddress);

        if ($existingRating) {
            if ($existingRating->rating === $validated['rating']) {
                // Same rating - remove it (toggle off)
                $existingRating->delete();
                $action = 'removed';
            } else {
                // Different rating - update it
                $existingRating->update(['rating' => $validated['rating']]);
                $action = 'updated';
            }
        } else {
            // Create new rating
            SongRating::create([
                'song_id' => $validated['song_id'],
                'song_title' => $validated['song_title'],
                'song_artist' => $validated['song_artist'],
                'user_id' => $userId,
                'ip_address' => $userId ? null : $ipAddress,
                'rating' => $validated['rating'],
            ]);
            $action = 'created';
        }

        $counts = SongRating::getCounts($validated['song_id']);

        return response()->json([
            'success' => true,
            'action' => $action,
            'data' => $counts,
        ]);
    }

    /**
     * Get rating data for a song.
     */
    public function show(Request $request, string $songId): JsonResponse
    {
        $userId = auth()->id();
        $ipAddress = $request->ip();

        $counts = SongRating::getCounts($songId);
        $userRating = SongRating::hasRated($songId, $userId, $ipAddress);

        return response()->json([
            'success' => true,
            'data' => [
                ...$counts,
                'user_rating' => $userRating?->rating,
            ],
        ]);
    }

    /**
     * Get trending songs (most liked).
     */
    public function trending(): JsonResponse
    {
        $trending = SongRating::selectRaw('song_id, song_title, song_artist, SUM(rating) as score, COUNT(*) as total_votes')
            ->groupBy('song_id', 'song_title', 'song_artist')
            ->havingRaw('SUM(rating) > 0')
            ->orderByDesc('score')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trending,
        ]);
    }
}
