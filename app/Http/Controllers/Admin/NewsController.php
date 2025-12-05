<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = News::with('author');

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->has('published')) {
            $query->where('is_published', $request->get('published') === '1');
        }

        $news = $query->latest()->paginate(15);

        return view('admin.news.index', [
            'news' => $news,
        ]);
    }

    public function create(): View
    {
        $authors = User::all();

        return view('admin.news.create', [
            'authors' => $authors,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'source' => 'required|in:manual,rss,api',
            'source_url' => 'nullable|url|max:500',
            'image' => 'nullable|url|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);

        $news = News::create($validated);

        activity()
            ->performedOn($news)
            ->causedBy(auth()->user())
            ->log('created news article');

        return redirect()->route('admin.news.index')
            ->with('success', 'News article created successfully.');
    }

    public function edit(News $news): View
    {
        $authors = User::all();

        return view('admin.news.edit', [
            'news' => $news,
            'authors' => $authors,
        ]);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,'.$news->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'source' => 'required|in:manual,rss,api',
            'source_url' => 'nullable|url|max:500',
            'image' => 'nullable|url|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $news->update($validated);

        activity()
            ->performedOn($news)
            ->causedBy(auth()->user())
            ->log('updated news article');

        return redirect()->route('admin.news.index')
            ->with('success', 'News article updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        activity()
            ->performedOn($news)
            ->causedBy(auth()->user())
            ->withProperties(['title' => $news->title])
            ->log('deleted news article');

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully.');
    }
}
