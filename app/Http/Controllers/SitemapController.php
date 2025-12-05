<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Poll;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Maximum items to include per content type in sitemap.
     * Using cursor-based iteration to prevent memory issues with large datasets.
     */
    protected const MAX_ITEMS_PER_TYPE = 1000;

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
     * Get news article URLs using cursor for memory efficiency.
     */
    protected function getNewsUrls(): array
    {
        $urls = [];
        $count = 0;

        News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->cursor()
            ->each(function ($news) use (&$urls, &$count) {
                if ($count >= self::MAX_ITEMS_PER_TYPE) {
                    return false;
                }
                $urls[] = [
                    'loc' => route('news.show', $news->slug),
                    'lastmod' => $news->updated_at->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
                $count++;
            });

        return $urls;
    }

    /**
     * Get event URLs using cursor for memory efficiency.
     */
    protected function getEventUrls(): array
    {
        $urls = [];
        $count = 0;

        Event::where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->cursor()
            ->each(function ($event) use (&$urls, &$count) {
                if ($count >= self::MAX_ITEMS_PER_TYPE) {
                    return false;
                }
                $urls[] = [
                    'loc' => route('events.show', $event->slug),
                    'lastmod' => $event->updated_at->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
                $count++;
            });

        return $urls;
    }

    /**
     * Get poll URLs using cursor for memory efficiency.
     */
    protected function getPollUrls(): array
    {
        $urls = [];
        $count = 0;
        $maxPolls = 500; // Polls typically have less importance in sitemap

        Poll::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->cursor()
            ->each(function ($poll) use (&$urls, &$count, $maxPolls) {
                if ($count >= $maxPolls) {
                    return false;
                }
                $urls[] = [
                    'loc' => route('polls.show', $poll->slug),
                    'lastmod' => $poll->updated_at->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
                $count++;
            });

        return $urls;
    }
}
