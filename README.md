# Los Santos Radio

A feature-rich online radio & gaming community hub powered by **AzuraCast**. Los Santos Radio provides an interactive listener experience with real-time radio data, music discovery, user profiles, community features, and gamification systems.

## 🎯 Vision

Los Santos Radio is designed to be a modern, polished, and interactive radio website that:
- Provides 24/7 music streaming with live DJ shows
- Builds a gaming community around music
- Creates long-term engagement through gamification
- Offers social features for listeners to connect

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS, Alpine.js, Blade Templates
- **Radio Integration**: AzuraCast API
- **Streaming**: Icecast
- **Caching**: Redis (optional) / File cache
- **Database**: SQLite / MySQL / PostgreSQL
- **Real-time**: Event broadcasting (optional WebSocket support)
- **HTTP Client**: Guzzle with random user agent rotation
- **Search**: Laravel Scout with collection driver
- **Permissions**: Spatie Laravel Permission
- **Media**: Spatie Laravel Media Library with Intervention Image
- **Sitemap**: Spatie Laravel Sitemap (auto-generated every 6 hours)

## 📦 Features

### Radio Experience
- **Ultra-Modern Now Playing Widget** - Professional, visually stunning display with:
  - Large album artwork (300x300px) with enhanced 3D hover effects, shadows, and ambient glow
  - Animated audio visualizer with glowing effects and smooth bounce animations
  - Dynamic gradient text styling for song titles with multi-color shifting animation
  - Play indicator icon with pulsing gradient background
  - Artist display with icon and enhanced typography
  - Real-time listener count with pulsing animations
  - Enhanced progress bar with gradient, shimmer effect, and smooth transitions
  - Interactive rating buttons with ripple effects, hover animations, and active states
  - Live/AutoDJ status indicator with animated glow and peak listener stats
  - Floating background elements with smooth animations
  - DJ/Host avatar with 360° rotation on hover
  - Backdrop blur effects for depth and modern aesthetics
- **Popup Mini Player** - Floating player for continuous listening:
  - Persistent across all pages for uninterrupted radio experience
  - Minimizable to a floating icon for unobtrusive browsing
  - Real-time now playing updates with album artwork
  - Play/pause controls and direct link to full player
  - Automatically syncs with main player state
  - Remembers state across page refreshes
- **Real-time Updates** - Auto-refresh of currently playing songs with smooth transitions
- **Recently Played** - Song history with timestamps and album art displayed in sidebar
- **Up Next in Sidebar** - Enhanced preview of the next song with album artwork, relocated to sidebar for better space utilization and accessibility
- **Song Requests** - Browse the song library with a media grid layout and request tracks via modal
  - **Enhanced Error Handling** - User-friendly messages for failed requests
  - Graceful handling of unavailable songs (404 errors)
  - Clear feedback for rate limits and service issues
- **Live Stream Player** - Built-in audio player with prominent controls and volume management
- **Song Ratings** - Modern upvote/downvote interface to shape the playlist
  - **Fixed API Endpoint** - Resolved 405 Method Not Allowed errors
  - Proper CSRF token handling for secure submissions
- **Trending Songs** - Top-rated tracks displayed in real-time
- **Enhanced Homepage** - Rich, dynamic content display with:
  - Latest news articles with featured images and excerpts
  - Upcoming community events with date highlights and location
  - Active polls for community engagement with vote counts
  - **Hot Game Deals** - Top gaming deals with 50%+ savings from CheapShark API:
    - Deal percentage badges with pricing
    - Metacritic scores for quality reference
    - Direct links to purchase
  - **Free Games** - Active free game offers from multiple platforms:
    - Epic Games, Steam, and other platform listings
    - Expiration timers for time-limited offers
    - Platform badges and icons
  - Five-column responsive grid layout for optimal content discovery
  - Hover effects and smooth transitions on all content cards

### Schedule System
- **Playlist Schedule** - Automatically displays schedules from AzuraCast playlists
- **Weekly View** - Schedule grouped by day with time slots
- **Live Status** - Shows which playlists are currently active
- **Now Playing** - Real-time display of current track during scheduled shows

### Community Features
- **User Profiles** - Customizable profiles with bio, avatar, and activity stats
- **XP & Leveling System** - Earn experience points for engagement
- **Achievements** - Unlock badges for reaching milestones
- **Daily Streaks** - Track consecutive days of activity
- **Leaderboard** - Compete with other listeners
- **Messaging** - User-to-user private messaging
- **Comments** - Comment on news articles and content

