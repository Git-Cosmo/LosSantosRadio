<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SongRating extends Model
{
    protected $fillable = [
        'song_id',
        'song_title',
        'song_artist',
        'user_id',
        'ip_address',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * Get the user who made the rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get upvotes for a song.
     */
    public function scopeUpvotes($query, string $songId)
    {
        return $query->where('song_id', $songId)->where('rating', 1);
    }

    /**
     * Scope to get downvotes for a song.
     */
    public function scopeDownvotes($query, string $songId)
    {
        return $query->where('song_id', $songId)->where('rating', -1);
    }

    /**
     * Get the score for a song (upvotes - downvotes).
     */
    public static function getScore(string $songId): int
    {
        return static::where('song_id', $songId)->sum('rating');
    }

    /**
     * Get rating counts for a song.
     */
    public static function getCounts(string $songId): array
    {
        $ratings = static::where('song_id', $songId)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return [
            'upvotes' => $ratings[1] ?? 0,
            'downvotes' => $ratings[-1] ?? 0,
            'score' => ($ratings[1] ?? 0) - ($ratings[-1] ?? 0),
        ];
    }

    /**
     * Check if a user or IP has already rated a song.
     * Returns the user's rating first if they are authenticated,
     * otherwise returns any guest rating from the same IP.
     */
    public static function hasRated(string $songId, ?int $userId, ?string $ipAddress): ?static
    {
        // First check for authenticated user's rating
        if ($userId) {
            $userRating = static::where('song_id', $songId)
                ->where('user_id', $userId)
                ->first();

            if ($userRating) {
                return $userRating;
            }
        }

        // Then check for guest rating by IP (only if not authenticated)
        if (! $userId && $ipAddress) {
            return static::where('song_id', $songId)
                ->whereNull('user_id')
                ->where('ip_address', $ipAddress)
                ->first();
        }

        return null;
    }
}
