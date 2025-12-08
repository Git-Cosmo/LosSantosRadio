# GitHub Copilot Instructions for Los Santos Radio

## 🚀 Quick Reference Cheat Sheet

### Most Common Commands
```bash
composer dev              # Start dev server with hot reload, queue, logs, Vite
composer test             # Run all tests
./vendor/bin/pint         # Format code with Laravel Pint
php artisan migrate       # Run migrations
php artisan cache:clear   # Clear application cache
npm run build             # Build frontend assets
```

### Key Patterns
```php
// Cache with CacheService
$key = $cacheService->key(CacheService::NAMESPACE_RADIO, 'data');
$data = $cacheService->remember($key, CacheService::TTL_REALTIME, fn() => $this->fetch());

// HTTP requests with retry
$response = $this->httpClient->get($url, ['timeout' => 10, 'retry' => [3, 100]]);

// Graceful error handling
try {
    $data = $this->service->getData();
} catch (ServiceException $e) {
    Log::error('Error', ['error' => $e->getMessage()]);
    return back()->with('error', 'Unable to fetch data');
}
```

### Cache TTL Selection
- `TTL_REALTIME` (30s): Now playing, live streams
- `TTL_SHORT` (5m): Discord bot status, frequently changing data
- `TTL_MEDIUM` (1h): Game deals, moderately stable data
- `TTL_LONG` (12h): Game metadata, stable data
- `TTL_VERY_LONG` (24h): Lyrics, very stable data

### Database Compatibility
```php
// Filtered indexes (PostgreSQL/SQLite only)
$driver = DB::connection()->getDriverName();
if (in_array($driver, ['pgsql', 'sqlite'])) {
    DB::statement('CREATE INDEX idx ON table(col) WHERE condition');
}

// Process large datasets
Table::chunk(1000, fn($items) => /* process */);
```

---

## Project Overview

Los Santos Radio is a feature-rich online radio and gaming community hub powered by **AzuraCast**. It provides an interactive listener experience with real-time radio data, music discovery, user profiles, community features, and gamification systems.

### Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS 4, Alpine.js, Blade Templates
- **Build Tool**: Vite 7
- **Radio Integration**: AzuraCast API
- **Streaming**: Icecast / Shoutcast (multi-server support with Docker orchestration)
- **Real-time**: Laravel Reverb (WebSocket) for instant now playing updates
- **Caching**: Universal CacheService with Redis support and namespace organization
- **Database**: SQLite / MySQL / PostgreSQL (with migration compatibility for all three)
- **HTTP Client**: Guzzle with random user agent rotation
- **Search**: Laravel Scout with collection driver
- **Permissions**: Spatie Laravel Permission
- **Media**: Spatie Laravel Media Library with Intervention Image
- **Sitemap**: Spatie Laravel Sitemap (auto-generated every 6 hours)
- **Lyrics**: Genius API integration with guest limits and monetization flow
- **Testing**: PHPUnit with Feature and Unit tests

## Development Workflow

### Initial Setup

```bash
# Install dependencies and setup environment
composer setup

# Or manually:
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate
```

### Development Commands

```bash
# Start development server with hot reload, queue, logs, and Vite
composer dev

# Build frontend assets
npm run build

# Run development server (Vite)
npm run dev

# Run tests
composer test
# or
php artisan test

# Run code formatter (Laravel Pint)
./vendor/bin/pint

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Testing

- Tests are located in `tests/Feature` and `tests/Unit`
- Use PHPUnit for all tests
- Feature tests should test HTTP endpoints and user workflows
- Unit tests should test individual classes and methods
- Always run tests before finalizing changes: `composer test` or `php artisan test`
- Test configuration is in `phpunit.xml`
- Tests use in-memory SQLite database for speed

## Code Conventions and Patterns

### General PHP Conventions

- Follow Laravel conventions and best practices
- Use PHP 8.2+ features (typed properties, constructor property promotion, readonly properties, etc.)
- Follow PSR-12 coding style (enforced by Laravel Pint)
- Use 4 spaces for indentation (configured in `.editorconfig`)
- Always add type hints for parameters and return types
- Use strict types: `declare(strict_types=1);`
- Use descriptive variable and method names

### Laravel-Specific Patterns

#### Service Layer Architecture

All business logic should be in Service classes under `app/Services/`:

```php
// Good: Use dedicated service classes
class AzuraCastService
{
    public function getNowPlaying(int $stationId): ?array
    {
        // Business logic here
    }
}

