# Deployment Notes

## Frontend Assets

Los Santos Radio uses **Vite** to compile CSS and JavaScript assets. The `public/build/` directory is intentionally excluded from version control (`.gitignore`) as these are generated files.

### After Cloning or Pulling Changes

**Always run these commands** after cloning the repository or pulling changes that affect frontend code:

```bash
# Install Node.js dependencies
npm install

# Build frontend assets for production
npm run build
```

This will generate:
- `public/build/assets/app-*.css` (~145KB, 26KB gzipped)
- `public/build/assets/app-*.js` (~122KB, 40KB gzipped)
- `public/build/manifest.json` (asset manifest)

### Development Workflow

For local development with hot module replacement:

```bash
# Start Vite dev server with hot reload
npm run dev

# In another terminal, start Laravel
php artisan serve
```

### Production Deployment

Your CI/CD pipeline or deployment script should include:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Troubleshooting

**Issue**: Website loads with no styling (white page, black text)

**Cause**: Frontend assets haven't been built

**Solution**: 
```bash
npm install
npm run build
```

**Verification**: Check that `public/build/assets/` contains CSS and JS files:
```bash
ls -lh public/build/assets/
```

You should see files like:
- `app-[hash].css`
- `app-[hash].js`

## Asset Pipeline Architecture

- **Source Files**: `resources/css/` and `resources/js/`
- **Build Config**: `vite.config.js`
- **Output**: `public/build/`
- **Loading**: `@vite(['resources/css/app.css', 'resources/js/app.js'])` in `resources/views/layouts/app.blade.php`

## CSS Organization

All CSS has been extracted from Blade templates into modular files:

- **Core**: `layout.css`, `radio-player.css`, `audio-player.css`, `home.css`
- **Features**: `games.css`, `songs.css`, `news-index.css`, `polls-show.css`, etc.
- **Components**: `quick-stats.css`, `floating-bg.css`
- **Admin**: `admin-layout.css`, `admin-auth.css`, `admin-theme.css`
- **Errors**: `error-404.css`, `error-503.css`, etc.

All modules are imported via `resources/css/app.css` and compiled by Vite.

## JavaScript Modules

JavaScript is organized in `resources/js/modules/`:

- `radio-player.js` - Radio player controls
- `websocket-player.js` - Real-time updates via Laravel Reverb
- `lyrics-modal.js` - Lyrics functionality
- `toast-notifications.js` - Toast system
- `search-modal.js` - Global search
- `keyboard-shortcuts.js` - Keyboard navigation
- `ui-helpers.js` - Common utilities

All modules are imported via `resources/js/app.js` and bundled by Vite.
