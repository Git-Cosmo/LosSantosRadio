<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lyric extends Model
{
    protected $fillable = [
        'song_id',
        'title',
        'artist',
        'lyrics',
        'source',
        'source_url',
        'is_synced',
        'synced_lyrics',
        'views_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'is_synced' => 'boolean',
        'synced_lyrics' => 'array',
        'last_viewed_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Increment the views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
        $this->update(['last_viewed_at' => now()]);
    }

    /**
     * Check if lyrics are available
     */
    public function hasLyrics(): bool
    {
        return !empty($this->lyrics);
    }

    /**
     * Get formatted lyrics (split by lines)
     */
    public function getFormattedLyricsAttribute(): array
    {
        if (empty($this->lyrics)) {
            return [];
        }

        return array_filter(explode("\n", $this->lyrics));
    }
}