// Controllers should be thin and delegate to services
class RadioController extends Controller
{
    public function __construct(
        private readonly AzuraCastService $azuraCast
    ) {}
    
    public function index()
    {
        try {
            $nowPlaying = $this->azuraCast->getNowPlaying($stationId);
            return view('radio.index', compact('nowPlaying'));
        } catch (AzuraCastException $e) {
            // Graceful degradation
            return view('radio.index', ['nowPlaying' => null]);
        }
    }
}
```

#### Cache Service Pattern

Always use the centralized `CacheService` for cache operations:

```php
use App\Services\CacheService;

// Good: Use CacheService with namespace constants
$cacheService = app(CacheService::class);
$key = $cacheService->key(CacheService::NAMESPACE_RADIO, 'now_playing');
$data = $cacheService->remember($key, CacheService::TTL_REALTIME, function() {
    return $this->fetchFromApi();
});

// Available namespaces:
// - CacheService::NAMESPACE_RADIO (radio-related data, TTL_REALTIME = 30s)
// - CacheService::NAMESPACE_GAMES (game data, TTL_LONG = 12h)
// - CacheService::NAMESPACE_LYRICS (lyrics data, TTL_VERY_LONG = 24h)
// - CacheService::NAMESPACE_USER (user data)
// - CacheService::NAMESPACE_CONTENT (content data)
// - CacheService::NAMESPACE_SESSION (session data)
```

#### HTTP Client Pattern

Use `HttpClientService` for all external HTTP requests:

```php
use App\Services\HttpClientService;

// Good: Use HttpClientService with retry and random user agents
class ExternalApiService
{
    public function __construct(
        private readonly HttpClientService $httpClient
    ) {}
    
