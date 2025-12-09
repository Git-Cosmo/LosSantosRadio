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
- **Streaming**: Icecast / Shoutcast (multi-server support with Docker orchestration)
- **Real-time**: Laravel Reverb (WebSocket) for instant now playing updates
- **Caching**: Universal CacheService with Redis support and namespace organization
  - AzuraCast data: 30 seconds (configurable, real-time radio updates)
  - CheapShark deals: 1 hour (gaming deals)
  - Reddit content: 30 minutes (free games and videos)
  - IGDB game data: 12 hours (game metadata)
  - Discord bot data: 5 minutes (bot status and guild info)
  - Lyrics data: 24 hours (persistent lyrics cache)
- **Database**: SQLite / MySQL / PostgreSQL
- **HTTP Client**: Guzzle with random user agent rotation
- **Search**: Laravel Scout with collection driver
- **Permissions**: Spatie Laravel Permission
- **Media**: Spatie Laravel Media Library with Intervention Image
- **Sitemap**: Spatie Laravel Sitemap (auto-generated every 6 hours)
- **Lyrics**: Genius API integration with guest limits and monetization flow
- **Build Tool**: Vite 7 with hot reload support
- **CSS Architecture**: Modular CSS organization with 36+ feature-specific stylesheets
- **Frontend Assets**: All CSS/JS externalized to resources/ directory for maintainability

## 📂 Frontend Architecture

### CSS Organization

All CSS has been extracted from Blade templates into modular CSS files located in `resources/css/`:

- **Core Styles** (`layout.css`, `audio-player.css`, `radio-player.css`, `home.css`): Main layout and player components
- **Feature Styles** (`games.css`, `songs.css`, `news-index.css`, `polls-show.css`, etc.): Page-specific styles
- **Component Styles** (`quick-stats.css`, `floating-bg.css`): Reusable component styles
- **Admin Styles** (`admin-layout.css`, `admin-auth.css`, etc.): Admin panel styles  
- **Error Pages** (`error-404.css`, `error-503.css`, etc.): Error page styles

All CSS modules are imported via `resources/css/app.css` and built with Vite.

### JavaScript Organization

JavaScript modules are organized in `resources/js/modules/`:

- `radio-player.js` - Radio player controls and state management
- `websocket-player.js` - Real-time now playing updates via Laravel Reverb
- `lyrics-modal.js` - Lyrics modal functionality
- `toast-notifications.js` - Toast notification system
- `search-modal.js` - Global search functionality
- `keyboard-shortcuts.js` - Keyboard navigation
- `ui-helpers.js` - Common UI utilities

All modules are imported via `resources/js/app.js` and bundled with Vite.

### Updating Frontend Assets

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

Vite automatically compiles all CSS and JavaScript assets. All frontend code is located in `resources/css/` and `resources/js/` directories - no inline styles or scripts in Blade templates.

## 📦 Features

### New vBulletin-Inspired Homepage

The homepage features a modern, community-focused layout inspired by vBulletin/vAdvanced:

- **Center Content Area**: Showcases now playing info, latest news, upcoming events, and hot game deals
- **Right Sidebar**: Community stats, active polls, free games, quick links, and Discord integration
- **Responsive Design**: Adapts seamlessly from desktop to mobile with collapsing sidebars
- **Aggregated Content**: Pulls data from all major features (News, Events, Polls, Games, Radio)
- **Real-time Updates**: Live listener count and now playing information
- **Quick Navigation**: Easy access to all major sections of the site

Homepage route: `/` (main landing page)
Radio player route: `/radio` (full player interface)

### Radio Experience
- **Enhanced Audio Player** - Feature-rich embedded player with:
  - **Autoplay functionality** - Automatically resumes playback based on user preference stored in localStorage (set via `playerAutoplay` key)
  - Album artwork with animated visualizer bars that respond to playback
  - Large play/pause button with gradient styling and pulse animation
  - **Volume controls** - Slider with mute/unmute toggle and visual feedback
  - Now playing information with song title, artist, and listener count
  - Live/AutoDJ status badge with color-coded indicators
  - Progress bar showing elapsed and total time for songs
  - External player link for popup window
  - Remembers volume settings and autoplay preference via localStorage
  - Fully responsive design optimized for mobile and tablet devices
