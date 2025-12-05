<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameStore extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'is_active',
        'images',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get the deals for this store.
     */
    public function deals(): HasMany
    {
        return $this->hasMany(GameDeal::class, 'store_id');
    }

    /**
     * Scope to active stores.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