    public function fetchData(): array
    {
        $response = $this->httpClient->get('https://api.example.com/data', [
            'timeout' => 10,
            'retry' => [3, 100], // 3 retries with 100ms delay
        ]);
        
        return $response->json();
    }
}
```

#### Error Handling

- Controllers should catch service exceptions and provide graceful degradation
- Use try-catch blocks to handle API failures
- Return views with empty data instead of crashing
- Log errors appropriately

```php
// Good: Graceful error handling
try {
    $data = $this->service->fetchData();
} catch (ServiceException $e) {
    Log::error('Failed to fetch data', ['error' => $e->getMessage()]);
    $data = collect(); // Return empty collection
}
return view('page', compact('data'));
```

### Database Conventions

#### Migrations

- Always check database driver when using database-specific features
- Use `DB::connection()->getDriverName()` to detect the database
- Filtered/partial indexes are only supported in PostgreSQL and SQLite 3.8.0+, not MySQL/MariaDB

```php
// Good: Database-aware filtered index
$driver = DB::connection()->getDriverName();
if (in_array($driver, ['pgsql', 'sqlite'])) {
    try {
        DB::statement('CREATE INDEX idx_active_items ON items(id) WHERE active = true');
    } catch (\Exception $e) {
        // Handle gracefully
    }
}
```

- Use `chunk()` instead of `each()` when processing large tables to prevent memory issues

```php
// Good: Use chunk for large datasets
Table::chunk(1000, function ($items) {
    foreach ($items as $item) {
        // Process item
    }
});
```

#### Seeders

- Check if `$this->command` is not null before calling methods on it
- Command may be null when seeder runs outside artisan context

```php
// Good: Safe command usage
if ($this->command !== null) {
    $this->command->info('Seeding data...');
}
```

### Authentication

- Los Santos Radio uses **OAuth-only authentication** (Discord, Twitch, Steam, Battle.net)
- There is **no traditional registration route** - all authentication is through OAuth providers
- OAuth routes are defined in `routes/web.php` (lines 189-206)

### Frontend Conventions

#### Blade Templates

- Use Blade components for reusable UI elements
- Components are in `resources/views/components/`
- Example: `<x-floating-background intensity="subtle" :icons="[]" />`

#### Tailwind CSS

- Use Tailwind CSS 4 utility classes
- Follow mobile-first responsive design
- Use arbitrary values sparingly, prefer standard Tailwind classes

#### Alpine.js

- Use Alpine.js for interactive components
- Keep Alpine.js logic simple and declarative
- For complex interactions, consider using Livewire or JavaScript

### Security Considerations

#### Input Sanitization

- Always sanitize user input
- Use `escapeshellcmd()` for shell commands (e.g., in RadioServerService)
- Validate and sanitize quotes and semicolons in query strings to prevent injection

```php
// Good: Sanitize query strings for IGDB API
$query = str_replace(['"', ';'], ['', ''], $searchTerm);
```

#### CSRF Protection

- All forms must include CSRF tokens
- Use `@csrf` directive in Blade templates
- API routes in `routes/api.php` are exempt from CSRF protection

#### SQL Injection Prevention

- Always use query builder or Eloquent ORM
- Never concatenate SQL strings with user input
- Use parameter binding for raw queries

## File Organization

### Directory Structure

```
app/
├── Console/         # Artisan commands
├── DTOs/           # Data Transfer Objects
├── Events/         # Event classes
├── Exceptions/     # Custom exceptions
├── Http/
│   ├── Controllers/    # Controllers (thin, delegate to services)
│   ├── Middleware/     # HTTP middleware
│   └── Requests/       # Form request validation
├── Jobs/           # Queue jobs
├── Models/         # Eloquent models
├── Providers/      # Service providers
├── Services/       # Business logic (main layer)
├── Traits/         # Reusable traits
└── View/           # View composers

resources/
├── views/
│   ├── components/     # Blade components
│   ├── layouts/        # Layout templates
│   ├── admin/          # Admin pages
│   ├── events/         # Event pages
│   ├── games/          # Gaming pages
│   └── ...

routes/
├── api.php         # API routes
├── channels.php    # Broadcast channels
├── console.php     # Console commands
└── web.php         # Web routes (22KB, complex routing)