### Content Systems
- **News & Blog** - Publish articles with rich content, search and filter support
- **Events** - Create and manage community events (live shows, contests, meetups)
  - **Pre-seeded 2026 Gaming Events** - 18 major gaming events including:
    - E3, Gamescom, PAX East/West, Tokyo Game Show
    - The International, League of Legends Worlds, EVO, VALORANT Champions
    - Summer Game Fest, The Game Awards, GDQ charity marathons
    - Nintendo Direct, PlayStation State of Play, Xbox Games Showcase
- **Music Polls** - Let the community vote on playlists and preferences
  - **Pre-seeded Gaming Polls** - 6 entertaining polls including:
    - Platform preferences, favorite game genres, best gaming soundtracks
    - GTA VI feature wishlist (with multi-select support)
    - Fun/odd polls like "one game forever" and "delete all progress for..."
- **DJ/Staff Profiles** - Showcase your DJ team with bios and schedules

### Games Section
- **Free Games** - Browse and claim free game offers from various platforms
- **Game Deals** - Find the best deals powered by **CheapShark API with enhanced reliability**:
  - Rotating user agents to prevent 403 errors
  - HttpClientService integration for consistent API access
  - Automatic retry logic and error handling
- **Store Filtering** - Filter deals by store (Steam, Epic, GOG, etc.)
- **Savings Display** - See how much you save on each deal
- **Reddit Integration** - Automatically fetch free game posts from Reddit

### Videos Section
- **YLYL (You Laugh You Lose)** - Funny videos from Reddit
- **Streamer Clips** - Best clips from Twitch, YouTube, and Kick
- **Platform Filtering** - Filter clips by streaming platform
- **Embedded Players** - Watch videos directly on the site
- **Reddit Integration** - Automatically fetch videos from r/funnyvideos and r/LivestreamFail

### Search System
- **Global Search** - Search across news, events, games, videos, and deals
- **Search API** - JSON API endpoint for search functionality
- **Category Icons** - Visual distinction between result types
- **Search Modal** - Click search icon in navbar to open modern search overlay
- **Real-time Results** - Instant search suggestions as you type
- **Laravel Scout Integration** - Searchable models with collection driver

### DJ/Presenter System
- **DJ Profiles** - Featured DJ pages with social links and genres
- **Weekly Schedule** - Visual schedule of live DJ shows
- **On-Air Status** - Real-time display of who's currently broadcasting
- **DJ Statistics** - Track total shows and listener counts

### Admin Panel
- **Admin Panel** - Overview of stats, activity, and requests
- **User Management** - View, edit, and manage user accounts
- **Song Requests** - Manage the request queue, mark played/rejected
- **News Management** - Create and publish news articles with **integrated RSS scraping controls**:
  - One-click "Sync RSS Feeds" button to import from all active RSS sources
  - Quick access to RSS feed management panel
  - Manual and automated article import from gaming news sites
- **Events Management** - Schedule and manage community events
- **Polls Management** - Create and monitor music polls
- **DJ Profile Management** - Add DJs and manage schedules
- **Games Management** - Manage free games and deals, sync from Reddit/CheapShark
- **Videos Management** - Manage YLYL and clips, sync from Reddit
- **Media Library** - Upload, organize, and manage media files with image optimization
- **Discord Bot Panel** - Monitor and manage Discord integration
- **Settings** - Configure application settings
- **Activity Log** - Audit trail of admin actions
- **RSS Feeds Management** - Manage RSS feeds for automatic news import from popular gaming sites

### Discord Bot Integration
- **User/Role Sync** - Sync Discord server roles and members to database
- **Member Linking** - Link Discord accounts to website accounts
- **Bot Monitoring** - View bot status and activity logs
- **Admin Controls** - Manage bot settings from admin panel at `/admin/discord/settings`
  - Toggle bot on/off
  - Restart bot connection
  - Monitor bot status in real-time
  - Configure channel IDs for logging and welcome messages
  - Enable/disable auto-sync
- **API Integration** - Uses Discord API v10 for all operations

### RSS News Feeds
- **Automatic Import** - Import gaming news articles automatically from RSS feeds
- **Feed Management** - Add, edit, and delete RSS feeds from admin panel at `/admin/rss-feeds`
- **Quick Populate** - One-click button to populate database with 15 high-quality gaming news sources
- **Prepopulated Feeds** - Includes popular gaming news sources:
  - **Major Sites**: IGN, GameSpot, Polygon, Kotaku, Eurogamer
  - **PC Gaming**: PC Gamer, Rock Paper Shotgun
  - **Platform Specific**: PlayStation Blog, Xbox Wire, Nintendo Life
  - **Other Sources**: VG247, Game Informer, GamesRadar+, Destructoid, GameRant
