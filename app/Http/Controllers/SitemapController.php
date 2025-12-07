<?php

namespace App\Http\Controllers;

use App\Models\DjProfile;
use App\Models\Event;
use App\Models\News;
use App\Models\Poll;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Maximum items to include per content type in sitemap.
     */
    protected const MAX_ITEMS_PER_TYPE = 1000;

    /**
     * Maximum polls to include in sitemap (polls typically have less importance).
     */
    protected const MAX_POLLS = 500;

    /**
     * Generate XML sitemap for SEO.
     */
    public function index(): Response
    {
        $urls = $this->getStaticUrls();
        $urls = array_merge($urls, $this->getNewsUrls());
        $urls = array_merge($urls, $this->getEventUrls());
        $urls = array_merge($urls, $this->getPollUrls());
        $urls = array_merge($urls, $this->getDjUrls());

        $content = view('sitemap.index', ['urls' => $urls])->render();

        return response($content)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Get static page URLs.
     */
    protected function getStaticUrls(): array
    {
        $now = now()->format('c');

        return [
            [
                'loc' => url('/'),
                'lastmod' => $now,
                'changefreq' => 'hourly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('schedule'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('news.index'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('events.index'),
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('polls.index'),
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('requests.index'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('songs'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('leaderboard'),
                'lastmod' => $now,
                'changefreq' => 'hourly',
                'priority' => '0.6',
            ],
            [
                'loc' => route('djs.index'),
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('djs.schedule'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('games.free'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('games.deals'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.7',
            ],
            [
                'loc' => route('videos.ylyl'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.6',
            ],
            [
                'loc' => route('videos.clips'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.6',
            ],
            [
                'loc' => route('legal.terms'),
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('legal.privacy'),
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('legal.cookies'),
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.3',
            ],
        ];
    }

    /**
     * Get news article URLs using cursor for memory efficiency.
     */
    protected function getNewsUrls(): array
    {
        $urls = [];

        News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(self::MAX_ITEMS_PER_TYPE)
            ->cursor()
            ->each(function ($news) use (&$urls) {
                $urls[] = [
                    'loc' => route('news.show', $news->slug),
                    'lastmod' => $news->updated_at->format('c'),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            });

        return $urls;
    }

    /**
     * Get event URLs using cursor for memory efficiency.
     */
    protected function getEventUrls(): array
    {
        $urls = [];

        Event::where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->take(self::MAX_ITEMS_PER_TYPE)
            ->cursor()
            ->each(function ($event) use (&$urls) {
                $urls[] = [
                    'loc' => route('events.show', $event->slug),
                    'lastmod' => $event->updated_at->format('c'),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            });

        return $urls;
    }

    /**
     * Get poll URLs using cursor for memory efficiency.
     */
    protected function getPollUrls(): array
    {
        $urls = [];

        Poll::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->take(self::MAX_POLLS)
            ->cursor()
            ->each(function ($poll) use (&$urls) {
                $urls[] = [
                    'loc' => route('polls.show', $poll->slug),
                    'lastmod' => $poll->updated_at->format('c'),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
            });

        return $urls;
    }

    /**
     * Get DJ profile URLs using cursor for memory efficiency.
     */
    protected function getDjUrls(): array
    {
        $urls = [];

        DjProfile::where('is_active', true)
            ->orderBy('stage_name')
            ->take(self::MAX_ITEMS_PER_TYPE)
            ->cursor()
            ->each(function ($dj) use (&$urls) {
                $urls[] = [
                    'loc' => route('djs.show', $dj->id),
                    'lastmod' => $dj->updated_at->format('c'),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            });

        return $urls;
    }
}
