<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaCategory;
use App\Models\MediaItem;
use App\Models\MediaSubcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaItemController extends Controller
{
    /**
     * Display a listing of media items.
     */
    public function index(Request $request): View
    {
        $query = MediaItem::with(['user', 'category', 'subcategory']);

        // Search
        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter by category
        if ($categoryId = $request->get('category')) {
            $query->where('media_category_id', $categoryId);
        }

        // Filter by subcategory
        if ($subcategoryId = $request->get('subcategory')) {
            $query->where('media_subcategory_id', $subcategoryId);
        }

        // Filter by approval status
        if ($request->has('approved')) {
            $query->where('is_approved', $request->get('approved') === '1');
        }

        $mediaItems = $query->latest()->paginate(20);
        $categories = MediaCategory::active()->get();

        return view('admin.media.items.index', [
            'mediaItems' => $mediaItems,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new media item.
     */
    public function create(): View
    {
        $categories = MediaCategory::active()->with('subcategories')->get();

        return view('admin.media.items.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created media item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'media_category_id' => 'required|exists:media_categories,id',
            'media_subcategory_id' => 'required|exists:media_subcategories,id',
            'version' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_approved'] = $request->boolean('is_approved');
        $validated['is_active'] = $request->boolean('is_active');

        $mediaItem = MediaItem::create($validated);

        // Handle file uploads if present
        if ($request->hasFile('file')) {
            $mediaItem->addMediaFromRequest('file')->toMediaCollection('files');
            $file = $request->file('file');
            $mediaItem->update(['file_size' => $this->formatBytes($file->getSize())]);
        }

        if ($request->hasFile('image')) {
            $mediaItem->addMediaFromRequest('image')->toMediaCollection('images');
        }

        activity()
            ->performedOn($mediaItem)
            ->causedBy(auth()->user())
            ->log('created media item');

        return redirect()->route('admin.media.items.index')
            ->with('success', 'Media item created successfully.');
    }

    /**
     * Show the form for editing a media item.
     */
    public function edit(MediaItem $mediaItem): View
    {
        $categories = MediaCategory::active()->with('subcategories')->get();

        return view('admin.media.items.edit', [
            'mediaItem' => $mediaItem,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified media item.
     */
    public function update(Request $request, MediaItem $mediaItem): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'media_category_id' => 'required|exists:media_categories,id',
            'media_subcategory_id' => 'required|exists:media_subcategories,id',
            'version' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_approved'] = $request->boolean('is_approved');
        $validated['is_active'] = $request->boolean('is_active');

        $mediaItem->update($validated);

        // Handle file updates
        if ($request->hasFile('file')) {
            $mediaItem->clearMediaCollection('files');
            $mediaItem->addMediaFromRequest('file')->toMediaCollection('files');
            $file = $request->file('file');
            $mediaItem->update(['file_size' => $this->formatBytes($file->getSize())]);
        }

        if ($request->hasFile('image')) {
            $mediaItem->clearMediaCollection('images');
            $mediaItem->addMediaFromRequest('image')->toMediaCollection('images');
        }

        activity()
            ->performedOn($mediaItem)
            ->causedBy(auth()->user())
            ->log('updated media item');

        return redirect()->route('admin.media.items.index')
            ->with('success', 'Media item updated successfully.');
    }

    /**
     * Remove the specified media item.
     */
    public function destroy(MediaItem $mediaItem): RedirectResponse
    {
        activity()
            ->performedOn($mediaItem)
            ->causedBy(auth()->user())
            ->log('deleted media item');

        $mediaItem->delete();

        return redirect()->route('admin.media.items.index')
            ->with('success', 'Media item deleted successfully.');
    }

    /**
     * Toggle approval status of media item.
     */
    public function toggleApproval(MediaItem $mediaItem): RedirectResponse
    {
        $mediaItem->update([
            'is_approved' => !$mediaItem->is_approved,
            'published_at' => !$mediaItem->is_approved ? now() : $mediaItem->published_at,
        ]);

        $status = $mediaItem->is_approved ? 'approved' : 'unapproved';

        activity()
            ->performedOn($mediaItem)
            ->causedBy(auth()->user())
            ->log("marked media item as {$status}");

        return back()->with('success', "Media item {$status} successfully.");
    }

    /**
     * Bulk approve media items.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_items,id',
        ]);

        $count = MediaItem::whereIn('id', $validated['ids'])
            ->update([
                'is_approved' => true,
                'published_at' => now(),
            ]);

        activity()
            ->causedBy(auth()->user())
            ->log("bulk approved {$count} media items");

        return back()->with('success', "Successfully approved {$count} media items.");
    }

    /**
     * Bulk reject (delete) media items.
     */
    public function bulkReject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_items,id',
        ]);

        $count = MediaItem::whereIn('id', $validated['ids'])->count();
        
        MediaItem::whereIn('id', $validated['ids'])->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("bulk rejected {$count} media items");

        return back()->with('success', "Successfully rejected {$count} media items.");
    }

    /**
     * Bulk feature/unfeature media items.
     */
    public function bulkFeature(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_items,id',
            'featured' => 'required|boolean',
        ]);

        $count = MediaItem::whereIn('id', $validated['ids'])
            ->update(['is_featured' => $validated['featured']]);

        $action = $validated['featured'] ? 'featured' : 'unfeatured';

        activity()
            ->causedBy(auth()->user())
            ->log("bulk {$action} {$count} media items");

        return back()->with('success', "Successfully {$action} {$count} media items.");
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