- **Rich Media Support** - Automatically extracts images from RSS feed content
- **Category Organization** - Organize feeds by category (Gaming News, PC Gaming, PlayStation, Xbox, Nintendo)
- **Detailed Descriptions** - Each feed includes comprehensive description for easy identification
- **Scheduled Import** - Feeds checked based on configured fetch intervals (30 mins to 1 hour)
- **Import All Feature** - Batch import from all active feeds with a single click
- **CLI Import** - Manual import via `php artisan rss:import` command

### Gamification
- **XP Rewards** - Earn XP for daily logins, song requests, ratings, comments, and poll votes
- **Leveling System** - Progress through 20 levels with increasing thresholds
- **Daily Streaks** - Build consecutive day streaks for bonus XP
- **Achievement System** - Unlock 15+ achievements across categories:
  - Streak achievements (3, 7, 14, 30, 60, 100 days)
  - Request achievements (1, 10, 50, 100, 500 requests)
  - Level achievements (5, 10, 15, 20)
  - Community achievements (first comment, first vote)

### Social Features
- **Social Login** - Sign in with Discord, Twitch, Steam, or Battle.net
- **Multi-Provider Linking** - Connect multiple social accounts to one profile
- **Discord Integration** - Community Discord server links

### UI/UX
- **Dark/Light Mode** - Toggle themes with preference persistence
- **Mobile Responsive** - Optimized for all screen sizes
- **PWA Support** - Progressive Web App for mobile experience
- **Real-time Updates** - Auto-refresh of now playing data
- **Toast Notifications** - User feedback for actions
- **Dropdown Navigation** - Games and Videos sections have dropdown menus
- **User Dropdown Menu** - Consolidated user actions (Messages, Settings, Profile, Admin Panel, Logout) in a compact dropdown for logged-in users
- **Themed Error Pages** - Custom error pages (404, 500, 403, 419, 429, 503) with fun radio-themed messages and animations that match the site's visual style
- **Coming Soon Mode** - Pre-launch landing page with countdown timer and integrated audio player

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- SQLite / MySQL / PostgreSQL

## 🚀 Installation

1. **Clone the repository:**
```bash
git clone https://github.com/Git-Cosmo/LosSantosRadio.git
cd LosSantosRadio
```

2. **Install dependencies:**
```bash
composer install
npm install
```

3. **Configure environment:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure your `.env` file** with:
   - AzuraCast API credentials
   - Icecast connection details
   - OAuth provider credentials (Discord, Twitch, Steam, Battle.net)
   - Discord bot credentials (optional)

5. **Run migrations and seed:**
```bash
php artisan migrate --seed
```

6. **Seed additional data (recommended):**
```bash
# Seed achievements (unlock system for users)
php artisan db:seed --class=AchievementSeeder

# Seed RSS feeds (15 high-quality gaming news sources)
php artisan db:seed --class=RssFeedSeeder

# Seed 2026 gaming events (18 major events: E3, Gamescom, TGA, etc.)
php artisan db:seed --class=EventSeeder

# Seed entertaining polls (6 gaming polls, including 2 odd ones)
php artisan db:seed --class=PollSeeder
```

7. **Build frontend assets:**
```bash
npm run build
```

8. **Start the development server:**
```bash
php artisan serve
```

## 🆕 Recent Updates (December 2025)

### Backend Fixes & Enhancements
- ✅ **Fixed RssFeed & Achievement Seeders** - Added proper console output messages for better visibility
- ✅ **Fixed CheapShark API 403 Errors** - Integrated HttpClientService with rotating user agents (24 different browser user agents) to prevent API blocking
- ✅ **Enhanced CheapShark Service** - Improved error handling and response validation for stores and deals endpoints

### New Seeders
- ✅ **EventSeeder** - Pre-populated with 18 major 2026 gaming events:
  - Gaming Expos: E3 2026, Gamescom 2026, PAX East/West, Tokyo Game Show, Summer Game Fest
  - Esports: The International, LoL Worlds, EVO, VALORANT Champions
  - Awards: The Game Awards 2026
  - Charity Events: AGDQ & SGDQ 2026
  - Press Conferences: Nintendo Direct, PlayStation State of Play, Xbox Games Showcase
- ✅ **PollSeeder** - Pre-populated with 6 entertaining gaming polls:
  - Platform preferences, favorite genres, best soundtracks
  - GTA VI feature wishlist (multi-select)
  - 2 humorous/odd polls for testing and community engagement

