<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of published news.
     */
    public function index(Request $request)
    {
        $news = News::published()
            ->latestPublished()
            ->withCount('comments')
            ->paginate(10);

        return view('news.index', compact('news'));
    }

    /**
     * Display a specific news article.
     */
    public function show(string $slug)
    {
        $article = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Load comments with user and replies
        $comments = $article->comments()
            ->approved()
            ->topLevel()
            ->with(['user', 'replies.user'])
            ->orderByDesc('created_at')
            ->get();

        $relatedNews = News::published()
            ->where('id', '!=', $article->id)
            ->latestPublished()
            ->limit(3)
            ->get();

        return view('news.show', compact('article', 'relatedNews', 'comments'));
    }
}
