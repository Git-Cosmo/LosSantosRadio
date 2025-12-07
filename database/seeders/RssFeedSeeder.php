<?php

namespace Database\Seeders;

use App\Models\RssFeed;
use Illuminate\Database\Seeder;

class RssFeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * High-quality gaming news RSS feeds with detailed descriptions
     * and image-rich content sources.
     */
    public function run(): void
    {
        $feeds = [
            [
                'name' => 'IGN All News',
                'url' => 'https://feeds.ign.com/ign/all',
                'category' => 'Gaming News',
                'description' => 'Latest gaming news, reviews, and videos from IGN. Comprehensive coverage of all gaming platforms with high-quality images and detailed articles.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'GameSpot Latest News',
                'url' => 'https://www.gamespot.com/feeds/news/',
                'category' => 'Gaming News',
                'description' => 'Breaking gaming news and updates from GameSpot. Features in-depth coverage with screenshots and trailers.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Polygon',
                'url' => 'https://www.polygon.com/rss/index.xml',
                'category' => 'Gaming News',
                'description' => 'Modern gaming news and culture from Polygon. Known for high-quality journalism and multimedia content.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Kotaku',
                'url' => 'https://kotaku.com/rss',
                'category' => 'Gaming Culture',
                'description' => 'Gaming culture, news, and reviews from Kotaku. Features unique perspectives and engaging content with rich media.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'PC Gamer',
                'url' => 'https://www.pcgamer.com/rss/',
                'category' => 'PC Gaming',
                'description' => 'PC gaming news, hardware reviews, and features. Excellent source for PC-focused content with detailed images.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Eurogamer',
                'url' => 'https://www.eurogamer.net/?format=rss',
                'category' => 'Gaming News',
                'description' => 'European gaming news and reviews. High-quality content with comprehensive coverage and media-rich articles.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'GamesRadar+',
                'url' => 'https://www.gamesradar.com/feed/',
                'category' => 'Gaming News',
                'description' => 'Gaming news, reviews, and features from GamesRadar. Covers all platforms with engaging multimedia content.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Rock Paper Shotgun',
                'url' => 'https://www.rockpapershotgun.com/feed',
                'category' => 'PC Gaming',
                'description' => 'PC gaming news, reviews, and opinion pieces. Known for insightful writing and quality content.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'VG247',
                'url' => 'https://www.vg247.com/feed',
                'category' => 'Gaming News',
                'description' => 'Breaking gaming news and rumors. Fast-paced coverage with frequent updates and image-rich articles.',
                'is_active' => true,
                'fetch_interval' => 1800, // 30 minutes - faster updates
            ],
            [
                'name' => 'Game Informer',
                'url' => 'https://www.gameinformer.com/feeds/thefeedrss.aspx',
                'category' => 'Gaming News',
                'description' => 'Premium gaming news and exclusive features. Long-form content with extensive media coverage.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Nintendo Life',
                'url' => 'https://www.nintendolife.com/feeds/latest',
                'category' => 'Nintendo',
                'description' => 'Nintendo news, reviews, and guides. Comprehensive Nintendo coverage with screenshots and videos.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'PlayStation Blog',
                'url' => 'https://blog.playstation.com/feed/',
                'category' => 'PlayStation',
                'description' => 'Official PlayStation news and updates. First-hand information with official artwork and media.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Xbox Wire',
                'url' => 'https://news.xbox.com/en-us/feed/',
                'category' => 'Xbox',
                'description' => 'Official Xbox news and announcements. Direct from Microsoft with official assets and media.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Destructoid',
                'url' => 'https://www.destructoid.com/feed/',
                'category' => 'Gaming News',
                'description' => 'Gaming news with personality and humor. Engaging content with a unique voice and quality images.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'GameRant',
                'url' => 'https://gamerant.com/feed/',
                'category' => 'Gaming News',
                'description' => 'Gaming news, guides, and features. Covers all aspects of gaming with detailed articles and media.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
        ];

        foreach ($feeds as $feed) {
            RssFeed::updateOrCreate(
                ['url' => $feed['url']],
                $feed
            );
        }

        if ($this->command) {
            $this->command->info('RSS feeds seeded successfully! Added '.count($feeds).' gaming news sources.');
        }
    }
}