### Admin Panel Improvements
- ✅ **RSS Scraping Management** - Added to `/admin/news` page:
  - "Sync RSS Feeds" button for one-click import from all active sources
  - "Manage RSS Feeds" quick access button to feed management panel
  - Integrated manual trigger for RSS scraping directly from news management

### Frontend Enhancements
- ✅ **Ultra-Modern Now Playing UI** - Significantly enhanced homepage player with:
  - Advanced 3D hover effects with glow on album artwork
  - Floating animated background elements
  - Enhanced gradient animations with color shifting
  - Smooth shimmer effect on progress bar
  - Ripple effects and hover animations on rating buttons
  - 360° rotation animation on DJ/Host avatar
  - Pulsing animations for live indicators and "Up Next" section
  - Backdrop blur effects for modern depth
  - Broadcast tower icon with custom pulse animation

### Documentation
- ✅ **Updated README** - Comprehensive documentation of all new features, fixes, and improvements
- ✅ **Installation Instructions** - Added detailed seeder commands and recommended setup steps



### Environment Variables

```env
# AzuraCast
AZURACAST_BASE_URL=https://your-azuracast-instance.com
AZURACAST_API_KEY=your-api-key
AZURACAST_STATION_ID=1
AZURACAST_CACHE_TTL=30

# Icecast
ICECAST_HOST=localhost
ICECAST_PORT=8000
ICECAST_MOUNT=/stream
ICECAST_SSL=false

# Coming Soon Mode
COMINGSOON=false
LAUNCH_DATE="2024-12-10T18:00:00Z"

# OAuth Providers
DISCORD_CLIENT_ID=
DISCORD_CLIENT_SECRET=
DISCORD_REDIRECT_URI=

# Discord Bot (optional)
DISCORD_BOT_TOKEN=
DISCORD_GUILD_ID=

TWITCH_CLIENT_ID=
TWITCH_CLIENT_SECRET=
TWITCH_REDIRECT_URI=

STEAM_CLIENT_SECRET=

BATTLENET_CLIENT_ID=
BATTLENET_CLIENT_SECRET=
BATTLENET_REDIRECT_URI=

# Request Limits
REQUEST_GUEST_MAX_PER_DAY=2
REQUEST_USER_MIN_INTERVAL_SECONDS=60
REQUEST_USER_MAX_PER_WINDOW=10
REQUEST_USER_WINDOW_MINUTES=20

# Laravel Scout (Search)
SCOUT_DRIVER=collection
SCOUT_QUEUE=false

# Activity Logging
ACTIVITY_LOGGER_ENABLED=true

# Sentry Error Monitoring (optional)
SENTRY_LARAVEL_DSN=
SENTRY_TRACES_SAMPLE_RATE=0.1
```

**📝 Note:** For a complete list of all available environment variables with detailed explanations and examples, see [.env.example](.env.example).

### Discord Bot Admin Controls

The Discord bot can be managed from the admin panel at `/admin/discord/settings`:

- **Toggle Bot On/Off**: Enable or disable Discord bot functionality without changing environment variables
- **Restart Bot**: Gracefully restart the bot connection to apply configuration changes
- **View Bot Status**: See real-time bot online/offline status
- **Configure Channels**: Set log and welcome channel IDs

Bot token and guild ID are configured via environment variables only for security reasons.

### RSS Feed Management

Automatically import gaming news from RSS feeds:

1. **Access Feed Management**: Navigate to `/admin/rss-feeds` in the admin panel
2. **Add Feeds**: Popular gaming news feeds are pre-populated (IGN, GameSpot, PC Gamer, etc.)
3. **Import Articles**: 
   - Click "Import Now" on individual feeds
   - Or use "Import All Feeds" to import from all active feeds
4. **Automated Import**: Run `php artisan rss:import` in a cron job for automatic updates
5. **Configure Intervals**: Set custom fetch intervals for each feed (default: 1 hour)

The RSS feed system automatically:
- Extracts images from feed content
- Prevents duplicate articles
- Sanitizes HTML content
- Supports both RSS 2.0 and Atom feeds

### Docker Queue Configuration

The `examples/docker-queue/` directory contains production-ready Docker Compose configurations for running Laravel queues:

#### Redis Queue Setup (Recommended)
```bash
cd examples/docker-queue
docker-compose -f docker-compose.redis.yml up -d
```

Features:
- Redis 7 for fast queue processing
- MySQL 8.0 for database
- Auto-restarting queue workers
- Optional Laravel Horizon support

