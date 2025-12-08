<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Game extends Model
{
    use HasSlug, Searchable;

    protected $fillable = [
        'igdb_id',
        'title',
        'slug',
        'description',
        'storyline',
        'cover_image',
        'screenshots',
        'genres',
        'platforms',
        'websites',
        'rating',
        'rating_count',
        'aggregated_rating',
        'aggregated_rating_count',
        'release_date',
        'igdb_url',
    ];

    protected $casts = [
        'igdb_id' => 'integer',
        'screenshots' => 'array',
        'genres' => 'array',
        'platforms' => 'array',
        'websites' => 'array',
        'rating' => 'decimal:2',
        'rating_count' => 'integer',
        'aggregated_rating' => 'decimal:2',
        'aggregated_rating_count' => 'integer',
        'release_date' => 'datetime',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the deals for this game.
     */
    public function deals(): HasMany
    {
        return $this->hasMany(GameDeal::class, 'game_id');
    }

    /**
     * Get active deals for this game.
     */
    public function activeDeals(): HasMany
    {
        return $this->deals()->where('is_on_sale', true);
    }

    /**
     * Get the best deal for this game.
     */
    public function bestDeal()
    {
        return $this->activeDeals()
            ->orderBy('savings_percent', 'desc')
            ->orderBy('sale_price', 'asc')
            ->first();
    }

    /**
     * Scope to games with deals.
     */
    public function scopeWithDeals($query)
    {
        return $query->whereHas('deals', function ($q) {
            $q->where('is_on_sale', true);
        });
    }

    /**
     * Scope to recent games.
     */
    public function scopeRecent($query, int $months = 6)
    {
        return $query->where('release_date', '>=', now()->subMonths($months));
    }

    /**
     * Scope to highly rated games.
     */
    public function scopeHighlyRated($query, int $minRating = 75)
    {
        return $query->where('rating', '>=', $minRating)
            ->orWhere('aggregated_rating', '>=', $minRating);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => strip_tags($this->description ?? ''),
            'genres' => $this->genres,
            'platforms' => $this->platforms,
        ];
    }
}
