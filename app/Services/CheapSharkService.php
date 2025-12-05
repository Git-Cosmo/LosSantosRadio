<?php

namespace App\Services;

use App\Models\GameDeal;
use App\Models\GameStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheapSharkService
{
    protected string $baseUrl = 'https://www.cheapshark.com/api/1.0';

    protected int $cacheTtl = 3600; // 1 hour

    /**
     * Sync all stores from CheapShark.
     */
    public function syncStores(): Collection
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/stores");

            if ($response->failed()) {
                Log::error('CheapShark: Failed to fetch stores', ['status' => $response->status()]);

                return collect();
            }

            $stores = collect($response->json())->map(function ($store) {
                return GameStore::updateOrCreate(
                    ['external_id' => (string) $store['storeID']],
                    [
                        'name' => $store['storeName'],
                        'is_active' => (bool) $store['isActive'],
                        'images' => $store['images'] ?? null,
                    ]
                );
            });

            Log::info('CheapShark: Synced stores', ['count' => $stores->count()]);

            return $stores;
        } catch (\Exception $e) {
            Log::error('CheapShark: Error syncing stores', ['error' => $e->getMessage()]);

            return collect();
        }
    }

    /**
     * Fetch deals from CheapShark with optional filters.
     */
    public function fetchDeals(array $options = []): Collection
    {
        $params = array_merge([
            'pageSize' => 60,
            'pageNumber' => 0,
            'sortBy' => 'Savings',
            'desc' => 1,
            'onSale' => 1,
        ], $options);

        $cacheKey = 'cheapshark.deals.'.md5(serialize($params));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($params) {
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/deals", $params);

                if ($response->failed()) {
                    Log::error('CheapShark: Failed to fetch deals', ['status' => $response->status()]);

                    return collect();
                }

                return collect($response->json());
            } catch (\Exception $e) {
                Log::error('CheapShark: Error fetching deals', ['error' => $e->getMessage()]);

                return collect();
            }
        });
    }

    /**
     * Sync deals from CheapShark to database.
     */
    public function syncDeals(int $minSavings = 50, int $maxDeals = 100): int
    {
        // First ensure stores are synced
        $stores = GameStore::pluck('id', 'external_id');

        if ($stores->isEmpty()) {
            $this->syncStores();
            $stores = GameStore::pluck('id', 'external_id');
        }

        $deals = $this->fetchDeals([
            'pageSize' => $maxDeals,
            'lowerPrice' => 0,
        ]);

        $synced = 0;

        foreach ($deals as $deal) {
            $savings = (int) $deal['savings'];

            if ($savings < $minSavings) {
                continue;
            }

            $storeId = $stores->get((string) $deal['storeID']);

            GameDeal::updateOrCreate(
                ['deal_id' => $deal['dealID']],
                [
                    'title' => $deal['title'],
                    'slug' => Str::slug($deal['title']),
                    'sale_price' => (float) $deal['salePrice'],
                    'normal_price' => (float) $deal['normalPrice'],
                    'savings_percent' => $savings,
                    'metacritic_score' => $deal['metacriticScore'] ? (float) $deal['metacriticScore'] : null,
                    'thumb' => $deal['thumb'] ?? null,
                    'store_id' => $storeId,
                    'external_game_id' => $deal['gameID'] ?? null,
                    'is_on_sale' => true,
                ]
            );

            $synced++;
        }

        // Mark old deals as not on sale if they're no longer in the API response
        $dealIds = $deals->pluck('dealID')->toArray();
        GameDeal::whereNotIn('deal_id', $dealIds)
            ->where('is_on_sale', true)
            ->update(['is_on_sale' => false]);

        Log::info('CheapShark: Synced deals', ['synced' => $synced]);

        return $synced;
    }

    /**
     * Get deal details by ID.
     */
    public function getDealDetails(string $dealId): ?array
    {
        $cacheKey = "cheapshark.deal.{$dealId}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($dealId) {
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/deals", ['id' => $dealId]);

                if ($response->failed()) {
                    return null;
                }

                return $response->json();
            } catch (\Exception $e) {
                Log::error('CheapShark: Error fetching deal details', ['dealId' => $dealId, 'error' => $e->getMessage()]);

                return null;
            }
        });
    }

    /**
     * Search for games.
     */
    public function searchGames(string $query, int $limit = 10): Collection
    {
        $cacheKey = 'cheapshark.search.'.md5($query).".{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl / 2, function () use ($query, $limit) {
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/games", [
                    'title' => $query,
                    'limit' => $limit,
                ]);

                if ($response->failed()) {
                    return collect();
                }

                return collect($response->json());
            } catch (\Exception $e) {
                Log::error('CheapShark: Error searching games', ['query' => $query, 'error' => $e->getMessage()]);

                return collect();
            }
        });
    }

    /**
     * Clear all CheapShark caches.
     *
     * Note: This method clears specific known cache keys. For a more robust
     * solution in production, consider using cache tags.
     */
    public function clearCache(): void
    {
        // Clear the main deals cache with default params
        $defaultParams = [
            'pageSize' => 60,
            'pageNumber' => 0,
            'sortBy' => 'Savings',
            'desc' => 1,
            'onSale' => 1,
        ];
        Cache::forget('cheapshark.deals.'.md5(serialize($defaultParams)));

        // Note: Individual deal caches expire naturally after TTL
    }
}