- **Redesigned Now Playing Widget** - **Compact, professional display** with enhanced efficiency:
  - **Compact album artwork (120x120px)** - Reduced from 180px for better space utilization while maintaining visual appeal
  - Enhanced 3D hover effects with shadows and ambient glow
  - Animated audio visualizer with glowing effects and smooth bounce animations
  - Dynamic gradient text styling for song titles with multi-color shifting animation
  - Tightened spacing and reduced padding for more efficient layout
  - Play indicator icon with pulsing gradient background
  - Artist display with icon and enhanced typography
  - Real-time listener count with pulsing animations
  - Enhanced progress bar with gradient, shimmer effect, and smooth transitions
  - Interactive rating buttons with ripple effects, hover animations, and active states
  - Compact DJ/Host info section with streamlined text
  - Live/AutoDJ status indicator with animated glow and peak listener stats
  - Overall height reduced by ~30% for better content visibility
  - Backdrop blur effects for depth and modern aesthetics
- **Schedule Display** - Dynamic playlist schedule showing:
  - Weekly schedule organized by day with today highlighted
  - Time slots with formatted start/end times (12-hour format)
  - Active show indicators with "ON AIR" badges
  - Playlist names with smooth hover effects
  - Fetched dynamically from AzuraCast API endpoint (`/api/station/{id}/playlists`)
  - Graceful fallback when schedule data unavailable
  - Mobile-responsive layout with collapsible days
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
- **Song Requests** - **Enhanced UI with modern design** - Browse the song library and request tracks:
  - **Redesigned Request Page** - Professional layout with improved visual hierarchy
  - Modern table design with album art thumbnails and enhanced typography
  - **Enhanced Search** - Improved search functionality with better placeholder text
  - **Visual Status Cards** - Color-coded status indicators (green for allowed, amber for limit reached)
  - **Improved Queue Display** - Compact queue view with better organization
  - **Better Mobile Support** - Fully responsive layout for all screen sizes
  - **Animated Toast Notifications** - Modern success/error messages with smooth animations
  - **Enhanced Error Handling** - User-friendly messages for failed requests
  - Graceful handling of unavailable songs (404 errors)
  - Clear feedback for rate limits and service issues
  - Auto-reload after successful request to update queue
- **Live Stream Player** - Built-in audio player with prominent controls and volume management
- **Song Ratings** - Modern upvote/downvote interface to shape the playlist
  - **Fixed API Endpoint** - Resolved 405 Method Not Allowed errors
  - Proper CSRF token handling for secure submissions
- **Trending Songs** - Top-rated tracks displayed in real-time
- **Enhanced Homepage** - Rich, dynamic content display with:
  - Latest news articles with featured images and excerpts
  - Upcoming community events with date highlights, location, and like counts
  - Active polls for community engagement with vote counts
  - **Hot Game Deals** - Top gaming deals with 50%+ savings from CheapShark API:
    - Clickable deal cards linking to detailed deal pages
    - Deal percentage badges with pricing
    - Metacritic scores for quality reference
    - Full deal information including store details
  - **Free Games** - Active free game offers from multiple platforms:
    - Clickable game cards linking to detailed game pages
    - Epic Games, Steam, and other platform listings
    - Expiration timers for time-limited offers
    - Platform badges and icons
    - Comprehensive game information and external claim links
  - **Fixed Layout** - Proper sidebar display on desktop (2fr main + 1fr sidebar grid)
  - All homepage content sections now properly contained within the main grid
  - Responsive grid layout for optimal content discovery
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
- **Events** - Create and manage community events (live shows, contests, meetups) with enhanced interactive features:
  - **Redesigned Events Pages** - Modern, visually appealing layout with:
    - Gradient hero section with event statistics
    - Featured events showcase with hover effects and images
    - Live event indicators with animated badges
    - Enhanced date badges and category icons
    - Interactive event cards with smooth transitions
    - Improved sidebar with category cards and Discord integration
  - **Enhanced Event Details Page** - Professional event display featuring:
    - Full-width hero images with overlay badges
    - Color-coded information cards for start time, end time, and location
    - Interactive like and reminder buttons with real-time updates
    - Status indicators for live, upcoming, and past events
    - Enhanced typography and better content layout
    - Organizer profile section with call-to-action
  - **Event Likes System** - Users can like events with real-time count updates
  - **Event Reminders** - Authenticated users can subscribe to email reminders for upcoming events
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
- **Redesigned Games Hub** - Modern, cohesive game discovery experience:
  - **Hero Section** - Eye-catching gradient hero with game icon and tagline
  - **Enhanced Search** - Large search bar with focus states and smooth transitions
  - **Consistent Visual Design** - Color-coded sections (red for deals, green for free games)
  - **Improved Cards** - Modern card designs with:
    - Smooth hover animations with lift effect
    - Image zoom on hover for engaging interactions
    - Better visual hierarchy with larger headings
    - Consistent spacing and typography
  - **Better Layout** - Responsive grid that adapts to all screen sizes
