<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\RedditScraperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideosController extends Controller
{
    public function __construct(
        protected RedditScraperService $redditScraper
    ) {}

    /**
     * Display videos dashboard.
     */
    public function index(): View
    {
        return view('admin.videos.index', [
            'yylylCount' => Video::ylyl()->active()->count(),
            'clipsCount' => Video::clips()->active()->count(),
            'totalViews' => Video::sum('views'),
            'recentVideos' => Video::latest()->take(10)->get(),
        ]);
    }

    /**
     * Display YLYL videos list.
     */
    public function ylyl(): View
    {
        $videos = Video::ylyl()
            ->orderBy('posted_at', 'desc')
            ->paginate(20);

        return view('admin.videos.ylyl', [
            'videos' => $videos,
        ]);
    }

    /**
     * Display clips list.
     */
    public function clips(): View
    {
        $videos = Video::clips()
            ->orderBy('posted_at', 'desc')
            ->paginate(20);

        return view('admin.videos.clips', [
            'videos' => $videos,
        ]);
    }

    /**
     * Show form to create a video.
     */
    public function create(): View
    {
        return view('admin.videos.create');
    }

    /**
     * Store a new video.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
            'embed_url' => 'nullable|url',
            'thumbnail_url' => 'nullable|url',
            'category' => 'required|in:ylyl,clips',
            'platform' => 'required|in:youtube,twitch,kick,reddit,other',
            'author' => 'nullable|string|max:255',
        ]);

        $validated['source'] = 'manual';
        $validated['is_active'] = true;
        $validated['posted_at'] = now();

        Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video created successfully.');
    }

    /**
     * Show form to edit a video.
     */
    public function edit(Video $video): View
    {
        return view('admin.videos.edit', [
            'video' => $video,
        ]);
    }

    /**
     * Update a video.
     */
    public function update(Request $request, Video $video): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
            'embed_url' => 'nullable|url',
            'thumbnail_url' => 'nullable|url',
            'category' => 'required|in:ylyl,clips',
            'platform' => 'required|in:youtube,twitch,kick,reddit,other',
            'author' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video updated successfully.');
    }

    /**
     * Delete a video.
     */
    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully.');
    }

    /**
     * Sync videos from Reddit.
     */
    public function sync(Request $request): RedirectResponse
    {
        $category = $request->input('category', 'ylyl');

        $count = $this->redditScraper->syncVideos($category);

        return redirect()->route("admin.videos.{$category}")
            ->with('success', "Synced {$count} {$category} videos from Reddit.");
    }
}
