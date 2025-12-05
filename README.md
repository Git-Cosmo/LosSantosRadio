# Los Santos Radio

A modern Laravel 12 application for **Los Santos Radio**, powered by AzuraCast as the single source of truth for all radio data.

## Features

- **Now Playing & History** - Real-time display of currently playing songs with auto-refresh and song history
- **Song Request System** - Browse the song library and request tracks with configurable rate limits
- **Social Login** - Sign in with Discord, Twitch, Steam, or Battle.net with multi-provider account linking
- **Admin Panel** - Full-featured native admin panel for managing users, requests, news, and settings
- **Icecast Integration** - Stream status and listener statistics
- **Activity Logging** - Audit trail for important events with admin UI
- **Dark/Light Mode** - Toggle between dark and light themes with preference persistence
- **Leaderboard** - Top song requesters with timeframe filtering
- **Health Checks** - Environment and service connection verification
- **Analytics Dashboard** - Request statistics and trends for staff members
- **News System** - Publish and manage news articles with rich content
- **Messaging** - User-to-user messaging system
- **Song Ratings** - Upvote/downvote songs with trending display
- **PWA Support** - Progressive Web App for mobile experience

## Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- SQLite/MySQL/PostgreSQL

## Installation

1. Clone the repository:
```bash
git clone https://github.com/Git-Cosmo/LosSantosRadio.git
cd LosSantosRadio
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your `.env` file with:
   - AzuraCast API credentials
   - Icecast connection details
   - OAuth provider credentials (Discord, Twitch, Steam, Battle.net)

5. Run migrations and seed the database:
```bash
php artisan migrate --seed
```

6. Build frontend assets:
```bash
npm run build
```

7. Start the development server:
```bash
php artisan serve
```

## Configuration

### Environment Variables

```env
# AzuraCast
AZURACAST_BASE_URL=https://your-azuracast-instance.com
AZURACAST_API_KEY=your-api-key
AZURACAST_STATION_ID=1

# Icecast
ICECAST_HOST=localhost
ICECAST_PORT=8000
ICECAST_MOUNT=/stream

# OAuth Providers
DISCORD_CLIENT_ID=
DISCORD_CLIENT_SECRET=
TWITCH_CLIENT_ID=
TWITCH_CLIENT_SECRET=
STEAM_CLIENT_SECRET=
BATTLENET_CLIENT_ID=
BATTLENET_CLIENT_SECRET=
```

### Request Limits

Default limits (configurable via admin panel):
- **Guests**: 2 requests per 24 hours
- **Users**: 1 request per minute, max 10 requests per 20-minute window

## Admin Panel

Access the admin panel at `/admin`. Admin users must have the `admin` role assigned.

Features:
- **Dashboard** - Overview of users, requests, and recent activity
- **User Management** - View and manage users, assign roles
- **Song Requests** - Manage request queue, mark as played/rejected
- **News** - Create and publish news articles
- **Settings** - Configure application settings
- **Activity Log** - View audit trail of admin actions

## Architecture

- **Services**: `AzuraCastService`, `IcecastService`, `RequestLimitService`
- **DTOs**: Type-safe data transfer objects for API responses
- **Controllers**: Native Laravel controllers for all functionality
- **Blade Components**: Reusable UI components with layouts
- **Spatie Packages**: Permissions, Activity Logging, Media Library, Tags

## Health Checks

Run health checks to verify the application environment:

```bash
# Basic health check
php artisan health:check

# Detailed output
php artisan health:check --detailed
```

This verifies:
- Environment variables
- Database connection
- Cache functionality
- AzuraCast API connection
- Icecast connection
- Storage paths
- Required PHP extensions

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

### Development Server

```bash
composer dev
```

This starts the web server, queue worker, log viewer, and Vite dev server concurrently.

## CI/CD

The project includes GitHub Actions workflows for:
- **Linting**: Laravel Pint code style checking
- **Testing**: PHPUnit test suite
- **Building**: Production asset compilation

## License

This project is open-source.
