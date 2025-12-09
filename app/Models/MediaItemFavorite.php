<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaItemFavorite extends Model
{
    protected $fillable = [
        'media_item_id',
        'user_id',
    ];

    /**
     * Get the media item this favorite belongs to.
     */
    public function mediaItem(): BelongsTo
    {
        return $this->belongsTo(MediaItem::class);
    }

    /**
     * Get the user who favorited this item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
