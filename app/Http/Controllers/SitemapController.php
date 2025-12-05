<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Poll;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap for SEO.
     */
    public function index(): Response
    {
        $urls = $this->getStaticUrls();
        $urls = array_merge($urls, $this->getNewsUrls());
        $urls = array_merge($urls, $this->getEventUrls());
        $urls = array_merge($urls, $this->getPollUrls());

        $content = view('sitemap.index', ['urls' => $urls])->render();

        return response($content)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Get static page URLs.
     */
    protected function getStaticUrls(): array
    {
        return [
            [
                'loc' => url('/'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'hourly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('schedule'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('news.index'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('events.index'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('polls.index'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('requests.index'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('songs'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('leaderboard'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'hourly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('games.free'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('games.deals'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('videos.ylyl'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.6',
            ],
            [
                'loc' => route('videos.clips'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.6',
            ],
        ];
    }

    /**
     * Get news article URLs.
     */
    protected function getNewsUrls(): array
    {
        return News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->limit(100)
            ->get()
            ->map(fn ($news) => [
                'loc' => route('news.show', $news->slug),
                'lastmod' => $news->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ])
            ->toArray();
    }

    /**
     * Get event URLs.
     */
    protected function getEventUrls(): array
    {
        return Event::where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->limit(100)
            ->get()
            ->map(fn ($event) => [
                'loc' => route('events.show', $event->slug),
                'lastmod' => $event->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ])
            ->toArray();
    }

    /**
     * Get poll URLs.
     */
    protected function getPollUrls(): array
    {
        return Poll::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(fn ($poll) => [
                'loc' => route('polls.show', $poll->slug),
                'lastmod' => $poll->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.5',
            ])
            ->toArray();
    }
}
