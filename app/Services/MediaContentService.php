<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MediaCategory;
use App\Models\MediaItem;
use App\Models\MediaSubcategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Media Content Automation Service
 * 
 * Automatically populates media items from various free sources.
 * Scrapes and imports mods, maps, and content from open APIs and websites.
 */
class MediaContentService
{
    public function __construct(
        private readonly HttpClientService $httpClient,
        private readonly CacheService $cacheService
    ) {}

    /**
     * Import trending mods from CurseForge API (Minecraft).
     * CurseForge has a free API for mod discovery.
     */
    public function importCurseForgeMinecraftMods(int $limit = 20): int
    {
        try {
            $category = MediaCategory::where('slug', 'minecraft')->first();
            if (!$category) {
                Log::warning('Minecraft category not found for CurseForge import');
                return 0;
            }

            $modsSubcategory = MediaSubcategory::where('media_category_id', $category->id)
                ->where('slug', 'mods')
                ->first();

            if (!$modsSubcategory) {
                Log::warning('Mods subcategory not found for Minecraft');
                return 0;
            }

            // CurseForge API endpoint (requires API key)
            // For demo purposes, this would need CURSEFORGE_API_KEY in .env
            $apiKey = config('services.curseforge.api_key');
            if (!$apiKey) {
                Log::info('CurseForge API key not configured');
                return 0;
            }

            $response = $this->httpClient->get('https://api.curseforge.com/v1/mods/search', [
                'query' => [
                    'gameId' => 432, // Minecraft
                    'classId' => 6, // Mods
                    'sortField' => 2, // Popularity
                    'sortOrder' => 'desc',
                    'pageSize' => $limit,
                ],
                'headers' => [
                    'x-api-key' => $apiKey,
                ],
            ]);

            if (!$response->successful()) {
                Log::error('CurseForge API request failed', ['status' => $response->status()]);
                return 0;
            }

            $data = $response->json();
            $imported = 0;

            foreach ($data['data'] ?? [] as $mod) {
                $slug = Str::slug($mod['name']);
                
                // Check if already exists
                if (MediaItem::where('slug', $slug)->exists()) {
                    continue;
                }

                MediaItem::create([
                    'user_id' => 1, // System user
                    'media_category_id' => $category->id,
                    'media_subcategory_id' => $modsSubcategory->id,
                    'title' => $mod['name'],
                    'slug' => $slug,
                    'description' => Str::limit(strip_tags($mod['summary'] ?? ''), 1000),
                    'content' => strip_tags($mod['summary'] ?? ''),
                    'downloads_count' => $mod['downloadCount'] ?? 0,
                    'is_approved' => true,
                    'is_active' => true,
                    'published_at' => now(),
                ]);

                $imported++;
            }

            Log::info("Imported {$imported} Minecraft mods from CurseForge");
            return $imported;
        } catch (\Exception $e) {
            Log::error('CurseForge import failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Import popular CS2 workshop items (maps, skins).
     * Uses Steam Workshop API.
     */
    public function importSteamWorkshopCS2(int $limit = 20): int
    {
        try {
            $category = MediaCategory::where('slug', 'counter-strike-2')->first();
            if (!$category) {
                return 0;
            }

            // Steam Workshop API
            $response = $this->httpClient->get('https://api.steampowered.com/IPublishedFileService/QueryFiles/v1/', [
                'query' => [
                    'key' => config('services.steam.api_key'),
                    'appid' => 730, // CS2
                    'numperpage' => $limit,
                    'return_metadata' => true,
                    'query_type' => 1, // Ranked by trend
                ],
            ]);

            if (!$response->successful()) {
                return 0;
            }

            $data = $response->json();
            $imported = 0;

            foreach ($data['response']['publishedfiledetails'] ?? [] as $item) {
                $slug = Str::slug($item['title']);
                
                if (MediaItem::where('slug', $slug)->exists()) {
                    continue;
                }

                // Determine subcategory based on tags
                $subcategory = $this->determineCS2Subcategory($category, $item['tags'] ?? []);

                MediaItem::create([
                    'user_id' => 1,
                    'media_category_id' => $category->id,
                    'media_subcategory_id' => $subcategory->id,
                    'title' => $item['title'],
                    'slug' => $slug,
                    'description' => Str::limit(strip_tags($item['description'] ?? ''), 1000),
                    'content' => strip_tags($item['description'] ?? ''),
                    'downloads_count' => $item['subscriptions'] ?? 0,
                    'is_approved' => true,
                    'is_active' => true,
                    'published_at' => now(),
                ]);

                $imported++;
            }

            return $imported;
        } catch (\Exception $e) {
            Log::error('Steam Workshop import failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Import popular GTA V mods from GTA5-Mods.com RSS feed.
     */
    public function importGTA5Mods(int $limit = 20): int
    {
        try {
            $category = MediaCategory::where('slug', 'gta-v')->first();
            if (!$category) {
                return 0;
            }

            // GTA5-Mods.com has RSS feeds
            $response = $this->httpClient->get('https://www.gta5-mods.com/rss/all');

            if (!$response->successful()) {
                return 0;
            }

            $xml = simplexml_load_string($response->body());
            $imported = 0;
            $count = 0;

            foreach ($xml->channel->item as $item) {
                if ($count >= $limit) {
                    break;
                }

                $title = (string) $item->title;
                $slug = Str::slug($title);
                
                if (MediaItem::where('slug', $slug)->exists()) {
                    continue;
                }

                // Default to scripts subcategory
                $subcategory = MediaSubcategory::where('media_category_id', $category->id)
                    ->where('slug', 'scripts')
                    ->first();

                if (!$subcategory) {
                    continue;
                }

                MediaItem::create([
                    'user_id' => 1,
                    'media_category_id' => $category->id,
                    'media_subcategory_id' => $subcategory->id,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => Str::limit(strip_tags((string) $item->description), 1000),
                    'content' => strip_tags((string) $item->description),
                    'is_approved' => true,
                    'is_active' => true,
                    'published_at' => now(),
                ]);

                $imported++;
                $count++;
            }

            return $imported;
        } catch (\Exception $e) {
            Log::error('GTA5 Mods import failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Import Skyrim mods from Nexus Mods API (requires API key).
     */
    public function importNexusModsSkyrim(int $limit = 20): int
    {
        try {
            $apiKey = config('services.nexusmods.api_key');
            if (!$apiKey) {
                return 0;
            }

            $category = MediaCategory::where('slug', 'skyrim')->first();
            if (!$category) {
                return 0;
            }

            // Nexus Mods API
            $response = $this->httpClient->get('https://api.nexusmods.com/v1/games/skyrimspecialedition/mods/trending.json', [
                'headers' => [
                    'apikey' => $apiKey,
                ],
                'query' => [
                    'limit' => $limit,
                ],
            ]);

            if (!$response->successful()) {
                return 0;
            }

            $mods = $response->json();
            $imported = 0;

            foreach ($mods as $mod) {
                $slug = Str::slug($mod['name']);
                
                if (MediaItem::where('slug', $slug)->exists()) {
                    continue;
                }

                // Default to gameplay mods
                $subcategory = MediaSubcategory::where('media_category_id', $category->id)
                    ->where('slug', 'gameplay-mods')
                    ->first();

                if (!$subcategory) {
                    continue;
                }

                MediaItem::create([
                    'user_id' => 1,
                    'media_category_id' => $category->id,
                    'media_subcategory_id' => $subcategory->id,
                    'title' => $mod['name'],
                    'slug' => $slug,
                    'description' => Str::limit($mod['summary'] ?? '', 1000),
                    'content' => $mod['summary'] ?? '',
                    'downloads_count' => $mod['downloads'] ?? 0,
                    'is_approved' => true,
                    'is_active' => true,
                    'published_at' => now(),
                ]);

                $imported++;
            }

            return $imported;
        } catch (\Exception $e) {
            Log::error('Nexus Mods import failed', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Determine CS2 subcategory from tags.
     */
    private function determineCS2Subcategory(MediaCategory $category, array $tags): MediaSubcategory
    {
        $tagsList = array_column($tags, 'tag');
        
        // Check for maps
        if (in_array('Map', $tagsList) || in_array('map', $tagsList)) {
            return MediaSubcategory::where('media_category_id', $category->id)
                ->where('slug', 'maps')
                ->firstOrFail();
        }

        // Default to maps (most common)
        return MediaSubcategory::where('media_category_id', $category->id)
            ->where('slug', 'maps')
            ->firstOrFail();
    }

    /**
     * Run all import tasks.
     */
    public function importAll(): array
    {
        $results = [
            'minecraft_curseforge' => $this->importCurseForgeMinecraftMods(20),
            'cs2_workshop' => $this->importSteamWorkshopCS2(20),
            'gta5_mods' => $this->importGTA5Mods(20),
            'skyrim_nexus' => $this->importNexusModsSkyrim(20),
        ];

        Log::info('Media content import completed', $results);

        return $results;
    }
}
