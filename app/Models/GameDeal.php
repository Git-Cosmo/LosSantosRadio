<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class GameDeal extends Model
{
    use Searchable;

    protected $fillable = [
        'deal_id',
        'title',
        'slug',
        'sale_price',
        'normal_price',
        'savings_percent',
        'metacritic_score',
        'thumb',
        'store_id',
        'external_game_id',
        'is_on_sale',
        'deal_rating',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'normal_price' => 'decimal:2',
        'savings_percent' => 'integer',
        'metacritic_score' => 'decimal:2',
        'is_on_sale' => 'boolean',
        'deal_rating' => 'datetime',
    ];

    /**
     * Get the store for this deal.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(GameStore::class, 'store_id');
    }

    /**
     * Scope to active deals.
     */
    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true);
    }

    /**
     * Scope to deals with minimum savings.
     */
    public function scopeMinSavings($query, int $percent)
    {
        return $query->where('savings_percent', '>=', $percent);
    }

    /**
     * Get the formatted savings string.
     */
    public function getFormattedSavingsAttribute(): string
    {
        return $this->savings_percent.'% off';
    }

    /**
     * Get the CheapShark deal URL.
     */
    public function getDealUrlAttribute(): string
    {
        return 'https://www.cheapshark.com/redirect?dealID='.$this->deal_id;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'deal_id';
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
            'sale_price' => $this->sale_price,
            'savings_percent' => $this->savings_percent,
            'metacritic_score' => $this->metacritic_score,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->is_on_sale;
    }
}
