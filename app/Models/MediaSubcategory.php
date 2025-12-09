<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MediaSubcategory extends Model
{
    protected $fillable = [
        'media_category_id',
        'name',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Automatically generate slug from name.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subcategory) {
            if (empty($subcategory->slug)) {
                $subcategory->slug = Str::slug($subcategory->name);
            }
        });
    }

    /**
     * Get the category this subcategory belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }

    /**
     * Get media items for this subcategory.
     */
    public function mediaItems(): HasMany
    {
        return $this->hasMany(MediaItem::class);
    }

    /**
     * Scope to get only active subcategories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