- **Free Games** - Browse and claim free game offers from various platforms
  - **Individual Game Pages** - Detailed pages for each free game with full descriptions, expiration dates, and claim links
  - **Related Games** - Discover similar free games from the same platform
  - **Platform Badges** - Visual indicators for Epic Games, Steam, and other stores
  - **Expiration Timers** - Clear countdown for time-limited offers
- **Game Deals** - Find the best deals powered by **CheapShark API with enhanced reliability**:
  - **Individual Deal Pages** - Comprehensive deal information with Metacritic scores and store details
  - **Related Deals** - Explore more deals from the same store
  - **Savings Badges** - Prominent discount percentages with gradient styling
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

### Media Hub (GameBanana-style)
- **Comprehensive Mods & Content Platform** - Full-featured content sharing system for gaming mods, maps, and enhancements
- **8 Game Categories Pre-configured**:
  - **Counter-Strike 2** - Maps, Skins, HUD Mods, Sound Mods, Server Plugins
  - **Minecraft** - Mods, Texture Packs, Maps, Data Packs, Skins, Server Plugins
  - **GTA V** - Scripts, Vehicles, Maps, Weapons, Peds, Graphics Mods
  - **Skyrim** - Gameplay Mods, Quests, Graphics & Visuals, Armor & Weapons, Followers, Utilities
  - **Cyberpunk 2077** - Gameplay Mods, Graphics, Clothing & Armor, Vehicles, Weapons, Utilities
  - **Starfield** - Gameplay Mods, Ship Mods, Outpost Mods, Graphics, UI Improvements, Weapons & Armor
  - **Baldur's Gate 3** - Class Mods, Companions, Gameplay, Visual Mods, Quality of Life, Equipment
  - **Terraria** - Content Mods, Quality of Life, Texture Packs, Tools
- **45+ Subcategories** - Organized by content type for easy browsing
- **User Upload System** - Authenticated users can upload mods and content:
  - File upload support (up to 100MB)
  - Image thumbnails (up to 5MB)
  - Version tracking
  - Approval workflow for quality control
- **Browse & Discover**:
  - Browse by game category
  - Filter by subcategory (maps, skins, mods, etc.)
  - Featured content showcase
  - Popular items by downloads
  - Latest uploads
  - Advanced search with Laravel Scout
- **Download System**:
  - **Public Viewing** - All visitors can browse and view content details
  - **Auth-Gated Downloads** - Must be logged in to download files
  - Download counter tracking
  - View counter tracking
- **Spatie Media Library Integration**:
  - Multiple file collections (files, images, screenshots)
  - Automatic file size formatting
  - Image optimization
  - Media management in admin panel
- **Ratings & Reviews** - Community rating system (ready for implementation)
- **Admin Management**:
  - Full CRUD for media items
  - Category and subcategory management
  - Approval/rejection workflow
  - Bulk actions
  - Featured content selection
- **Automated Content Import**:
  - **CurseForge Integration** - Auto-import popular Minecraft mods
  - **Steam Workshop Integration** - Import trending CS2 maps and items
  - **GTA5-Mods.com Scraping** - RSS feed import for GTA V mods
  - **Nexus Mods Integration** - Import trending Skyrim mods
  - CLI command: `php artisan media:import --source=all`
  - Configurable via optional API keys
  - Automatic duplicate detection
  - Smart subcategory assignment
- **SEO Optimized**:
  - Included in sitemap generation
  - SEO-friendly URL structure: `/media/{game}/{type}/{item}`
  - Searchable via global search
  - Meta tags ready for implementation

### Search System
- **Global Search** - **Fixed and fully functional** - Search across news, events, polls, games, videos, DJ profiles, deals, and media items
- **Laravel Scout Integration** - All content types use Scout's Searchable trait for enhanced search relevance:
  - News articles indexed by title and content
  - Events indexed by title, description, location, and type
  - Polls indexed by question and description
  - Free games indexed by title, description, platform, and store
  - Game deals indexed by title with pricing and scoring data
  - Videos indexed by title, description, category, platform, and author
  - DJ profiles indexed by stage name and bio
  - **Media items** indexed by title, description, content, category, subcategory, and uploader
- **Search API** - JSON API endpoint at `/api/search` with proper response format:
  - Returns `success` flag and `results` array
  - Each result includes `id`, `type`, `title`, `url`, `description`, `date`, and `date_formatted`
  - Supports pagination and result limiting
- **Category Icons** - Visual distinction between result types
- **Search Modal** - Click search icon in navbar to open modern search overlay
- **Real-time Results** - Instant search suggestions as you type with debouncing
- **Keyboard Navigation** - Press ESC to close search modal

