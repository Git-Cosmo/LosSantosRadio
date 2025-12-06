<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RssFeed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'category',
        'description',
        'is_active',
        'last_fetched_at',
        'fetch_interval',
        'articles_imported',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_fetched_at' => 'datetime',
        'fetch_interval' => 'integer',
        'articles_imported' => 'integer',
    ];

    /**
     * Check if the feed is due for fetching.
     */
    public function isDueForFetch(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if (! $this->last_fetched_at) {
            return true;
        }

        return $this->last_fetched_at->addSeconds($this->fetch_interval)->isPast();
    }

    /**
     * Mark feed as fetched.
     */
    public function markAsFetched(int $articlesImported = 0): void
    {
        $this->update([
            'last_fetched_at' => now(),
            'articles_imported' => $this->articles_imported + $articlesImported,
        ]);
    }
}
