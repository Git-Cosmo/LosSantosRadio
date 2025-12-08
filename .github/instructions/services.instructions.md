---
applyTo: "app/Services/**"
---

# Service Layer Instructions

## Service Architecture

Services contain business logic. Controllers should be thin and delegate to services.

## Service Class Structure

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;

class ExampleService
{
    public function __construct(
        private readonly CacheService $cacheService,
        private readonly HttpClientService $httpClient
    ) {}
    
    /**
     * Method description with clear return type.
     */
    public function fetchData(int $id): array
    {
        // Business logic here
    }
}
```

## Using CacheService

Always use CacheService for caching operations:

```php
// Cache with namespace and TTL
$key = $this->cacheService->key(CacheService::NAMESPACE_RADIO, "data.{$id}");
$data = $this->cacheService->remember(
    $key,
    CacheService::TTL_REALTIME, // or TTL_SHORT, TTL_MEDIUM, TTL_LONG, TTL_VERY_LONG
    function () use ($id) {
        return $this->fetchFromApi($id);
    }
);

// Get cached data
$cached = $this->cacheService->get(CacheService::NAMESPACE_RADIO, 'key');

// Clear specific cache
$this->cacheService->forget(CacheService::NAMESPACE_RADIO, 'key');

// Clear entire namespace
$this->cacheService->clearNamespace(CacheService::NAMESPACE_RADIO);
```

## Cache TTL Guidelines

Choose appropriate TTL based on data freshness requirements:

- `TTL_REALTIME` (30s): Real-time data like now playing, live streams
- `TTL_SHORT` (5m): Frequently changing data like Discord bot status
- `TTL_MEDIUM` (1h): Moderately stable data like game deals
- `TTL_LONG` (12h): Stable data like game metadata
- `TTL_VERY_LONG` (24h): Very stable data like lyrics

## Using HttpClientService

Use HttpClientService for external HTTP requests:

```php
// GET request with retry
$response = $this->httpClient->get('https://api.example.com/data', [
    'timeout' => 10,
    'retry' => [3, 100], // 3 retries with 100ms delay
]);

$data = $response->json();

// POST request
$response = $this->httpClient->post('https://api.example.com/data', [
    'json' => ['key' => 'value'],
    'timeout' => 10,
]);
```

## Error Handling

Throw custom exceptions and handle them in controllers:

```php
// In Service
if (!$response->successful()) {
    throw new AzuraCastException('Failed to fetch data: ' . $response->body());
}

// In Controller
try {
    $data = $this->service->fetchData($id);
} catch (ServiceException $e) {
    Log::error('Service error', ['error' => $e->getMessage()]);
    return back()->with('error', 'Unable to fetch data');
}
```

## External API Integration Pattern

Complete pattern for integrating external APIs:

```php
class ExternalApiService
{
    private const CACHE_TTL = CacheService::TTL_MEDIUM;
    
    public function __construct(
        private readonly CacheService $cacheService,
        private readonly HttpClientService $httpClient
    ) {}
    
    public function getData(string $query): array
    {
        // Sanitize input
        $query = $this->sanitizeQuery($query);
        
        // Check cache first
        $cacheKey = $this->cacheService->key(
            CacheService::NAMESPACE_GAMES,
            "external_api.{$query}"
        );
        
        return $this->cacheService->remember(
            $cacheKey,
            self::CACHE_TTL,
            fn() => $this->fetchFromApi($query)
        );
    }
    
    private function fetchFromApi(string $query): array
    {
        try {
            $response = $this->httpClient->get("https://api.example.com/search", [
                'query' => ['q' => $query],
                'timeout' => 10,
                'retry' => [3, 100],
            ]);
            
            if (!$response->successful()) {
                throw new ExternalApiException(
                    "API error: {$response->status()}"
                );
            }
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('External API error', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            throw new ExternalApiException('Failed to fetch data');
        }
    }
    
    private function sanitizeQuery(string $query): string
    {
        // Remove potentially dangerous characters
        return str_replace(['"', ';', '\\'], ['', '', ''], $query);
    }
}
```

## When to Create a New Service vs Extending Existing

### Create New Service When:
- Integrating with a new external API
- Adding a distinct business domain (e.g., GamificationService, LyricsService)
- Logic is complex and doesn't fit in existing services

### Extend Existing Service When:
- Adding methods to existing domain (e.g., new AzuraCast API endpoint)
- Adding helper methods for the same service
- Refactoring existing logic

## Testing Services

Services should be unit tested:

```php
namespace Tests\Unit;

use App\Services\ExampleService;
use Tests\TestCase;

class ExampleServiceTest extends TestCase
{
    public function test_fetch_data_returns_array(): void
    {
        $service = app(ExampleService::class);
        $result = $service->fetchData(1);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }
}
```

## Service Dependencies

Use constructor injection for dependencies:

```php
// Good: Constructor injection with readonly properties
public function __construct(
    private readonly CacheService $cacheService,
    private readonly HttpClientService $httpClient,
    private readonly OtherService $otherService
) {}

// Avoid: Manual instantiation
$service = new OtherService(); // Don't do this
```

## Logging

Add appropriate logging for debugging and monitoring:

```php
// Info level for normal operations
Log::info('Data fetched successfully', ['count' => count($data)]);

// Warning level for recoverable issues
Log::warning('API slow response', ['duration' => $duration]);

// Error level for failures
Log::error('Failed to fetch data', [
    'error' => $e->getMessage(),
    'query' => $query,
]);
```
