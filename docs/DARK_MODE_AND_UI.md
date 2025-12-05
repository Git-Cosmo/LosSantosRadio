# Dark Mode & UI Documentation

This document covers the dark mode implementation, live clock feature, and package integrations for Los Santos Radio.

## Dark Mode Implementation

### Overview

The application uses a **dark-first** approach where dark mode is the default and persistent theme. Users can toggle to light mode, but the application defaults to dark mode for new visitors.

### How It Works

Dark mode is implemented using:
- **CSS Custom Properties (Variables)** for theme colors
- **Alpine.js** for reactive theme toggling
- **localStorage** for persistence across sessions

### CSS Variables

The theme colors are defined in `resources/views/layouts/app.blade.php`:

```css
/* Light Theme Colors */
:root {
    --color-bg-primary: #ffffff;
    --color-bg-secondary: #f6f8fa;
    --color-bg-tertiary: #eaeef2;
    --color-bg-hover: #d0d7de;
    --color-border: #d0d7de;
    --color-text-primary: #1f2328;
    --color-text-secondary: #656d76;
    --color-text-muted: #8c959f;
    --color-accent: #0969da;
    --color-accent-hover: #0550ae;
    --color-success: #1a7f37;
    --color-warning: #9a6700;
    --color-danger: #cf222e;
}

/* Dark Theme Colors */
html.dark {
    --color-bg-primary: #0d1117;
    --color-bg-secondary: #161b22;
    --color-bg-tertiary: #21262d;
    --color-bg-hover: #30363d;
    --color-border: #30363d;
    --color-text-primary: #e6edf3;
    --color-text-secondary: #8b949e;
    --color-text-muted: #6e7681;
    --color-accent: #58a6ff;
    --color-accent-hover: #79c0ff;
    --color-success: #3fb950;
    --color-warning: #d29922;
    --color-danger: #f85149;
}
```

### Alpine.js Integration

The theme state is managed via Alpine.js on the `<html>` element:

```html
<html x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" 
      :class="{ 'dark': darkMode }" 
      class="dark">
```

### Theme Toggle Button

Users can toggle themes using the button in the navigation bar:

```html
<button @click="darkMode = !darkMode" 
        class="btn btn-secondary theme-toggle" 
        :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
</button>
```

### Adding Dark Mode to New Components

When creating new UI components, use the CSS custom properties:

```css
.my-component {
    background-color: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    color: var(--color-text-primary);
}

.my-component:hover {
    background-color: var(--color-bg-hover);
    border-color: var(--color-accent);
}
```

---

## Live Clock Feature

### Overview

A real-time updating clock is displayed in the navigation bar, featuring:
- Updates every second
- Toggleable 12/24 hour format
- Persistent format preference via localStorage

### Implementation

The clock uses Alpine.js for reactive updates:

```javascript
function liveClock() {
    return {
        time: '',
        interval: null,
        init() {
            this.updateTime();
            this.interval = setInterval(() => this.updateTime(), 1000);
        },
        updateTime() {
            const now = new Date();
            const format = localStorage.getItem('clockFormat') || '24';
            
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            
            if (format === '12') {
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                this.time = `${hours}:${minutes}:${seconds} ${ampm}`;
            } else {
                this.time = `${hours.toString().padStart(2, '0')}:${minutes}:${seconds}`;
            }
        },
        destroy() {
            if (this.interval) clearInterval(this.interval);
        }
    };
}
```

### Usage

The clock element:

```html
<div class="live-clock" 
     @click="clockFormat = clockFormat === '24' ? '12' : '24'" 
     title="Click to toggle 12/24 hour format" 
     x-data="liveClock()" 
     x-init="init()">
    <i class="fas fa-clock"></i>
    <span class="live-clock-time" x-text="time"></span>
    <span class="live-clock-format" x-text="clockFormat === '24' ? '24H' : '12H'"></span>
</div>
```

---

## Package Integrations

### spatie/laravel-permission

**Purpose:** Role and permission management for users.

**Configuration:** `config/permission.php`

**Usage:**

```php
// Check if user has a role
if ($user->hasRole('admin')) { ... }

// Check if user has permission
if ($user->can('edit articles')) { ... }

// Assign role
$user->assignRole('admin');

// Give permission
$user->givePermissionTo('edit articles');
```

**Seeded Roles:**
- `admin` - Full administrative access
- `staff` - Station staff access
- `listener` - Default role for registered users

### spatie/laravel-sluggable

**Purpose:** Automatic slug generation for models.

**Usage in Model:**

```php
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class News extends Model
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
```

### spatie/laravel-medialibrary

**Purpose:** Media uploads and conversions.

**Configuration:** `config/media-library.php`

**Usage:**

```php
// In Model
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class News extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile()
            ->useFallbackUrl('/images/default-news.png');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300);
    }
}

// Adding media
$news->addMedia($file)->toMediaCollection('featured');

// Getting media
$news->getFirstMediaUrl('featured', 'thumb');
```

### spatie/laravel-activitylog

**Purpose:** Track model changes and user activity.

**Configuration:** `config/activitylog.php`

**Usage:**

```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty();
    }
}

// Manual logging
activity()
    ->causedBy($user)
    ->performedOn($article)
    ->log('Article published');

// Viewing logs
Activity::forSubject($user)->get();
```

### spatie/laravel-tags

**Purpose:** Tagging functionality for models.

**Usage:**

```php
use Spatie\Tags\HasTags;

class News extends Model
{
    use HasTags;
}

// Adding tags
$news->attachTags(['breaking', 'music']);

// Getting items with tag
News::withAnyTags(['breaking'])->get();
```

---

## Accessibility Features

### Reduced Motion

Animations respect user preferences:

```css
@media (prefers-reduced-motion: reduce) {
    .hero-logo,
    .equalizer-bar,
    .progress-fill::after {
        animation: none;
    }
}
```

### Focus States

All interactive elements have visible focus indicators:

```css
.btn:focus,
.nav-link:focus {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
}
```

### Screen Reader Support

Hidden labels for screen readers:

```css
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}
```