#### Database Queue Setup
```bash
cd examples/docker-queue
docker-compose -f docker-compose.database.yml up -d
```

Features:
- MySQL 8.0 for both database and queue
- Two queue workers with priority handling
- Built-in scheduler for automated tasks
- No additional services required

See `examples/docker-queue/README.md` for complete documentation, scaling instructions, and troubleshooting.

### Request Limits

Default limits (configurable via admin panel):
- **Guests**: 2 requests per 24 hours
- **Users**: 1 request per minute, max 10 requests per 20-minute window

### Coming Soon Mode

Enable pre-launch mode by setting `COMINGSOON=true` in your `.env` file. When enabled:
- All visitors see a stylish "Coming Soon" landing page
- A countdown timer shows time until the configured launch date
- An integrated "Now Playing" section displays current track information
- An audio player allows visitors to listen to the stream
- Admin and staff users can still access the full site normally
- API endpoints remain accessible for the audio player functionality

Configure the launch date with `LAUNCH_DATE` environment variable (ISO 8601 format, e.g., `2024-12-10T18:00:00Z`).

To disable coming soon mode and launch the site, set `COMINGSOON=false` or remove the variable entirely.

## 🔐 Admin Panel

Access the admin panel at `/admin`. Admin users must have the `admin` or `staff` role assigned.

### User Roles

The application uses Spatie Laravel Permission for role-based access control. Roles are automatically created when the database is seeded.

- **Admin**: The first user to sign up automatically becomes an admin. Full access to all features and all permissions.
- **Staff**: Can access the admin panel with permissions for content management (news, events, polls) and request management.
- **DJ**: Radio DJs with permissions for schedule management, going live, and request management.
- **Moderator**: Community moderators with permissions for viewing users, managing requests, and viewing activity logs.
- **VIP**: Premium listeners with special privileges.
- **Listener**: Default role for all subsequent users. Can request songs, rate tracks, and participate in polls.
- **Guest**: Reference role for unauthenticated visitors.

**Features:**
- Dashboard with stats and recent activity
- User management with role assignment
- Song request queue management
- News article publishing
- Event management
- Poll creation and monitoring
- DJ profile and schedule management
- Games management (free games + deals)
- Videos management (YLYL + clips)
- Media library management with image optimization
- Discord bot monitoring and settings
- Application settings
- Activity log auditing

## 🏗️ Architecture

### API Endpoints & Caching

The application uses a multi-layer caching strategy for optimal performance:

**Server-side Caching (via AzuraCastService):**
- Now Playing data: 30 seconds
- Station/Playlist data: 5 minutes
- Request library: 60 seconds

**HTTP Cache Headers (via CacheApiResponse middleware):**
| Endpoint | Cache Duration |
|----------|----------------|
| `/api/radio/*` | 30 seconds |
| `/api/playlists/*` | 5 minutes |
| `/api/leaderboard` | 60 seconds |
| `/api/search` | No cache |

### AzuraCast API Integration

All radio data is fetched using official AzuraCast API endpoints:
- `GET /api/nowplaying/{station_id}` - Current playing track
- `GET /api/station/{station_id}` - Station details
- `GET /api/station/{station_id}/playlists` - Playlists with schedule
- `GET /api/station/{station_id}/history` - Play history
- `GET /api/station/{station_id}/requests` - Requestable songs (paginated)
- `POST /api/station/{station_id}/request/{song_id}` - Submit song request

### Local API Endpoints

The application provides the following API endpoints:

**Station API** (AzuraCast compatible):
- `GET /api/station/{stationId}/request` - Get list of requestable songs
- `POST /api/station/{stationId}/request/{requestId}` - Submit a song request
  - Response codes: `200` (success), `403` (rate limited), `404` (song not found), `500` (server error)

**Search API**:
- `GET /api/search?q={query}` - Search across all content types
- `GET /api/search/instant?q={query}` - Quick search for real-time suggestions

**Media API** (requires authentication):
- `GET /api/media` - List media items
- `POST /api/media` - Upload new media
- `DELETE /api/media/{id}` - Delete media

### External API Integrations

- **CheapShark API** - Game deals from multiple stores
- **Reddit JSON API** - Free games and video content
- **Discord API v10** - Bot integration for user/role sync

### Services
- `AzuraCastService` - Radio data fetching and caching
- `IcecastService` - Stream status and listener counts
- `RequestLimitService` - Request rate limiting logic
- `GamificationService` - XP and achievement processing
- `CheapSharkService` - Game deals fetching and sync
- `RedditScraperService` - Reddit content fetching
- `DiscordBotService` - Discord API integration
- `RssFeedService` - RSS feed parsing and article import
- `HttpClientService` - Global HTTP client with random user agent rotation

