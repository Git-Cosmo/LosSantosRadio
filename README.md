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

## 📦 Features

### Radio Experience
- **Now Playing Widget** - Real-time display of currently playing songs with album art, progress bar, and auto-refresh
- **Recently Played** - Song history with timestamps
- **Up Next** - Preview the next song in queue
- **Song Requests** - Browse the song library with a media grid layout and request tracks via modal
- **Live Stream Player** - Built-in audio player with volume control
- **Song Ratings** - Upvote/downvote songs to shape the playlist
- **Trending Songs** - Top-rated tracks displayed in real-time

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
- **Music Polls** - Let the community vote on playlists and preferences
- **DJ/Staff Profiles** - Showcase your DJ team with bios and schedules

### Games Section
- **Free Games** - Browse and claim free game offers from various platforms
- **Game Deals** - Find the best deals powered by CheapShark API
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

### DJ/Presenter System
- **DJ Profiles** - Featured DJ pages with social links and genres
- **Weekly Schedule** - Visual schedule of live DJ shows
- **On-Air Status** - Real-time display of who's currently broadcasting
- **DJ Statistics** - Track total shows and listener counts

### Admin Panel
- **Dashboard** - Overview of stats, activity, and requests
- **User Management** - View, edit, and manage user accounts
- **Song Requests** - Manage the request queue, mark played/rejected
- **News Management** - Create and publish news articles
- **Events Management** - Schedule and manage community events
- **Polls Management** - Create and monitor music polls
- **DJ Profile Management** - Add DJs and manage schedules
- **Games Management** - Manage free games and deals, sync from Reddit/CheapShark
- **Videos Management** - Manage YLYL and clips, sync from Reddit
- **Discord Bot Panel** - Monitor and manage Discord integration
- **Settings** - Configure application settings
- **Activity Log** - Audit trail of admin actions

### Discord Bot Integration
- **User/Role Sync** - Sync Discord server roles and members to database
- **Member Linking** - Link Discord accounts to website accounts
- **Bot Monitoring** - View bot status and activity logs
- **Admin Controls** - Manage bot settings from admin panel
- **API Integration** - Uses Discord API v10 for all operations

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

6. **Seed achievements (optional):**
```bash
php artisan db:seed --class=AchievementSeeder
```

7. **Build frontend assets:**
```bash
npm run build
```

8. **Start the development server:**
```bash
php artisan serve
```

## ⚙️ Configuration

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
```

### Discord Bot Setup

1. Create a Discord application at [Discord Developer Portal](https://discord.com/developers/applications)
2. Create a bot user and get the bot token
3. Enable Server Members Intent and Message Content Intent
4. Add the bot to your server with appropriate permissions
5. Get your Guild (Server) ID by enabling Developer Mode in Discord
6. Add `DISCORD_BOT_TOKEN` and `DISCORD_GUILD_ID` to your `.env` file

### Request Limits

Default limits (configurable via admin panel):
- **Guests**: 2 requests per 24 hours
- **Users**: 1 request per minute, max 10 requests per 20-minute window

## 🔐 Admin Panel

Access the admin panel at `/admin`. Admin users must have the `admin` or `staff` role assigned.

### User Roles

- **Admin**: The first user to sign up automatically becomes an admin. Full access to all features.
- **Staff**: Can access the admin panel with limited permissions.
- **Listener**: Default role for all subsequent users. Can request songs, rate tracks, and participate in polls.

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
- News / Comment
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

### Bug Fixes
- **Login Route 404s Fixed** - Added support for both `/auth/{provider}/callback` and `/login/{provider}/callback` OAuth redirect URIs. OAuth providers (Discord, Twitch, Steam, Battle.net) can now be configured with either route pattern, resolving 404 errors when callbacks use the `/login` prefix.
- **Request Page Song List** - Fixed the song library not displaying songs on the request page despite showing the correct total count. The AzuraCast API response uses a `rows` key for paginated request data which is now properly handled.
- **Theme Toggle Navbar** - Fixed the light/dark mode toggle not changing the navbar color. The navbar now correctly uses CSS variables to match the selected theme.
- **Album Art Rotation Removed** - Removed the spinning album art animation when music is playing. Album art now displays statically for a cleaner look.

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
