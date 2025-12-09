<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaItem extends Model implements HasMedia
{
    use InteractsWithMedia, Searchable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'media_category_id',
        'media_subcategory_id',
        'title',
        'slug',
        'description',
        'content',
        'version',
        'file_size',
        'downloads_count',
        'views_count',
        'rating',
        'ratings_count',
        'is_featured',
        'is_approved',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'downloads_count' => 'integer',
        'views_count' => 'integer',
        'rating' => 'decimal:2',
        'ratings_count' => 'integer',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Automatically generate slug from title.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->title);
            }
            if (empty($item->published_at) && $item->is_approved) {
                $item->published_at = now();
            }
        });

        static::updating(function ($item) {
            if ($item->is_approved && empty($item->published_at)) {
                $item->published_at = now();
            }
        });
    }

    /**
     * Get the user who uploaded this media item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category this media item belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }

    /**
     * Get the subcategory this media item belongs to.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(MediaSubcategory::class, 'media_subcategory_id');
    }

    /**
     * Get all ratings for this media item.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(MediaItemRating::class);
    }

    /**
     * Get all favorites for this media item.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(MediaItemFavorite::class);
    }

    /**
     * Get all download records for this media item.
     */
    public function downloadRecords(): HasMany
    {
        return $this->hasMany(MediaItemDownload::class);
    }

    /**
     * Check if user has favorited this item.
     */
    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user has rated this item.
     */
    public function isRatedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        
        return $this->ratings()->where('user_id', $user->id)->exists();
    }

    /**
     * Get user's rating for this item.
     */
    public function getUserRating(?User $user): ?MediaItemRating
    {
        if (!$user) {
            return null;
        }
        
        return $this->ratings()->where('user_id', $user->id)->first();
    }

    /**
     * Update average rating and ratings count.
     */
    public function updateRatingStats(): void
    {
        $stats = $this->ratings()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')
            ->first();
        
        $this->update([
            'rating' => $stats->avg_rating ?? 0,
            'ratings_count' => $stats->count ?? 0,
        ]);
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->acceptsMimeTypes([
                'application/zip',
                'application/x-rar-compressed',
                'application/x-7z-compressed',
                'application/x-tar',
                'application/gzip',
            ]);

        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('screenshots')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'category' => $this->category->name ?? '',
            'subcategory' => $this->subcategory->name ?? '',
            'user' => $this->user->name ?? '',
        ];
    }

    /**
     * Increment downloads count.
     */
    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Scope to get only approved and active items.
     */
    public function scopePublished($query)
    {
        return $query->where('is_approved', true)
            ->where('is_active', true)
            ->whereNotNull('published_at');
    }

    /**
     * Scope to get featured items.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('media_category_id', $categoryId);
    }

    /**
     * Scope to filter by subcategory.
     */
    public function scopeBySubcategory($query, $subcategoryId)
    {
        return $query->where('media_subcategory_id', $subcategoryId);
    }

    /**
     * Scope to order by most downloaded.
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('downloads_count');
    }

    /**
     * Scope to order by highest rated.
     */
    public function scopeTopRated($query)
    {
        return $query->where('ratings_count', '>', 0)
            ->orderByDesc('rating');
    }

    /**
     * Scope to order by most recent.
     */
    public function scopeLatest($query)
    {
        return $query->orderByDesc('published_at');
    }
}