### Data Transfer Objects (DTOs)
- `NowPlayingDTO` - Current song and stream data
- `SongDTO` - Song metadata
- `SongHistoryDTO` - Play history entries
- `StationDTO` - Station information
- `PlaylistDTO` - Playlist data and schedules

### Models
- User (with XP, levels, streaks)
- Achievement / UserAchievement
- Event
- Poll / PollOption / PollVote
- DjProfile / DjSchedule
- News / Comment / RssFeed
- SongRequest / SongRating
- XpTransaction
- FreeGame / GameDeal / GameStore
- Video
- DiscordRole / DiscordMember / DiscordLog

## 🧪 Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

### Health Checks

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

### Development Server

```bash
composer dev
```

This starts the web server, queue worker, log viewer, and Vite dev server concurrently.

### Scheduled Tasks

The application includes scheduled commands that run automatically:

- **Sitemap Generation** - Runs every 6 hours to generate `/sitemap.xml`
- **RSS Feed Import** - Configure `php artisan rss:import` to run hourly for automatic news updates

To run the scheduler, add this cron entry to your server:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

You can also manually generate the sitemap or import RSS feeds:
```bash
# Generate sitemap
php artisan sitemap:generate

# Import RSS feeds
php artisan rss:import

# Import specific RSS feed by ID
php artisan rss:import --feed=1
```

### Troubleshooting

#### Cache Errors (filemtime, view cache, etc.)

If you encounter errors like `filemtime(): stat failed` or other cache-related issues, clear all Laravel caches:

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

Or clear everything at once:

```bash
php artisan optimize:clear
```

This is commonly needed after:
- Pulling new changes from git
- Switching branches
- When compiled view files become stale

## 📁 Project Structure

```
app/
├── DTOs/                 # Data Transfer Objects
├── Http/
│   ├── Controllers/      # Request handlers
│   │   ├── Admin/        # Admin panel controllers
│   │   └── Auth/         # Authentication controllers
│   └── Middleware/       # Request middleware
├── Models/               # Eloquent models
├── Services/             # Business logic services
└── View/Components/      # Blade view components

resources/
├── views/
│   ├── admin/           # Admin panel views
│   │   ├── discord/     # Discord bot admin views
│   │   ├── games/       # Games admin views
│   │   └── videos/      # Videos admin views
│   ├── djs/             # DJ profile views
│   ├── events/          # Event views
│   ├── games/           # Games public views
│   ├── polls/           # Poll views
│   ├── profile/         # User profile views
│   ├── search/          # Search views
│   ├── videos/          # Videos public views
│   └── layouts/         # Layout components

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
```

## 🔄 Recent Updates

### Site Revamp (December 2024)

#### Multi-Server Support
- **Shoutcast Integration** - Added full support for Shoutcast radio servers alongside AzuraCast and Icecast
- **Radio Server Admin Panel** - New admin section (`/admin/radio`) for managing radio server settings:
  - Server type selection (AzuraCast, Shoutcast, Icecast)
  - Connection testing for all server types
  - Server-specific configuration options
  - Real-time status indicators

