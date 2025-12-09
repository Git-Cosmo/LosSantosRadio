<?php

namespace App\Http\Controllers;

use App\Models\MediaCategory;
use App\Models\MediaItem;
use App\Models\MediaSubcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    /**
     * Display the media hub homepage.
     */
    public function index(): View
    {
        $categories = MediaCategory::active()
            ->withCount('mediaItems')
            ->get();

        $featured = MediaItem::published()
            ->featured()
            ->with(['category', 'subcategory', 'user'])
            ->take(6)
            ->get();

        $popular = MediaItem::published()
            ->with(['category', 'subcategory'])
            ->popular()
            ->take(8)
            ->get();

        $recent = MediaItem::published()
            ->with(['category', 'subcategory'])
            ->latest()
            ->take(12)
            ->get();

        return view('media.index', [
            'categories' => $categories,
            'featured' => $featured,
            'popular' => $popular,
            'recent' => $recent,
        ]);
    }

    /**
     * Display media items for a specific category.
     */
    public function category(string $categorySlug): View
    {
        $category = MediaCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->with('subcategories')
            ->firstOrFail();

        $query = MediaItem::published()
            ->byCategory($category->id)
            ->with(['subcategory', 'user']);

        $mediaItems = $query->latest()->paginate(24);

        return view('media.category', [
            'category' => $category,
            'mediaItems' => $mediaItems,
        ]);
    }

    /**
     * Display media items for a specific subcategory.
     */
    public function subcategory(string $categorySlug, string $subcategorySlug): View
    {
        $category = MediaCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $subcategory = MediaSubcategory::where('slug', $subcategorySlug)
            ->where('media_category_id', $category->id)
            ->where('is_active', true)
            ->firstOrFail();

        $query = MediaItem::published()
            ->bySubcategory($subcategory->id)
            ->with(['user']);

        // Sorting
        $sort = request('sort', 'latest');
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

        $mediaItems = $query->paginate(24);

        return view('media.subcategory', [
            'category' => $category,
            'subcategory' => $subcategory,
            'mediaItems' => $mediaItems,
        ]);
    }

    /**
     * Display a specific media item.
     */
    public function show(string $categorySlug, string $subcategorySlug, string $slug): View
    {
        $mediaItem = MediaItem::where('slug', $slug)
            ->published()
            ->with(['category', 'subcategory', 'user', 'media'])
            ->firstOrFail();

        // Increment views
        $mediaItem->incrementViews();

        // Get related items
        $related = MediaItem::published()
            ->bySubcategory($mediaItem->media_subcategory_id)
            ->where('id', '!=', $mediaItem->id)
            ->take(6)
            ->get();

        return view('media.show', [
            'mediaItem' => $mediaItem,
            'related' => $related,
        ]);
    }

    /**
     * Show upload form for authenticated users.
     */
    public function upload(): View
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to upload content.');
        }

        $categories = MediaCategory::active()
            ->with('subcategories')
            ->get();

        return view('media.upload', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a user-uploaded media item.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to upload content.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'content' => 'nullable|string',
            'media_category_id' => 'required|exists:media_categories,id',
            'media_subcategory_id' => 'required|exists:media_subcategories,id',
            'version' => 'nullable|string|max:50',
            'file' => 'required|file|max:102400', // 100MB max
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_approved'] = false; // Require admin approval
        $validated['is_active'] = true;

        $mediaItem = MediaItem::create($validated);

        // Handle file upload
        if ($request->hasFile('file')) {
            $mediaItem->addMediaFromRequest('file')->toMediaCollection('files');
            $file = $request->file('file');
            $mediaItem->update(['file_size' => $this->formatBytes($file->getSize())]);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $mediaItem->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('media.index')
            ->with('success', 'Your content has been submitted and is awaiting approval.');
    }

    /**
     * Download a media item file.
     */
    public function download(string $categorySlug, string $subcategorySlug, string $slug): \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to download content.');
        }

        $mediaItem = MediaItem::where('slug', $slug)
            ->published()
            ->firstOrFail();

        $media = $mediaItem->getFirstMedia('files');
        
        if (!$media) {
            abort(404, 'File not found.');
        }

        // Increment downloads
        $mediaItem->incrementDownloads();

        return response()->download($media->getPath(), $media->file_name);
    }

    /**
     * Search media items.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category');

        $results = MediaItem::search($query)
            ->query(function ($builder) use ($categoryId) {
                $builder->published();
                if ($categoryId) {
                    $builder->where('media_category_id', $categoryId);
                }
            })
            ->paginate(24);

        $categories = MediaCategory::active()->get();

        return view('media.search', [
            'results' => $results,
            'query' => $query,
            'categories' => $categories,
        ]);
    }

    /**
     * Format bytes to human-readable format.
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
