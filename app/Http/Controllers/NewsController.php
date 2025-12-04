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

        $relatedNews = News::published()
            ->where('id', '!=', $article->id)
            ->latestPublished()
            ->limit(3)
            ->get();

        return view('news.show', compact('article', 'relatedNews'));
    }
}