#### High-Performance Now Playing Updates
- **Server-Sent Events (SSE)** - Implemented real-time now playing updates as recommended by [AzuraCast documentation](https://azuracast.com/docs/developers/now-playing-data/#high-performance-updates)
- **Automatic Fallback** - Falls back to polling when SSE is unavailable
- **Configurable Update Method** - Choose between SSE and polling in admin panel
- **API Endpoints**:
  - `GET /api/nowplaying` - Current now playing data
  - `GET /api/nowplaying/sse-config` - SSE configuration for clients
  - `GET /api/nowplaying/sse` - SSE proxy endpoint

#### Enhanced Now Playing Widget
- **Visual Improvements** - Gradient accents, animated progress bar with handle, pulse effects
- **Better Animations** - Enhanced equalizer bars, smooth transitions
- **Rating UI** - Redesigned upvote/downvote buttons with hover states
- **Live Badge** - Animated pulse effect for live broadcasts

#### Legal Compliance & Cookie Consent
- **Cookie Consent Banner** - Modern, accessible cookie consent popup with:
  - "Accept All" and "Essential Only" options
  - Local storage persistence
  - Dark/light mode support
- **Legal Pages** - Complete legal documentation:
  - Terms of Service (`/legal/terms`)
  - Privacy Policy (`/legal/privacy`)
  - Cookie Policy (`/legal/cookies`)
- **Footer Enhancement** - New footer design with:
  - Brand logo and tagline
  - Quick navigation links
  - Legal page links
  - Disclaimer (not affiliated with video games)

#### SEO Improvements
- **Enhanced Meta Tags** - Extended robots meta with googlebot, max-image-preview, max-snippet
- **Theme Colors** - Added theme-color and msapplication-TileColor meta tags
- **Structured Data** - Improved JSON-LD with:
  - RadioStation schema with ListenAction
  - WebSite schema with SearchAction
  - BreadcrumbList support for navigation
- **Sitemap Updates** - Legal pages added to sitemap

#### CheapShark API Fix
- **Sorting Issue Fixed** - Corrected the API call to properly fetch high-savings deals first
- **Verified Integration** - Game deals now correctly sorted by savings percentage

### Environment Variables (New)

```env
# Shoutcast Configuration (NEW)
SHOUTCAST_HOST=localhost
SHOUTCAST_PORT=8000
SHOUTCAST_ADMIN_PASSWORD=
SHOUTCAST_SSL=false
SHOUTCAST_STREAM_ID=1

# Radio Server Configuration (NEW)
RADIO_SERVER_TYPE=azuracast          # azuracast, shoutcast, or icecast
RADIO_NOW_PLAYING_METHOD=sse         # sse or polling
RADIO_POLLING_INTERVAL=15            # seconds
RADIO_SSE_ENABLED=true               # Enable SSE high-performance updates
RADIO_SSE_MAX_RUNTIME=28             # Max runtime for SSE proxy (reduce for load balancers with shorter timeouts)
```

#### SSE Proxy Considerations

The SSE proxy endpoint (`/api/nowplaying/sse`) keeps a PHP worker occupied for the connection duration. For production environments with high traffic, consider:

1. **Direct Connection**: Configure clients to connect directly to AzuraCast's SSE endpoint when possible
2. **CORS Setup**: If using direct connection, ensure AzuraCast has proper CORS headers configured for your domain
3. **Timeout Compatibility**: The default `RADIO_SSE_MAX_RUNTIME` of 28 seconds is designed to work with most load balancers (AWS ALB, Nginx default is 30s). Adjust if needed.
4. **Resource Planning**: Each active SSE connection uses one PHP worker. Plan your PHP-FPM pool size accordingly.

### Bug Fixes
- **Admin Controller Middleware Error** - Fixed Laravel 12 compatibility issue where `RssFeedController` was calling the deprecated `middleware()` method in the constructor. Removed the redundant middleware call as routes are already protected by `AdminMiddleware`.
- **Rating API 405 Error** - Resolved "Method Not Allowed" errors when submitting song ratings. Fixed by removing trailing slash from the API endpoint URL in the JavaScript code (`/api/ratings/` → `/api/ratings`).
- **Song Request 404 Handling** - Enhanced error handling for song requests that fail with 404 responses from AzuraCast. Now provides user-friendly error messages like "This song is not available for requests" instead of generic failures. Includes proper exception catching and logging for better debugging.
- **Login Route 404s Fixed** - Added support for both `/auth/{provider}/callback` and `/login/{provider}/callback` OAuth redirect URIs. OAuth providers (Discord, Twitch, Steam, Battle.net) can now be configured with either route pattern, resolving 404 errors when callbacks use the `/login` prefix.
- **Request Page Song List** - Fixed the song library not displaying songs on the request page despite showing the correct total count. The AzuraCast API response uses a `rows` key for paginated request data which is now properly handled.
- **Theme Toggle Navbar** - Fixed the light/dark mode toggle not changing the navbar color. The navbar now correctly uses CSS variables to match the selected theme.
- **Album Art Rotation Removed** - Removed the spinning album art animation when music is playing. Album art now displays statically for a cleaner look.
- **Missing Profile Edit View** - Added the missing `profile/edit.blade.php` view file that allows users to edit their display name and bio.
- **Sticky Footer** - Fixed the footer to stick to the bottom of all pages regardless of content height using flexbox layout.

### SEO Improvements
Comprehensive SEO best practices have been implemented across the application:

- **Meta Tags**: Enhanced title, description, and keyword meta tags with dynamic content support
- **Canonical URLs**: Automatic canonical URL generation for all pages
- **Open Graph**: Full Open Graph meta tag support for social sharing (Facebook, LinkedIn, etc.)
- **Twitter Cards**: Complete Twitter Card meta tags for optimized Twitter sharing
- **Structured Data**: JSON-LD schema markup for RadioStation and NewsArticle content types
- **Sitemap**: Dynamic XML sitemap generation at `/sitemap.xml` including all public pages, news articles, events, and polls
- **Robots.txt**: Enhanced robots.txt with proper allow/disallow rules and sitemap reference
- **Accessibility**: Improved alt text for images, ARIA labels for interactive elements, and semantic HTML improvements
- **Page-specific SEO**: Individual pages (like news articles) now pass custom SEO meta data for better search engine indexing

### Homepage & Song Library Improvements
- **Recently Played Songs** - Reduced the "Recently Played" section on the homepage to show only the last 2 songs for a cleaner look.
- **Our DJs Section Removed** - Removed the "Our DJs" section from the homepage to streamline the interface.
- **Song Library Pagination** - Added a modern, clean pagination system to the Song Library page. The library now displays songs in a responsive grid layout with pagination controls that include:
  - Page numbers with ellipsis for large page counts
  - Previous/Next navigation buttons
  - Current page indicator
  - Total song count and page information
  - Search functionality integrated with pagination

### UI & Navigation Improvements
- **User Dropdown Menu** - Reorganized the navbar for logged-in users. User-specific links (Messages, Settings, Profile, Admin Panel, Logout) are now consolidated into a clean dropdown menu under the user's avatar and name, freeing up navbar space and improving mobile experience.
- **Custom Error Pages** - Designed and implemented themed error pages that match the site's radio aesthetic:
  - **404 - Page Not Found**: "This Track Doesn't Exist!" with floating music notes animation
  - **500 - Server Error**: "Technical Difficulties!" with spark animations
  - **403 - Access Denied**: "Backstage Pass Required!" with VIP badge styling
  - **419 - Session Expired**: "Your Session Hit a Break!" with timer animations
  - **429 - Too Many Requests**: "Slow Down, Speedy!" with speed lines and cooldown bar
  - **503 - Service Unavailable**: "Station Under Maintenance!" with gear animations
- **Coming Soon Page** - A visually appealing pre-launch landing page featuring:
  - Countdown timer to launch date
  - Now Playing section with integrated audio player
  - Volume control and mute functionality
  - Feature preview cards
  - No navbar for focused design
  - Dark/Light mode toggle
- **Admin Panel Restyling** - Updated admin panel UI with:
  - Gradient effects on buttons and stat cards
  - Hover animations on cards and navigation
  - Improved visual consistency with main site
  - Accent color borders on hover states
  - Reduced motion support for accessibility

### JavaScript Migration
All manually-created JavaScript has been migrated from inline Blade templates to modular files in `resources/js`:
- **Live Clock** (`modules/live-clock.js`) - 12/24 hour toggle clock component
- **Search Modal** (`modules/search-modal.js`) - Global search overlay functionality
- **Radio Player** (`modules/radio-player.js`) - Audio playback, song ratings, trending songs
- **UI Helpers** (`modules/ui-helpers.js`) - Mobile menu, scroll to top, entrance animations, toast notifications

All JavaScript is now built using `npm run build` via Vite, providing better code organization, tree-shaking, and minification.

## 🔍 SEO Configuration

The application includes comprehensive SEO support out of the box:

### Meta Tags
All pages include proper meta tags. Custom meta data can be passed to the layout component:

```blade
<x-layouts.app 
    :title="$pageTitle"
    :metaDescription="$description"
    :ogImage="$imageUrl"
    :canonicalUrl="$url"
>
```

### Sitemap
A dynamic XML sitemap is automatically generated at `/sitemap.xml` and includes:
- All static pages (home, schedule, news, events, polls, etc.)
- Published news articles with proper lastmod timestamps
- Published events
- Active polls

### Robots.txt
The robots.txt is dynamically generated at `/robots.txt` and automatically includes:
- Allow search engines to index public content
- Block admin areas and private user sections
- Reference the sitemap location using the correct domain from `APP_URL`

### Structured Data
JSON-LD structured data is included for:
- RadioStation (on all pages)
- NewsArticle (on individual news pages)

Custom structured data can be added via the `structuredData` prop in the layout component.

## 🔐 OAuth Configuration

OAuth providers support both `/auth/{provider}/callback` and `/login/{provider}/callback` redirect URI patterns. Configure your OAuth applications with either format:

```env
# Using /auth prefix (default)
DISCORD_REDIRECT_URI="${APP_URL}/auth/discord/callback"

# Or using /login prefix (also supported)
DISCORD_REDIRECT_URI="${APP_URL}/login/discord/callback"
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## 📄 License

This project is open-source.
