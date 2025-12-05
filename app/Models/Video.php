<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Video extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'video_url',
        'embed_url',
        'thumbnail_url',
        'category',
        'platform',
        'source',
        'source_id',
        'author',
        'upvotes',
        'views',
        'is_active',
        'posted_at',
    ];

    protected $casts = [
        'upvotes' => 'integer',
        'views' => 'integer',
        'is_active' => 'boolean',
        'posted_at' => 'datetime',
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
     * Scope to active videos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to YLYL videos.
     */
    public function scopeYlyl($query)
    {
        return $query->where('category', 'ylyl');
    }

    /**
     * Scope to clips videos.
     */
    public function scopeClips($query)
    {
        return $query->where('category', 'clips');
    }

    /**
     * Scope by platform.
     */
    public function scopePlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Increment the view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
