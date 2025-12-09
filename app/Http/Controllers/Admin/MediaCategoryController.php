<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaCategory;
use App\Models\MediaSubcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = MediaCategory::withCount('mediaItems')
            ->orderBy('order')
            ->get();

        return view('admin.media.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.media.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;
        $validated['color'] = $validated['color'] ?? '#3B82F6';

        $category = MediaCategory::create($validated);

        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->log('created media category');

        return redirect()->route('admin.media.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(MediaCategory $category): View
    {
        $category->load('subcategories');

        return view('admin.media.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, MediaCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->log('updated media category');

        return redirect()->route('admin.media.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(MediaCategory $category): RedirectResponse
    {
        if ($category->mediaItems()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing media items.');
        }

        activity()
            ->performedOn($category)
            ->causedBy(auth()->user())
            ->log('deleted media category');

        $category->delete();

        return redirect()->route('admin.media.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Get subcategories for a specific category (AJAX).
     */
    public function subcategories(MediaCategory $category)
    {
        return response()->json([
            'subcategories' => $category->subcategories()->active()->get(),
        ]);
    }

    /**
     * Store a new subcategory.
     */
    public function storeSubcategory(Request $request, MediaCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['media_category_id'] = $category->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        $subcategory = MediaSubcategory::create($validated);

        activity()
            ->performedOn($subcategory)
            ->causedBy(auth()->user())
            ->log('created media subcategory');

        return back()->with('success', 'Subcategory created successfully.');
    }

    /**
     * Update a subcategory.
     */
    public function updateSubcategory(Request $request, MediaSubcategory $subcategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $subcategory->update($validated);

        activity()
            ->performedOn($subcategory)
            ->causedBy(auth()->user())
            ->log('updated media subcategory');

        return back()->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove a subcategory.
     */
    public function destroySubcategory(MediaSubcategory $subcategory): RedirectResponse
    {
        if ($subcategory->mediaItems()->count() > 0) {
            return back()->with('error', 'Cannot delete subcategory with existing media items.');
        }

        activity()
            ->performedOn($subcategory)
            ->causedBy(auth()->user())
            ->log('deleted media subcategory');

        $subcategory->delete();

        return back()->with('success', 'Subcategory deleted successfully.');
    }
}
