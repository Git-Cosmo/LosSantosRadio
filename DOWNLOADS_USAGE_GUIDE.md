# Downloads Portal - Usage Guide

## Overview

This guide covers the enhanced GameBanana-style downloads portal redesign implemented for Los Santos Radio.

## Key Features Implemented

### 1. Enhanced Index Page (`/media`)

**Hero Section with Integrated Search**
- Gradient background with diagonal pattern overlay
- Centered content with icon badge
- Quick search bar for instant content discovery
- Responsive design for all screen sizes

**Quick Stats Dashboard**
- Total downloads count
- Number of categories
- Featured content count
- Always available status (24/7)

**Filter & Sort Controls**
- View mode toggle (Grid/List) - powered by Alpine.js
- Sort dropdown: Latest, Most Downloads, Top Rated, Featured
- Real-time URL updates for sharing filtered views

**Category Cards**
- Large emoji icons for each game
- Category name and description
- Item count badges
- Hover effects with transform and shadow

**Content Sections**
- Featured Content (6 items)
- Popular Downloads (8 items)
- Recently Added (12 items)
- Each with distinctive icons and headers

**Call-to-Action**
- Authenticated users: Upload Content button
- Guest users: Login prompt with benefits

### 2. Backend Enhancements

**MediaItemSeeder**
- 120+ demo items across 8 game categories
- Realistic data generation:
  - Varied titles and descriptions
  - Version numbers (1.0.0, 2.1.0, etc.)
  - Random file sizes (MB/GB)
  - Download counts (10-5000)
  - View counts (50-10000)
  - Ratings (3.0-5.0 stars)
  - Rating counts (5-200)
- 10% of items randomly marked as featured
- Automatic user creation if needed
- Distributed across all 45+ subcategories
- Published dates spread over 365 days

**Game-Specific Content**
- Counter-Strike 2: Maps, Skins, HUD Mods, Sound Mods, Server Plugins
- Minecraft: Mods, Texture Packs, Maps, Data Packs, Skins, Server Plugins
- GTA V: Scripts, Vehicles, Maps, Weapons, Peds, Graphics Mods
- Skyrim: Gameplay Mods, Quests, Graphics, Armor, Followers, Utilities
- Cyberpunk 2077: Gameplay, Graphics, Clothing, Vehicles, Weapons, Utilities
- Starfield: Gameplay, Ships, Outposts, Graphics, UI, Weapons & Armor
- Baldur's Gate 3: Classes, Companions, Gameplay, Visuals, QoL, Equipment
- Terraria: Content Mods, QoL, Texture Packs, Tools

### 3. CSS Architecture

**Dedicated Stylesheet**
- Location: `resources/css/pages/media.css`
- Imported in `resources/css/app.css`

**Key CSS Classes**
- `.media-hero` - Hero section with gradient
- `.media-hero-content` - Centered content wrapper
- `.media-controls` - Filter/sort control bar
- `.download-card` - Reusable card component
- `.section-header` - Section title styling
- `.section-title` - Title with icon
- `.download-card-stat` - Stat display component

**Responsive Breakpoints**
- Mobile: Base styles
- Tablet: 768px
- Desktop: 1024px

**Theme Integration**
- Uses CSS custom properties from site theme
- Supports dark mode via CSS variables
- Consistent with existing site design

### 4. Interactive Features (Planned)

**media-show.js Module**
- Tab switching for content sections
- Star rating system with hover effects
- Favorite toggle functionality
- Image lightbox/gallery
- Toast notifications

## Usage Instructions

### For Developers

**Seeding the Database**

```bash
# Seed categories and subcategories
php artisan db:seed --class=MediaCategorySeeder

# Seed demo items
php artisan db:seed --class=MediaItemSeeder

# Seed everything
php artisan db:seed
```

**Reset and Reseed**

```bash
# Fresh migration and seed (WARNING: Destroys all data)
php artisan migrate:fresh --seed
```

**Building Assets**

```bash
# Install dependencies
npm install
composer install

# Build for production
npm run build

# Development with hot reload
npm run dev
```

**Code Formatting**

```bash
# Format PHP files
./vendor/bin/pint

# Format specific file
./vendor/bin/pint database/seeders/MediaItemSeeder.php
```

### For End Users