### DJ/Presenter System
- **DJ Profiles** - Featured DJ pages with social links and genres
- **Weekly Schedule** - Visual schedule of live DJ shows
- **On-Air Status** - Real-time display of who's currently broadcasting
- **DJ Statistics** - Track total shows and listener counts

### Admin Panel
- **Admin Panel** - Overview of stats, activity, and requests
- **Analytics Dashboard** - **NEW!** Comprehensive visitor tracking and statistics:
  - Real-time online user count
  - Session analytics (guests vs authenticated users)
  - Device type breakdown (mobile, tablet, desktop)
  - Top countries by visitor count
  - Browser statistics
  - Configurable date ranges (7, 30, 60, 90 days)
  - Privacy-first with IP anonymization
  - Automatic cleanup of old data
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
- **Media Hub Management** - **NEW!** Complete content moderation system:
  - **Media Items CRUD** - Full control over uploaded content
  - **Approval Workflow** - Review and approve/reject user submissions
  - **Category Management** - Create and organize game categories
  - **Subcategory Management** - Define content types per game
  - **Bulk Actions** - Efficient content moderation
  - **Featured Content** - Highlight quality submissions
  - **Download/View Statistics** - Track content popularity
  - **Automated Import** - One-click import from CurseForge, Steam Workshop, Nexus Mods, GTA5-Mods
- **Media Library** - Upload, organize, and manage media files with image optimization
- **Discord Bot Panel** - Monitor and manage Discord integration
- **Settings Dashboard** - **NEW!** Modern, user-friendly settings interface at `/admin/settings/dashboard`:
  - **Grouped Settings Organization** - Settings organized into logical sections:
    - General Settings (site name, description, contact email)
    - Theme Settings (seasonal theme dropdown selector)
    - Feature Toggles (comments, song requests, polls, maintenance mode) with modern checkboxes
    - Radio Settings (station ID, update intervals)
    - Rate Limits (guest/user request limits, lyrics view limits)
  - **Modern UI** - Clean, intuitive interface with visual feedback
  - **Batch Updates** - Save all settings changes at once with one click
  - **Visual Checkboxes** - Modern toggle switches for boolean settings
  - **Dropdown Selectors** - Easy theme selection with visual previews
  - **Sticky Save Button** - Always accessible save button at bottom of page
  - **Advanced Mode** - Link to traditional key-value editor for power users
- **Activity Log** - Audit trail of admin actions
- **RSS Feeds Management** - Manage RSS feeds for automatic news import from popular gaming sites
- **Theme Management** - Control global appearance themes at `/admin/theme`:
  - **No Theme** - Default appearance without overlay effects
  - **Christmas Theme** - Festive holiday experience with:
    - Animated snowfall across the entire page (100 snowflakes)
    - Colorful string lights at the top (red, green, blue, gold)
    - Festive corner decorations (tree, star, gift, bell emojis)
    - Red and green accent colors on interactive elements
    - Swinging animation for decorations
    - Twinkling lights effect
  - **New Year Theme** - Celebration party effects with:
    - Periodic firework displays (launches every 2 seconds)
    - Animated confetti falling from top (30 pieces in various colors)
    - Rainbow color animations on key elements
    - Party banner with celebration message
    - Purple and pink party color scheme
    - Glowing pulse effects on player and controls
  - **Admin-Only Control** - Themes applied globally, no user override
  - **Instant Updates** - Changes take effect immediately site-wide
  - **Performance Optimized** - Uses requestAnimationFrame for smooth animations
  - **Accessibility Friendly** - Respects prefers-reduced-motion settings
  - Theme files located in `public/themes/` (christmas.js, newyear.js)

### Discord Bot Integration
- **User/Role Sync** - Sync Discord server roles and members to database
- **Member Linking** - Link Discord accounts to website accounts
- **Bot Monitoring** - View bot status and activity logs
- **Admin Controls** - Comprehensive bot management from admin panel at `/admin/discord/settings`:
  - **Start/Stop Controls** - Dedicated buttons to start and stop the Discord bot with one click
  - **Real-time Status** - Visual indicators showing bot Running/Stopped state
  - Restart bot connection for quick recovery
  - Configure channel IDs for logging and welcome messages
  - Enable/disable auto-sync for roles and members
  - Bot state persists across settings form submissions
- **API Integration** - Uses Discord API v10 for all operations
- **Cache Management** - Smart caching (5 minutes TTL) with automatic cache clearing on configuration changes
- **Separation of Concerns** - Bot lifecycle (start/stop) managed separately from general settings for clearer UX

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

### Social Features & Authentication
- **OAuth Social Login** - Secure authentication via OAuth providers (no traditional registration):
  - Discord
  - Twitch  
  - Steam
  - Battle.net