tests/
├── Feature/        # Feature tests (HTTP, integration)
└── Unit/           # Unit tests (classes, methods)
```

### Naming Conventions

- Controllers: `{Resource}Controller` (e.g., `RadioController`, `EventsController`)
- Services: `{Domain}Service` (e.g., `AzuraCastService`, `LyricsService`)
- Models: Singular, PascalCase (e.g., `User`, `Event`, `Song`)
- Jobs: `{Action}{Resource}Job` (e.g., `UpdateDealsJob`, `SyncFreeGamesJob`)
- Events: `{Resource}{Action}` (e.g., `NowPlayingUpdated`)
- Exceptions: `{Domain}Exception` (e.g., `AzuraCastException`)

## 🎯 Decision Trees

### When to Use `chunk()` vs `each()`

**Use `chunk(1000)`:**
- ✅ Processing large datasets (>1000 records)
- ✅ Updating existing data in migrations
- ✅ Batch operations that might run out of memory
- ✅ Background jobs processing many records

**Use `each()` or regular loops:**
- ✅ Small datasets (<100 records)
- ✅ When you need to stop early (break)
- ✅ Simple iterations with low memory impact

### When to Create New Service vs Extend Existing

**Create New Service:**
- ✅ Integrating with a new external API (e.g., `SpotifyService`)
- ✅ Adding a distinct business domain (e.g., `NotificationService`)
- ✅ Logic is complex and self-contained
- ✅ Service will have multiple public methods

**Extend Existing Service:**
- ✅ Adding methods to existing API integration (e.g., new AzuraCast endpoint)
- ✅ Helper methods for the same domain
- ✅ Refactoring existing logic in same service

### Cache TTL Selection Guide

**Choose TTL based on data characteristics:**

- **TTL_REALTIME (30s)**: Data changes constantly
  - Now playing information
  - Live stream status
  - Active user counts

- **TTL_SHORT (5m)**: Data changes frequently
  - Discord bot status
  - Recent activity feeds
  - Session-based data

- **TTL_MEDIUM (1h)**: Data changes occasionally
  - Game deals
  - News articles
  - API responses with moderate freshness needs

- **TTL_LONG (12h)**: Data rarely changes
  - Game metadata
  - Static content
  - Reference data

- **TTL_VERY_LONG (24h)**: Data almost never changes
  - Song lyrics
  - Historical data
  - Archived content

### When to Add Tests

**Feature Tests (tests/Feature/):**
- ✅ Testing HTTP endpoints
- ✅ Testing user workflows (login, registration)
- ✅ Testing form submissions
- ✅ Testing authentication/authorization
- ✅ Integration testing

**Unit Tests (tests/Unit/):**
- ✅ Testing individual service methods
- ✅ Testing business logic
- ✅ Testing utility functions
- ✅ Testing DTOs and data transformations
- ✅ Testing complex calculations

## ⚠️ Anti-Patterns (Things to Avoid)

### Database Anti-Patterns

**DON'T concatenate SQL with user input**
```php
// ❌ BAD: SQL injection vulnerability
$results = DB::select("SELECT * FROM users WHERE email = '{$email}'");

// ✅ GOOD: Use parameter binding
$results = DB::select('SELECT * FROM users WHERE email = ?', [$email]);
// Or better: use query builder
$results = DB::table('users')->where('email', $email)->get();
```

**DON'T use database-specific features without driver detection**
```php
// ❌ BAD: Only works in MySQL
DB::statement("ALTER TABLE users ADD FULLTEXT INDEX (name)");

// ✅ GOOD: Check driver first
$driver = DB::connection()->getDriverName();
if ($driver === 'mysql') {
    DB::statement("ALTER TABLE users ADD FULLTEXT INDEX (name)");
}
```

**DON'T use `each()` on large tables**
```php
// ❌ BAD: Loads all records into memory
User::each(function ($user) {
    $user->update(['processed' => true]);
});

// ✅ GOOD: Process in chunks
User::chunk(1000, function ($users) {
    foreach ($users as $user) {
        $user->update(['processed' => true]);
    });
});
```

### Security Anti-Patterns

**DON'T skip CSRF tokens in forms**
```blade
{{-- ❌ BAD: Missing CSRF protection --}}
<form method="POST" action="{{ route('submit') }}">
    <button type="submit">Submit</button>
</form>

{{-- ✅ GOOD: Include CSRF token --}}
<form method="POST" action="{{ route('submit') }}">
    @csrf
    <button type="submit">Submit</button>
</form>
```

**DON'T use unescaped output for user content**
```blade
{{-- ❌ BAD: XSS vulnerability --}}
<div>{!! $user->bio !!}</div>

{{-- ✅ GOOD: Escaped output --}}
<div>{{ $user->bio }}</div>
```

**DON'T use unsanitized shell commands**
```php
// ❌ BAD: Command injection risk
$containerId = $request->container_id;
shell_exec("docker restart {$containerId}");

// ✅ GOOD: Sanitize input
$containerId = escapeshellcmd($request->container_id);
shell_exec("docker restart {$containerId}");
```

### Caching Anti-Patterns

**DON'T cache directly without using CacheService**
```php
// ❌ BAD: Direct cache usage without namespace
Cache::remember('nowplaying', 30, fn() => $this->fetch());

