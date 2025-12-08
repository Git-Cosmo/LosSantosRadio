# GitHub Copilot Instructions for Los Santos Radio

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