- **Multi-Provider Linking** - Connect multiple social accounts to one profile
- **Profile Management** - Unlink accounts from linked accounts page
- **Discord Integration** - Community Discord server links

### UI/UX
- **Dark/Light Mode** - Toggle themes with preference persistence
- **Mobile Responsive** - Optimized for all screen sizes
- **PWA Support** - Progressive Web App for mobile experience
- **Real-time Updates** - Auto-refresh of now playing data
- **Toast Notifications** - User feedback for actions
- **Enhanced Navigation** - **Redesigned navigation bar** with organized dropdowns and improved structure:
  - **Home** - Direct link to main landing page
  - **Radio Dropdown** - Consolidated radio features:
    - Schedule - View playlist schedule and live shows
    - Requests - Browse and request songs from the library
  - **News** - Latest announcements and articles
  - **Events** - Community events and live shows
  - **Polls** - Community polls and voting
  - **Games Dropdown** - Gaming content hub:
    - Free Games - Current free game offers
    - Game Deals - Hot deals and discounts
    - Downloads - Media Hub for mods, maps, and community content
  - **Videos Dropdown** - Video content:
    - YLYL - You Laugh You Lose videos
    - Streamers Clips - Gaming clips and highlights
  - Active state highlighting for current page
  - Fully responsive mobile menu with smooth animations
  - Consistent dropdown styling with hover effects
- **User Dropdown Menu** - Consolidated user actions (Messages, Settings, Profile, Admin Panel, Logout) in a compact dropdown for logged-in users
- **Themed Error Pages** - Custom error pages (404, 500, 403, 419, 429, 503) with fun radio-themed messages and animations that match the site's visual style
- **Coming Soon Mode** - Pre-launch landing page with countdown timer and integrated audio player
- **Listen Button** - Relocated to bottom-left corner for better UX:
  - Styled to match logo accent color
  - Positioned to not conflict with scroll-to-top button
  - Opens modal with multiple listening options
  - Responsive mobile positioning
- **Modern Footer** - Redesigned compact footer with:
  - Grid layout for organized content (Explore, Community, Connect)
  - Shorter vertical height for better space utilization
  - Brand section with logo and tagline
  - Quick links to major sections (Home, Schedule, Requests, News)
  - Community links (Events, Games, Videos, DJ Profiles)
  - Social connections (Discord invite, About, Contact)
  - Legal links in compact bottom bar (Terms, Privacy, Cookies)
  - Copyright and powered-by information
  - Fully responsive with mobile-optimized layout (2 columns on tablet, 1 column on mobile)
  - Centered alignment on small screens for better readability

### SEO & Discoverability
- **Comprehensive Meta Tags** - Title, description, keywords optimized for search engines
- **Open Graph Tags** - Enhanced social media sharing with custom images
- **Twitter Cards** - Rich previews when shared on Twitter
- **JSON-LD Structured Data** - Schema.org markup for RadioStation and WebSite
- **XML Sitemap** - Auto-generated sitemap including:
  - Static pages and navigation routes
  - News articles (up to 1000)
  - Events (up to 1000)
  - Polls (up to 500)
  - DJ profiles
  - Games and game information
  - Free game offers
  - Game deals
  - Videos (YLYL and clips)
  - **Media hub** - Categories and content items (up to 1000)
- **Robots.txt** - Dynamic generation with proper allow/disallow rules
- **Canonical URLs** - Prevent duplicate content issues
- **Search Engine Friendly** - Clean URLs and proper HTML semantics

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 20+
- SQLite / MySQL / PostgreSQL

## 🚀 Installation

> **⚠️ Important**: After cloning or pulling changes, you **must** run `npm install && npm run build` to compile frontend assets. Without this step, the site will load without any styling. See [DEPLOYMENT_NOTES.md](DEPLOYMENT_NOTES.md) for details.

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

# Seed media categories (8 games with 45+ subcategories)
php artisan db:seed --class=MediaCategorySeeder
```

7. **Optional: Import media content from external sources:**
```bash
# Import popular mods and content from various sources
# Requires API keys in .env (optional, see configuration section)
php artisan media:import

