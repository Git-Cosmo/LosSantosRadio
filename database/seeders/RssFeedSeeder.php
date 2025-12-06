<?php

namespace Database\Seeders;

use App\Models\RssFeed;
use Illuminate\Database\Seeder;

class RssFeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeds = [
            [
                'name' => 'IGN All News',
                'url' => 'https://feeds.ign.com/ign/all',
                'category' => 'Gaming News',
                'description' => 'All the latest gaming news from IGN, including reviews, previews, and industry updates.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'GameSpot News',
                'url' => 'https://www.gamespot.com/feeds/news/',
                'category' => 'Gaming News',
                'description' => 'Breaking gaming news, reviews, and features from GameSpot.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'PC Gamer',
                'url' => 'https://www.pcgamer.com/rss/',
                'category' => 'PC Gaming',
                'description' => 'The latest PC gaming news, reviews, and hardware guides.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Kotaku',
                'url' => 'https://kotaku.com/rss',
                'category' => 'Gaming Culture',
                'description' => 'Gaming news, reviews, and culture from Kotaku.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Polygon',
                'url' => 'https://www.polygon.com/rss/index.xml',
                'category' => 'Gaming News',
                'description' => 'Gaming news, reviews, and entertainment coverage from Polygon.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Eurogamer',
                'url' => 'https://www.eurogamer.net/?format=rss',
                'category' => 'Gaming News',
                'description' => 'European gaming news and reviews from Eurogamer.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'Rock Paper Shotgun',
                'url' => 'https://www.rockpapershotgun.com/feed',
                'category' => 'PC Gaming',
                'description' => 'PC gaming news, reviews, and features from Rock Paper Shotgun.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
            [
                'name' => 'GamesRadar+',
                'url' => 'https://www.gamesradar.com/rss/',
                'category' => 'Gaming News',
                'description' => 'Gaming news, reviews, previews, and features from GamesRadar.',
                'is_active' => true,
                'fetch_interval' => 3600,
            ],
        ];

        foreach ($feeds as $feed) {
            RssFeed::firstOrCreate(
                ['url' => $feed['url']],
                $feed
            );
        }
    }
}
