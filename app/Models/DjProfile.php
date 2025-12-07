<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class DjProfile extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id',
        'stage_name',
        'slug',
        'bio',
        'avatar',
        'cover_image',
        'social_links',
        'genres',
        'show_name',
        'show_description',
        'is_active',
        'is_featured',
        'total_shows',
        'total_listeners',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'genres' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('stage_name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DjSchedule::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return $this->user->avatar_url;
    }

    public function getFormattedGenresAttribute(): string
    {
        return $this->genres ? implode(', ', $this->genres) : '';
    }
}