# Import from specific source
php artisan media:import --source=minecraft --limit=50
```

8. **Build frontend assets:**
```bash
npm run build
```

8. **Start the development server:**
```bash
php artisan serve
```

## 🆕 Recent Updates (December 2025)

### Quick Win Features - Enhanced User Experience (December 8, 2025)
- ✅ **Keyboard Shortcuts** - Power user controls:
  - Space bar or K to play/pause the player
  - Arrow Up/Down to adjust volume (±5%)
  - M to mute/unmute audio
  - Works globally except in text input fields
  - Visual feedback toasts show current action
  - Initialized automatically on page load
- ✅ **Share Now Playing** - Social engagement feature:
  - Share button in audio player
  - Copies formatted song info to clipboard
  - Social media friendly text format
  - Includes title, artist, and listen link
  - Success toast notification on copy
- ✅ **Favorite Songs System** - Personal music collection:
  - Heart button in audio player to favorite songs
  - Save up to 100 favorites in localStorage
  - Dedicated favorites page at `/favorites`
  - View all favorited songs with artwork
  - Remove individual favorites or clear all
  - Shows when each song was added
  - No backend/database required
- ✅ **Player Minimize Mode** - Sticky mini player:
  - Minimize button collapses player to corner
  - Stays fixed in bottom-right while scrolling
  - Compact 350px width with all controls
  - Remembers state across page reloads
  - Expand button returns to full size
  - Mobile-optimized (300px on small screens)
- ✅ **Quick Stats Widget** - Live radio metrics:
  - Current listener count display
  - Peak listeners for today
  - Total songs played counter
  - Live/AutoDJ stream status
  - Auto-refresh every 30 seconds
  - Manual refresh button available
  - Component: `<x-quick-stats-widget />`
- ✅ **Toast Notifications** - Non-intrusive alerts:
  - "Now Playing" alerts when song changes
  - Auto-dismiss after 5 seconds
  - Can be toggled on/off by user
  - Preference saved to localStorage
  - Shows for keyboard shortcuts feedback
  - Success/error/info notification types
- ✅ **Dark/Light Mode Toggle** - Already implemented:
  - Sun/Moon icon in header (top right)
  - Instant theme switching
  - Syncs with localStorage
  - Visual indicator of current mode

### Player Improvements, Theme System & Modern Footer (December 8, 2025)
- ✅ **Enhanced Audio Player Component** - Feature-rich embedded player:
  - Autoplay functionality with localStorage persistence
  - Visual album artwork with animated equalizer bars
  - Large gradient play/pause button with pulse effect
  - Volume controls with vertical slider and mute toggle
  - Now playing information (title, artist, listeners, live status)
  - Progress bar with time display for songs
  - External player link for popup window
  - Fully responsive design (tablet/mobile optimized)
  - Component available at `resources/views/components/enhanced-audio-player.blade.php`
- ✅ **Global Theme System** - Admin-controlled appearance overlays:
  - Christmas theme with snow effects, lights, and decorations
  - New Year theme with fireworks, confetti, and party effects
  - Admin panel at `/admin/theme` for theme management
  - Instant site-wide updates when theme changes
  - Performance optimized with requestAnimationFrame
  - Respects accessibility (prefers-reduced-motion)
  - Theme files in `public/themes/` directory
  - Database migration adds `site_theme` setting
- ✅ **Redesigned Footer** - Modern, compact layout:
  - Grid-based organization (Explore, Community, Connect)
  - Reduced vertical height for better space usage
  - Quick links to all major sections
  - Legal links in bottom bar
  - Fully responsive (2-column tablet, 1-column mobile)
  - Centered alignment on small screens
- ✅ **Mobile Optimizations**:
  - Enhanced player scales down on small screens
  - Footer adapts to mobile viewports
  - Theme effects optimized for performance on mobile
  - All new features tested across device sizes

### How to Use New Features
1. **Enhanced Audio Player**: Include component in any Blade view:
   ```blade
   <x-enhanced-audio-player streamUrl="{{ $streamUrl }}" />
   ```

2. **Theme Management**: Admin users can access at `/admin/theme`
   - Select theme from visual cards (None, Christmas, New Year)
   - Changes apply instantly across the site
   - Themes are JavaScript overlays, don't affect core design

3. **Footer**: Automatically displayed on all pages, no configuration needed

### Massive Backend Enhancement & Real-Time Features (December 8, 2025)
- ✅ **Universal Cache Service** - Centralized cache management with smart DRY patterns:
  - Namespace-based organization (radio/, lyrics/, games/, user/, etc.)
  - Pre-built methods for radio and lyrics features
  - Session-based guest tracking with TTL management
  - Redis support with fallback to file/database cache
- ✅ **Laravel Reverb Integration** - Real-time WebSocket support:
  - Now playing updates broadcast instantly when songs change
  - Automatic fallback to polling when WebSocket unavailable
  - NowPlayingUpdated event for efficient real-time updates
  - Reduced API calls and improved performance
- ✅ **Lyrics System** - Full lyrics integration with monetization flow:
  - Lyrics table with song_id, title, artist, source tracking
  - Guest limit system (4 songs per session)
  - Time-based unlock (10 minutes after viewing ad/requirement)
  - Unlimited lyrics for registered users
  - Genius API integration structure (configurable)
  - LyricsController with RESTful API endpoints
  - Popular lyrics and search functionality
- ✅ **Radio Server Management** - Scalable Icecast/Shoutcast CRUD system:
  - RadioServer model with full CRUD operations
  - RadioServerService for Docker container orchestration
  - Support for multiple Icecast and Shoutcast servers
  - Remote Docker host configuration via .env
  - Container lifecycle management (start, stop, restart, status)
  - Connection testing and health monitoring
  - Admin UI routes (RadioServersController)
- ✅ **Floating Background Effects** - Reusable visual component:
  - Extracted from coming-soon page for site-wide use
  - Configurable intensity (subtle, medium, full)
  - Customizable icons (music, headphones, radio, gamepad, etc.)
  - Respect for prefers-reduced-motion accessibility
  - Integrated into main layout with subtle gamer aesthetic
- ✅ **Enhanced AzuraCastService** - Smart caching and broadcasting:
  - Integrated with CacheService for consistent cache management
  - Automatic song change detection
  - WebSocket broadcasting on song changes
  - Reduced redundant API calls
- ✅ **Configuration Updates**:
  - Added REVERB_* environment variables for WebSocket config
  - Added GENIUS_API_TOKEN for lyrics integration
  - Added DOCKER_DEFAULT_HOST for container management
  - Updated services.php with genius and docker config

### API Endpoints Added
- `/api/lyrics/{songId}` - Get lyrics for a song (with guest limits)
- `/api/lyrics/unlock` - Unlock lyrics for guest after time requirement
- `/api/lyrics/status` - Get current lyrics viewing status
- `/api/lyrics/search` - Search lyrics by title/artist/content
- `/api/lyrics/popular` - Get popular lyrics

### Admin Routes Added
- `/admin/radio-servers` - Manage radio servers (index, create, edit, delete)
- `/admin/radio-servers/{id}/test` - Test server connection
- `/admin/radio-servers/{id}/start` - Start Docker container
- `/admin/radio-servers/{id}/stop` - Stop Docker container
- `/admin/radio-servers/{id}/restart` - Restart Docker container
- `/admin/radio-servers/{id}/status` - Get server status

### Homepage Improvements & Feature Additions (December 7, 2025)
- ✅ **Redesigned Now Playing Widget** - Optimized album art size (180x180px) for better balance and visual appeal
- ✅ **Event Engagement Features**:
  - Event likes system with real-time count updates
  - Event reminder subscription for authenticated users
  - Like counts displayed on homepage event cards
  - Interactive like and reminder buttons on event detail pages
- ✅ **Enhanced Game Pages**:
  - Individual detail pages for free games with comprehensive information
  - Individual detail pages for game deals with store details and Metacritic scores
  - Related games/deals suggestions on detail pages
  - Homepage game cards now link to detail pages for better user experience
- ✅ **Laravel Scout Integration**:
  - All content types (News, Events, Polls, Videos, Free Games, Game Deals) now use Scout's Searchable trait
  - Enhanced search relevance with proper indexing
  - Faster search performance across all content types
  - Each model includes toSearchableArray() method for optimized search data
- ✅ **Database Enhancements**:
  - Created event_likes table for tracking event likes
  - Created event_reminders table for managing event notification subscriptions
  - Proper foreign key constraints and indexes for optimal performance

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

# Media Hub Content Import APIs (optional)
# CurseForge API (for Minecraft mods)
CURSEFORGE_API_KEY=

# Steam API (for CS2 workshop items)
STEAM_API_KEY=

# Nexus Mods API (for Skyrim mods)
NEXUSMODS_API_KEY=

# Sentry Error Monitoring (optional)
SENTRY_LARAVEL_DSN=
SENTRY_TRACES_SAMPLE_RATE=0.1
```

