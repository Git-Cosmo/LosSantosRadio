<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MediaCategory;
use App\Models\MediaItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaItemsController extends Controller
{
    /**
     * List media items with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(100, max(1, (int) $request->input('per_page', 20)));
        
        $query = MediaItem::published()
            ->with(['category', 'subcategory', 'user']);

        // Filter by category
        if ($categorySlug = $request->input('category')) {
            $category = MediaCategory::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('media_category_id', $category->id);
            }
        }

        // Filter by subcategory
        if ($subcategoryId = $request->input('subcategory_id')) {
            $query->where('media_subcategory_id', $subcategoryId);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->popular();
                break;
            case 'top-rated':
                $query->topRated();
                break;
            default:
                $query->latest();
        }

        $items = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'version' => $item->version,
                    'downloads_count' => $item->downloads_count,
                    'views_count' => $item->views_count,
                    'rating' => $item->rating,
                    'category' => [
                        'name' => $item->category->name,
                        'slug' => $item->category->slug,
                    ],
                    'subcategory' => [
                        'name' => $item->subcategory->name,
                        'slug' => $item->subcategory->slug,
                    ],
                    'author' => $item->user ? $item->user->name : 'System',
                    'published_at' => $item->published_at?->toIso8601String(),
                    'url' => route('media.show', [
                        $item->category->slug,
                        $item->subcategory->slug,
                        $item->slug,
                    ]),
                ];
            }),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * Get a specific media item.
     */
    public function show(string $slug): JsonResponse
    {
        $item = MediaItem::where('slug', $slug)
            ->published()
            ->with(['category', 'subcategory', 'user'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'description' => $item->description,
                'content' => $item->content,
                'version' => $item->version,
                'file_size' => $item->file_size,
                'downloads_count' => $item->downloads_count,
                'views_count' => $item->views_count,
                'rating' => $item->rating,
                'ratings_count' => $item->ratings_count,
                'category' => [
                    'name' => $item->category->name,
                    'slug' => $item->category->slug,
                    'icon' => $item->category->icon,
                ],
                'subcategory' => [
                    'name' => $item->subcategory->name,
                    'slug' => $item->subcategory->slug,
                ],
                'author' => $item->user ? $item->user->name : 'System',
                'published_at' => $item->published_at?->toIso8601String(),
                'created_at' => $item->created_at->toIso8601String(),
                'updated_at' => $item->updated_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get categories with subcategories.
     */
    public function categories(): JsonResponse
    {
        $categories = MediaCategory::active()
            ->with('subcategories')
            ->withCount('mediaItems')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'color' => $category->color,
                    'items_count' => $category->media_items_count,
                    'subcategories' => $category->subcategories->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'name' => $sub->name,
                            'slug' => $sub->slug,
                            'description' => $sub->description,
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * Get featured media items.
     */
    public function featured(): JsonResponse
    {
        $items = MediaItem::published()
            ->featured()
            ->with(['category', 'subcategory'])
            ->take(12)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'downloads_count' => $item->downloads_count,
                    'rating' => $item->rating,
                    'category' => $item->category->name,
                    'subcategory' => $item->subcategory->name,
                    'url' => route('media.show', [
                        $item->category->slug,
                        $item->subcategory->slug,
                        $item->slug,
                    ]),
                ];
            }),
        ]);
    }

    /**
     * Get trending media items (most downloads in last 7 days).
     */
    public function trending(): JsonResponse
    {
        $items = MediaItem::published()
            ->whereHas('downloadRecords', function ($query) {
                $query->where('downloaded_at', '>=', now()->subDays(7));
            })
            ->withCount(['downloadRecords as recent_downloads' => function ($query) {
                $query->where('downloaded_at', '>=', now()->subDays(7));
            }])
            ->with(['category', 'subcategory'])
            ->orderByDesc('recent_downloads')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'downloads_count' => $item->downloads_count,
                    'recent_downloads' => $item->recent_downloads,
                    'rating' => $item->rating,
                    'category' => $item->category->name,
                    'subcategory' => $item->subcategory->name,
                    'url' => route('media.show', [
                        $item->category->slug,
                        $item->subcategory->slug,
                        $item->slug,
                    ]),
                ];
            }),
        ]);
    }

    /**
     * Get media hub statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_items' => MediaItem::published()->count(),
            'total_downloads' => MediaItem::published()->sum('downloads_count'),
            'total_categories' => \App\Models\MediaCategory::active()->count(),
            'total_uploads_today' => MediaItem::whereDate('created_at', today())->count(),
            'top_rated' => MediaItem::published()
                ->where('ratings_count', '>', 0)
                ->orderByDesc('rating')
                ->take(5)
                ->get(['id', 'title', 'slug', 'rating', 'ratings_count']),
            'most_downloaded' => MediaItem::published()
                ->orderByDesc('downloads_count')
                ->take(5)
                ->get(['id', 'title', 'slug', 'downloads_count']),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