// ✅ GOOD: Use CacheService with namespace
$key = $this->cacheService->key(CacheService::NAMESPACE_RADIO, 'nowplaying');
$this->cacheService->remember($key, CacheService::TTL_REALTIME, fn() => $this->fetch());
```

**DON'T forget to handle cache failures**
```php
// ❌ BAD: No fallback if cache fails
$data = Cache::get('key');
return $data->property; // Crashes if null

// ✅ GOOD: Handle missing cache
$data = Cache::get('key');
if (!$data) {
    $data = $this->fetchFromSource();
}
```

### Service Layer Anti-Patterns

**DON'T put business logic in controllers**
```php
// ❌ BAD: Business logic in controller
public function index()
{
    $data = Http::get('https://api.example.com/data')->json();
    $processed = array_map(fn($item) => /* complex logic */, $data);
    return view('page', ['data' => $processed]);
}

// ✅ GOOD: Delegate to service
public function index()
{
    try {
        $data = $this->service->getData();
        return view('page', ['data' => $data]);
    } catch (ServiceException $e) {
        return view('page', ['data' => collect()]);
    }
}
```

**DON'T make HTTP requests without retry logic**
```php
// ❌ BAD: No retry on failure
$response = Http::get($url);

// ✅ GOOD: Use HttpClientService with retry
$response = $this->httpClient->get($url, [
    'timeout' => 10,
    'retry' => [3, 100],
]);
```

### Frontend Anti-Patterns

**DON'T forget accessibility attributes**
```blade
{{-- ❌ BAD: Missing alt text --}}
<img src="{{ $image }}" />

{{-- ✅ GOOD: Include alt text --}}
<img src="{{ $image }}" alt="{{ $event->title }}" />
```

**DON'T use arbitrary Tailwind values unnecessarily**
```blade
{{-- ❌ BAD: Arbitrary values --}}
<div class="p-[17px] mt-[33px]">

{{-- ✅ GOOD: Standard Tailwind classes --}}
<div class="p-4 mt-8">
```

## 📋 File Templates

### Service Class Template

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ServiceNameException;
use Illuminate\Support\Facades\Log;

class ServiceNameService
{
    public function __construct(
        private readonly CacheService $cacheService,
        private readonly HttpClientService $httpClient
    ) {}

    /**
     * Method description.
     *
     * @param  type  $param  Description
     * @return type Description
     * @throws ServiceNameException
     */
    public function methodName($param): mixed
    {
        $cacheKey = $this->cacheService->key(
            CacheService::NAMESPACE_APPROPRIATE,
            "key.{$param}"
        );

        return $this->cacheService->remember(
            $cacheKey,
            CacheService::TTL_APPROPRIATE,
            fn() => $this->fetchData($param)
        );
    }

    private function fetchData($param): mixed
    {
        try {
            $response = $this->httpClient->get('https://api.example.com/endpoint', [
                'query' => ['param' => $param],
                'timeout' => 10,
                'retry' => [3, 100],
            ]);

            if (!$response->successful()) {
                throw new ServiceNameException(
                    "Failed to fetch data: {$response->status()}"
                );
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('ServiceName error', [
                'param' => $param,
                'error' => $e->getMessage(),
            ]);
            throw new ServiceNameException('Failed to fetch data');
        }
    }
}
```

### Controller Template

```php
<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceException;
use App\Services\ServiceNameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    public function __construct(
        private readonly ServiceNameService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->service->getData();
            
            return view('resource.index', [
                'data' => $data,
            ]);
        } catch (ServiceException $e) {
            Log::error('Failed to load resource', ['error' => $e->getMessage()]);
            
            return view('resource.index', [
                'error' => 'Unable to load data. Please try again later.',
                'data' => collect(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $item = $this->service->getBySlug($slug);
            
            return view('resource.show', [
                'item' => $item,
            ]);
        } catch (ServiceException $e) {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            $item = $this->service->create($validated);
            
            return redirect()
                ->route('resource.show', $item->slug)
                ->with('success', 'Resource created successfully');
        } catch (ServiceException $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create resource');
        }
    }
}
```