**📝 Note:** For a complete list of all available environment variables with detailed explanations and examples, see [.env.example](.env.example).

### Media Content Automation

The media hub can automatically import popular mods and content from various free sources:

**Supported Sources:**
- **CurseForge** - Popular Minecraft mods (requires API key from https://docs.curseforge.com/)
- **Steam Workshop** - Trending CS2 maps and items (requires API key from https://steamcommunity.com/dev/apikey)
- **GTA5-Mods.com** - Latest GTA V mods via RSS (no API key required)
- **Nexus Mods** - Trending Skyrim mods (requires API key from https://www.nexusmods.com/users/myaccount?tab=api)

**Usage:**
```bash
# Import from all configured sources (20 items each)
php artisan media:import

# Import from specific source with custom limit
php artisan media:import --source=minecraft --limit=50

# Available sources: minecraft, cs2, gta5, skyrim, all
```

All API keys are optional. The import command will skip sources without configured keys. You can schedule this command to run periodically for automated content updates.

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
# Run all tests (unit + feature)
php artisan test

# Run only unit tests
php artisan test tests/Unit

# Run only feature tests
php artisan test tests/Feature
```

#### Integration Testing with AzuraCast

Integration tests can run against a real AzuraCast instance when API credentials are provided. See [Integration Testing Guide](docs/INTEGRATION_TESTING.md) for details.

```bash
# Set credentials for integration testing
export AZURACAST_BASE_URL=https://radio.lossantosradio.com
export AZURACAST_API_KEY=your-api-key-here
export AZURACAST_STATION_ID=1

# Run integration tests
php artisan test tests/Feature/AzuraCastIntegrationTest.php
```

**Note**: Integration tests will be skipped if `AZURACAST_API_KEY` is not set.

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
- **2024 UI/UX Overhaul** - Comprehensive design improvements for enhanced user experience:
  - **Downloads Navigation** - Added prominent "Downloads" link in main navbar for easy access to Media Hub
  - **Responsive Grid Layouts** - Fixed all grid layouts across the site with proper responsive breakpoints (mobile → tablet → desktop)
  - **Enhanced Event Cards** - Homepage upcoming events section redesigned with:
    - Gradient header backgrounds matching site theme
    - Larger date badges with gradient styling and shadows
    - Event type badges with color-coded indicators
    - Smooth hover effects with transform animations
    - Better spacing and improved typography
  - **Media Hub Redesign** - Complete visual overhaul of Downloads/Media Hub:
    - Hero section with gradient background and statistics
    - Enhanced category cards with hover animations
    - Featured content section with image support
    - Popular and recent downloads sections
    - Consistent visual language with rest of site
  - **Site-wide Consistency** - Unified responsive patterns across Events, Polls, DJs, and Radio pages
  - **Mobile-First Approach** - All layouts properly collapse to single column on mobile devices
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

## 🎨 Frontend Quality & Consistency Review (December 2025)

A comprehensive frontend audit was conducted to ensure design consistency, accessibility compliance, and code quality across all 106 frontend files.

### Review Scope
- ✅ **96 Blade Templates** - All views reviewed for consistency
- ✅ **2 CSS Files** - Main styles and lyrics modal
- ✅ **8 JavaScript Modules** - All scripts audited
- ✅ **Build System** - Vite 7 + Tailwind CSS 4 verified

### Critical Improvements Completed

#### ✅ Console Statements Eliminated (16 instances fixed)
Created conditional `logger.js` utility that only logs in development:
- Refactored `websocket-player.js` (9 console statements)
- Refactored `radio-player.js` (3 console statements)
- Refactored `lyrics-modal.js` (3 console statements)
- Refactored `search-modal.js` (1 console statement)
- **Production Impact**: Zero console output in production builds

#### ✅ Accessibility Enhanced (13 images fixed)
All images now have descriptive alt attributes for WCAG 2.1 Level A compliance:
- Fixed album artwork descriptions across radio pages
- Added descriptive alts for user avatars in admin area
- Enhanced alt text for game thumbnails and video previews
- **Impact**: Improved screen reader experience and SEO

### Design System
- **CSS Custom Properties**: Well-defined light/dark theme variables
- **Color System**: Consistent use of `--color-accent`, `--color-bg-*`, `--color-text-*`
- **Component Architecture**: Reusable Blade components with Alpine.js
- **Responsive Design**: Mobile-first approach throughout

### Build Performance
```
Build Time: 862ms (Vite 7)
Bundle Size: 118.96 kB (gzipped: 38.69 kB)
CSS Size: 49.28 kB (gzipped: 10.98 kB)
Build Status: ✅ PASSING (0 errors, 0 warnings)
```

### Review Documentation
For complete findings and technical details, see:
- **[FRONTEND_REVIEW_FINDINGS.md](./FRONTEND_REVIEW_FINDINGS.md)** - Comprehensive audit report with 6 issue categories, metrics, and action plans

### Future Enhancements
While the site is fully functional and accessible, future iterations may include:
- Gradual migration of inline styles to utility classes
- Additional component extraction for repeated patterns
- Enhanced animation performance optimizations

**Reviewed By**: GitHub Copilot AI Agent  
**Review Date**: December 8, 2025  
**Status**: Critical issues resolved, production-ready ✅

---

## 📄 License

This project is open-source.