**Browsing Downloads**
1. Visit `/media` to see all available downloads
2. Use the search bar in the hero section for quick searches
3. Click on a game category to browse specific content
4. Use the sort dropdown to filter by Latest, Popular, Top Rated, or Featured
5. Toggle between Grid and List views

**Uploading Content**
1. Login to your account (OAuth required)
2. Click "Upload Content" button
3. Select game category and content type
4. Fill in title, description, and installation instructions
5. Upload your file (ZIP, RAR, 7Z, TAR, GZ - max 100MB)
6. Optionally add a preview image (max 5MB)
7. Submit for admin approval

**Downloading Content**
1. Login to your account
2. Browse or search for content
3. Click "View Details" on any item
4. Review the description and installation instructions
5. Click "Download Now" to get the file
6. Rate and favorite content you enjoy

## File Structure

```
├── resources/
│   ├── css/
│   │   ├── app.css                      # Main stylesheet with imports
│   │   └── pages/
│   │       └── media.css                # Media-specific styles
│   ├── js/
│   │   └── modules/
│   │       └── media-show.js            # Interactive features for detail page
│   └── views/
│       └── media/
│           ├── index.blade.php          # Enhanced landing page
│           ├── category.blade.php       # Category listing
│           ├── subcategory.blade.php    # Subcategory listing
│           ├── show.blade.php           # Detail page
│           ├── search.blade.php         # Search results
│           ├── upload.blade.php         # Upload form
│           └── favorites.blade.php      # User favorites
├── database/
│   └── seeders/
│       ├── MediaCategorySeeder.php      # Categories and subcategories
│       └── MediaItemSeeder.php          # Demo content items
└── app/
    ├── Models/
    │   ├── MediaCategory.php
    │   ├── MediaSubcategory.php
    │   ├── MediaItem.php
    │   ├── MediaItemRating.php
    │   ├── MediaItemFavorite.php
    │   └── MediaItemDownload.php
    └── Http/
        └── Controllers/
            ├── MediaController.php
            ├── MediaItemRatingController.php
            └── MediaItemFavoriteController.php
```

## Best Practices

### Adding New Content

**Manual Content Creation**
- Always fill in title, description, and content (installation instructions)
- Use descriptive version numbers (e.g., 1.0.0, 2.1.0)
- Upload a preview image for better visibility
- Choose the correct category and subcategory
- Test the download before submitting

**Importing from External APIs**
```bash
# Import from specific source
php artisan media:import --source=minecraft --limit=50

# Import from all sources
php artisan media:import
```

### Customization

**Changing Hero Colors**
Edit the gradient in the hero section:
```css
.media-hero {
    background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%);
}
```

**Adjusting Card Hover Effects**
Modify the transform and shadow in:
```css
.download-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
```

**Customizing Stats Dashboard**
Edit the stats section in `resources/views/media/index.blade.php`:
```html
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Add or modify stat cards -->
</div>
```

## Troubleshooting

**Seeder fails with "No categories found"**
- Run `MediaCategorySeeder` before `MediaItemSeeder`
- Check that migrations have been run

**Frontend styles not loading**
- Run `npm run build` to compile assets
- Check that `@vite` directive is in layout file
- Clear browser cache

**Images not displaying**
- Ensure Spatie Media Library is configured
- Check file permissions on storage directory
- Verify image upload size limits

**Search not working**
- Configure Laravel Scout
- Run `php artisan scout:import "App\Models\MediaItem"`
- Check search driver configuration

## Future Enhancements

### Planned Features

**Detail Page Improvements**
- Image gallery/carousel for screenshots
- Tabbed interface (Description, Installation, Changelog, Comments)
- Version history display
- Next/Previous navigation
- Report button
- Subscribe to updates

**Category Page Enhancements**
- Advanced filtering sidebar
- Date range filters
- Rating filters
- Download count filters

**Interactive Features**
- Hover previews
- Quick view modals
- Drag-and-drop upload
- Bulk actions

**Performance Optimizations**
- Lazy loading images
- Infinite scroll
- Progressive Web App support
- Image optimization

## Support

For issues, questions, or feature requests:
- Open an issue on GitHub
- Check existing documentation in README.md
- Review the code comments in seeder files

## Credits

- Design inspiration: GameBanana
- Framework: Laravel 12
- Styling: Tailwind CSS 4
- Interactions: Alpine.js
- File management: Spatie Media Library
