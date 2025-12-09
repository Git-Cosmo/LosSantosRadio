<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel' }} - Los Santos Radio Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-radio"></i>
                    </div>
                    <span>LSR Admin</span>
                </a>
            </div>

            <nav>
                <div class="nav-group">
                    <div class="nav-group-title">Main</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Radio</div>
                    <a href="{{ route('admin.radio.index') }}" class="nav-link {{ request()->routeIs('admin.radio.*') ? 'active' : '' }}">
                        <i class="fas fa-broadcast-tower"></i> Radio Server
                    </a>
                    <a href="{{ route('admin.requests.index') }}" class="nav-link {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                        <i class="fas fa-music"></i> Song Requests
                    </a>
                    <a href="{{ route('admin.djs.index') }}" class="nav-link {{ request()->routeIs('admin.djs.*') ? 'active' : '' }}">
                        <i class="fas fa-headphones"></i> DJ Profiles
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Content</div>
                    <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i> News
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Events
                    </a>
                    <a href="{{ route('admin.polls.index') }}" class="nav-link {{ request()->routeIs('admin.polls.*') ? 'active' : '' }}">
                        <i class="fas fa-poll"></i> Polls
                    </a>
                    <a href="{{ route('admin.games.index') }}" class="nav-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                        <i class="fas fa-gamepad"></i> Games
                    </a>
                    <a href="{{ route('admin.videos.index') }}" class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                        <i class="fas fa-video"></i> Videos
                    </a>
                    <a href="{{ route('admin.media.index') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <i class="fas fa-photo-video"></i> Media Library
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">User Management</div>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Integrations</div>
                    <a href="{{ route('admin.discord.index') }}" class="nav-link {{ request()->routeIs('admin.discord.*') ? 'active' : '' }}">
                        <i class="fab fa-discord"></i> Discord Bot
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Configuration</div>
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="{{ route('admin.activity.index') }}" class="nav-link {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Activity Log
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Account</div>
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Admin Header Bar -->
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-border);">
                <!-- Live Clock -->
                <div class="live-clock" @click="toggleFormat()" title="Click to toggle 12/24 hour format" x-data="liveClock()" x-init="init()">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <span x-text="time"></span>
                    <span class="live-clock-format" x-text="getFormatLabel()"></span>
                </div>
                
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" class="btn btn-secondary theme-toggle" :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <script>
        // Live Clock Alpine.js component
        function liveClock() {
            return {
                time: '',
                interval: null,
                format: localStorage.getItem('clockFormat') || '24',
                init() {
                    this.updateTime();
                    this.interval = setInterval(() => this.updateTime(), 1000);
                    // Listen for clock format changes via storage event (for cross-tab sync)
                    window.addEventListener('storage', (e) => {
                        if (e.key === 'clockFormat') {
                            this.format = e.newValue || '24';
                            this.updateTime();
                        }
                    });
                },
                toggleFormat() {
                    this.format = this.format === '24' ? '12' : '24';
                    localStorage.setItem('clockFormat', this.format);
                    this.updateTime();
                },
                updateTime() {
                    const now = new Date();
                    
                    let hours = now.getHours();
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    
                    if (this.format === '12') {
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;
                        this.time = `${hours}:${minutes}:${seconds} ${ampm}`;
                    } else {
                        this.time = `${hours.toString().padStart(2, '0')}:${minutes}:${seconds}`;
                    }
                },
                getFormatLabel() {
                    return this.format === '24' ? '24H' : '12H';
                }
            };
        }
    </script>
</body>
</html>