### Migration Template

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'created_at']);
        });

        // Optional: Filtered index for PostgreSQL/SQLite only
        $driver = DB::connection()->getDriverName();
        
        if (in_array($driver, ['pgsql', 'sqlite'])) {
            try {
                DB::statement('CREATE INDEX table_name_active_idx ON table_name(created_at) WHERE is_active = true');
            } catch (\Illuminate\Database\QueryException $e) {
                $errorMessage = strtolower($e->getMessage());
                
                if (str_contains($errorMessage, 'syntax error') || str_contains($errorMessage, "near 'where'")) {
                    Log::info("Skipped filtered index for table_name - not supported on this {$driver} version");
                } else {
                    throw $e;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

### Feature Test Template

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_loads_successfully(): void
    {
        $response = $this->get('/resource');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_resource(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/resource', [
            'title' => 'Test Title',
            'content' => 'Test content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resources', [
            'title' => 'Test Title',
        ]);
    }

    public function test_guest_cannot_create_resource(): void
    {
        $response = $this->post('/resource', [
            'title' => 'Test Title',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_validation_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/resource', [
            'title' => '', // Required field empty
        ]);

        $response->assertSessionHasErrors('title');
    }
}
```

## Common Tasks and Workflows

### Adding a New Feature

1. Create service class in `app/Services/` for business logic
2. Create controller in `app/Http/Controllers/` (keep thin)
3. Add routes to `routes/web.php` or `routes/api.php`
4. Create Blade views in `resources/views/`
5. Add tests in `tests/Feature/`
6. Run tests: `composer test`
7. Format code: `./vendor/bin/pint`

### Working with External APIs

1. Check if HttpClientService exists for the API
2. If not, create a new service class (e.g., `{Api}Service.php`)
3. Use HttpClientService for HTTP requests with retry logic
4. Use CacheService to cache API responses
5. Handle errors gracefully with try-catch
6. Add appropriate TTL based on data freshness requirements

### Adding Queue Jobs

1. Create job class in `app/Jobs/`
2. Implement `ShouldQueue` interface
3. Add job to scheduler in `routes/console.php`
4. Configure queue connection in `.env`
5. Test job execution: `php artisan queue:work`

### Database Changes

1. Create migration: `php artisan make:migration {description}`
2. Check database compatibility (SQLite, MySQL, PostgreSQL)
3. Avoid database-specific features unless using driver detection
4. Run migration: `php artisan migrate`
5. Test rollback: `php artisan migrate:rollback`

## API Integrations

### AzuraCast API

- Main integration for radio data
- Use `AzuraCastService` for all AzuraCast interactions
- Configure with `AZURACAST_BASE_URL` and `AZURACAST_API_KEY`
- Cache with `TTL_REALTIME` (30 seconds)
- Handle errors gracefully (API may be temporarily unavailable)

### IGDB API (Games)

- Requires Twitch OAuth credentials: `IGDB_CLIENT_ID`, `IGDB_CLIENT_SECRET`
- Access token cached for 24 hours
- Sanitize quotes and semicolons in query strings to prevent injection
- Use `IgdbService` for all IGDB interactions

### CheapShark API (Game Deals)

- Syncs deals every 4 hours (50%+ savings, max 100 deals)
- Use `CheapSharkService` for all CheapShark interactions
- Job: `UpdateDealsJob` in `app/Jobs/`

### Reddit API (Free Games)

- Syncs free games from Reddit every 6 hours
- Use `RedditScraperService` for Reddit scraping
- Job: `SyncFreeGamesJob` in `app/Jobs/`

### Genius API (Lyrics)

- Guest limits: 4 songs per session
- Time-based unlock: 10 minutes (via `GUEST_LYRICS_UNLOCK_DURATION`)
- Unlimited access for registered users
- Use `LyricsService` for lyrics operations

## Real-time Features

### Laravel Reverb (WebSocket)

- Configured for WebSocket broadcasting
- `NowPlayingUpdated` event broadcasts on song changes
- Channel: `radio.station.{id}`
- Detected by `AzuraCastService` when polling for now playing data
- Configure with `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`

## Docker and Server Management

### Radio Server Management

- `RadioServerService` manages Icecast/Shoutcast servers
- Uses Docker container lifecycle (start/stop/restart/status)
- Uses polling mechanism for restart
- Security: `escapeshellcmd()` for all shell commands
- Models: `RadioServer` with status tracking

### Discord Bot

- Managed via `DiscordBotService`
- Can be started/stopped via admin panel at `/admin/discord/settings`
- Methods: `start()` and `stop()`

## Scheduled Tasks

Scheduled tasks are defined in `routes/console.php`:

- `UpdateDealsJob`: Syncs CheapShark deals every 4 hours
- `UpdateIGDBJob`: Refreshes stale games (7+ days old) daily
- `SyncFreeGamesJob`: Syncs Reddit every 6 hours
- Sitemap generation: Every 6 hours

## Environment Variables

Key environment variables (see `.env.example` for full list):

- `APP_NAME`: Application name
- `APP_ENV`: Environment (local, production, etc.)
- `AZURACAST_BASE_URL`: AzuraCast instance URL
- `AZURACAST_API_KEY`: AzuraCast API key
- `IGDB_CLIENT_ID`: IGDB (Twitch) client ID
- `IGDB_CLIENT_SECRET`: IGDB (Twitch) client secret
- `BROADCAST_CONNECTION`: Broadcasting driver (reverb)
- `CACHE_DRIVER`: Cache driver (redis, file, etc.)
- `DB_CONNECTION`: Database connection (sqlite, mysql, pgsql)
- `COMINGSOON`: Enable/disable coming soon mode
- `LAUNCH_DATE`: Launch date for countdown (ISO 8601 format)

## API Endpoints

Game API endpoints (documented in `routes/api.php`):

- `GET /api/games`: List games with filters and pagination
- `GET /api/games/{slug}`: Game details
- `GET /api/deals`: Filtered game deals
- `GET /api/free-games`: Active free games
- `GET /api/games/search`: Search games

## SEO and Structured Data

- Game pages include Schema.org `VideoGame` markup with `Offer` schemas for deals
- Deal pages have Schema.org `Offer` markup
- All pages include Open Graph and Twitter Card metadata
- Sitemap includes games, free games, game deals, videos, news, events, polls, and DJ profiles

## Repository Memory System

This repository uses a memory system to store important facts about the codebase. When you learn something important that would help in future tasks:

1. Use the `store_memory` tool to save the fact
2. Include clear citations (file paths and line numbers)
3. Explain why this fact is important for future tasks
4. Keep facts concise and actionable

## Tips for Working with This Codebase

1. **Always check existing services** before creating new ones - many common patterns are already implemented
2. **Use CacheService** for all caching operations - it provides consistent TTL values and namespace organization
3. **Test with all database types** (SQLite, MySQL, PostgreSQL) when working with migrations
4. **Handle API failures gracefully** - external APIs can be temporarily unavailable
5. **Follow the service layer architecture** - keep controllers thin, put business logic in services
6. **Use HttpClientService** for external HTTP requests - it provides retry logic and random user agents
7. **Check OAuth authentication** - there is no traditional registration, only OAuth providers
8. **Respect guest limits** - lyrics system has guest limits and time-based unlocks
9. **Use Laravel Pint** for code formatting before committing
10. **Run tests** before finalizing any changes

## Questions or Issues?

- Check existing code in `app/Services/` for patterns and examples
- Review tests in `tests/Feature/` for usage examples
- Consult Laravel 12 documentation for framework-specific questions
- Check `.env.example` for available configuration options
