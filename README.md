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
- **Song Requests** - Browse the song library and request tracks
- **Live Stream Player** - Built-in audio player with volume control
- **Song Ratings** - Upvote/downvote songs to shape the playlist
- **Trending Songs** - Top-rated tracks displayed in real-time

### Community Features
- **User Profiles** - Customizable profiles with bio, avatar, and activity stats
- **XP & Leveling System** - Earn experience points for engagement
- **Achievements** - Unlock badges for reaching milestones
- **Daily Streaks** - Track consecutive days of activity
- **Leaderboard** - Compete with other listeners
- **Messaging** - User-to-user private messaging
- **Comments** - Comment on news articles and content

### Content Systems
- **News & Blog** - Publish articles with rich content
- **Events** - Create and manage community events (live shows, contests, meetups)
- **Music Polls** - Let the community vote on playlists and preferences
- **DJ/Staff Profiles** - Showcase your DJ team with bios and schedules

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
- **Settings** - Configure application settings
- **Activity Log** - Audit trail of admin actions

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

### Request Limits

Default limits (configurable via admin panel):
- **Guests**: 2 requests per 24 hours
- **Users**: 1 request per minute, max 10 requests per 20-minute window

## 🔐 Admin Panel

Access the admin panel at `/admin`. Admin users must have the `admin` or `staff` role assigned.

**Features:**
- Dashboard with stats and recent activity
- User management with role assignment
- Song request queue management
- News article publishing
- Event management
- Poll creation and monitoring
- DJ profile and schedule management
- Application settings
- Activity log auditing

## 🏗️ Architecture

### Services
- `AzuraCastService` - Radio data fetching and caching
- `IcecastService` - Stream status and listener counts
- `RequestLimitService` - Request rate limiting logic
- `GamificationService` - XP and achievement processing

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
│   └── Middleware/       # Request middleware
├── Models/               # Eloquent models
├── Services/             # Business logic services
└── View/Components/      # Blade view components

resources/
├── views/
│   ├── admin/           # Admin panel views
│   ├── djs/             # DJ profile views
│   ├── events/          # Event views
│   ├── polls/           # Poll views
│   ├── profile/         # User profile views
│   └── layouts/         # Layout components

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## 📄 License

This project is open-source.
