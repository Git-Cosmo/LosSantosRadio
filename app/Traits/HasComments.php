<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Get all comments for this model.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get approved comments for this model.
     */
    public function approvedComments(): MorphMany
    {
        return $this->comments()->approved();
    }

    /**
     * Get top-level comments (not replies) for this model.
     */
    public function topLevelComments(): MorphMany
    {
        return $this->comments()->topLevel();
    }

    /**
     * Get the comment count for this model.
     */
    public function getCommentCountAttribute(): int
    {
        return $this->comments()->approved()->count();
    }
}
