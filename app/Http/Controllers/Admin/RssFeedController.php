<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RssFeed;
use App\Services\RssFeedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RssFeedController extends Controller
{
    public function __construct(
        protected RssFeedService $rssFeedService
    ) {}

    /**
     * Display a listing of RSS feeds.
     */
    public function index(): View
    {
        $feeds = RssFeed::orderBy('name')->paginate(20);

        return view('admin.rss-feeds.index', compact('feeds'));
    }

    /**
     * Show the form for creating a new RSS feed.
     */
    public function create(): View
    {
        return view('admin.rss-feeds.create');
    }

    /**
     * Store a newly created RSS feed.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'fetch_interval' => 'required|integer|min:300|max:86400',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        RssFeed::create($validated);

        return redirect()->route('admin.rss-feeds.index')
            ->with('success', 'RSS feed created successfully.');
    }

    /**
     * Show the form for editing an RSS feed.
     */
    public function edit(RssFeed $rssFeed): View
    {
        return view('admin.rss-feeds.edit', compact('rssFeed'));
    }

    /**
     * Update an RSS feed.
     */
    public function update(Request $request, RssFeed $rssFeed): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'fetch_interval' => 'required|integer|min:300|max:86400',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $rssFeed->update($validated);

        return redirect()->route('admin.rss-feeds.index')
            ->with('success', 'RSS feed updated successfully.');
    }

    /**
     * Remove an RSS feed.
     */
    public function destroy(RssFeed $rssFeed): RedirectResponse
    {
        $rssFeed->delete();

        return redirect()->route('admin.rss-feeds.index')
            ->with('success', 'RSS feed deleted successfully.');
    }

    /**
     * Import articles from a specific RSS feed.
     */
    public function import(RssFeed $rssFeed): RedirectResponse
    {
        $result = $this->rssFeedService->importFromUrl($rssFeed->url, auth()->id());

        if ($result['imported'] > 0) {
            $rssFeed->markAsFetched($result['imported']);

            return redirect()->route('admin.rss-feeds.index')
                ->with('success', "Successfully imported {$result['imported']} articles from {$rssFeed->name}.");
        }

        $errorMessage = ! empty($result['errors']) ? implode(', ', $result['errors']) : 'No articles were imported.';

        return redirect()->route('admin.rss-feeds.index')
            ->with('error', "Failed to import from {$rssFeed->name}: {$errorMessage}");
    }

    /**
     * Import articles from all active RSS feeds.
     */
    public function importAll(): RedirectResponse
    {
        $feeds = RssFeed::where('is_active', true)->get();
        $totalImported = 0;
        $errors = [];

        foreach ($feeds as $feed) {
            $result = $this->rssFeedService->importFromUrl($feed->url, auth()->id());

            if ($result['imported'] > 0) {
                $feed->markAsFetched($result['imported']);
                $totalImported += $result['imported'];
            }

            if (! empty($result['errors'])) {
                $errors[$feed->name] = $result['errors'];
            }
        }

        if ($totalImported > 0) {
            $message = "Successfully imported {$totalImported} articles from " . $feeds->count() . ' feeds.';
            if (! empty($errors)) {
                $message .= ' Some feeds had errors.';
            }

            return redirect()->route('admin.rss-feeds.index')
                ->with('success', $message);
        }

        return redirect()->route('admin.rss-feeds.index')
            ->with('error', 'No articles were imported from any feeds.');
    }
}
