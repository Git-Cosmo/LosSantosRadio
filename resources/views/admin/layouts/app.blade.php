<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') !== 'light', clockFormat: localStorage.getItem('clockFormat') || '24' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light')); $watch('clockFormat', val => localStorage.setItem('clockFormat', val))" :class="{ 'dark': darkMode }" class="dark">
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

    <style>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--color-bg-primary);
            color: var(--color-text-primary);
            line-height: 1.5;
            min-height: 100vh;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--color-bg-secondary);
            border-right: 1px solid var(--color-border);
            padding: 1rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid var(--color-border);
            margin-bottom: 1rem;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--color-text-primary);
            font-weight: 600;
            font-size: 1.125rem;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--color-accent), #7c3aed);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .nav-group {
            margin-bottom: 1.5rem;
        }

        .nav-group-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            color: var(--color-text-secondary);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background-color: var(--color-bg-tertiary);
            color: var(--color-text-primary);
        }

        .nav-link.active {
            background-color: var(--color-accent);
            color: white;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
        }

        /* Header */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-border);
        }

        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Cards */
        .card {
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--color-border);
        }

        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--color-text-muted);
            background-color: var(--color-bg-tertiary);
        }

        .table tbody tr:hover {
            background-color: var(--color-bg-hover);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--color-accent);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--color-accent-hover);
        }

        .btn-secondary {
            background-color: var(--color-bg-tertiary);
            border-color: var(--color-border);
            color: var(--color-text-primary);
        }

        .btn-secondary:hover {
            background-color: var(--color-bg-hover);
        }

        .btn-danger {
            background-color: var(--color-danger);
            color: white;
        }

        .btn-success {
            background-color: var(--color-success);
            color: white;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-primary);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            background-color: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: 6px;
            color: var(--color-text-primary);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.2);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 9999px;
        }

        .badge-success {
            background-color: rgba(63, 185, 80, 0.2);
            color: var(--color-success);
        }

        .badge-warning {
            background-color: rgba(210, 153, 34, 0.2);
            color: var(--color-warning);
        }

        .badge-danger {
            background-color: rgba(248, 81, 73, 0.2);
            color: var(--color-danger);
        }

        .badge-primary {
            background-color: rgba(88, 166, 255, 0.2);
            color: var(--color-accent);
        }

        .badge-gray {
            background-color: var(--color-bg-tertiary);
            color: var(--color-text-secondary);
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 1.25rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: rgba(63, 185, 80, 0.1);
            border: 1px solid rgba(63, 185, 80, 0.3);
            color: var(--color-success);
        }

        .alert-error {
            background-color: rgba(248, 81, 73, 0.1);
            border: 1px solid rgba(248, 81, 73, 0.3);
            color: var(--color-danger);
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.25rem;
            margin-top: 1rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--color-border);
            border-radius: 4px;
            text-decoration: none;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
        }

        .pagination a:hover {
            background-color: var(--color-bg-tertiary);
        }

        .pagination .active span {
            background-color: var(--color-accent);
            color: white;
            border-color: var(--color-accent);
        }

        /* Avatar */
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-sm {
            width: 24px;
            height: 24px;
        }

        /* Filters */
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }

            .grid-cols-2,
            .grid-cols-3 {
                grid-template-columns: 1fr;
            }
        }

        /* Live Clock */
        .live-clock {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            background-color: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 6px;
            color: var(--color-text-primary);
            font-size: 0.875rem;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .live-clock:hover {
            background-color: var(--color-bg-hover);
            border-color: var(--color-accent);
        }

        .live-clock i {
            color: var(--color-accent);
        }

        .live-clock-format {
            font-size: 0.625rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            padding: 0.5rem;
            min-width: 38px;
        }

        .theme-toggle:hover {
            transform: rotate(15deg);
        }
    </style>
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
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">User Management</div>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Users
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
                <div class="live-clock" @click="clockFormat = clockFormat === '24' ? '12' : '24'" title="Click to toggle 12/24 hour format" x-data="liveClock()" x-init="init()">
                    <i class="fas fa-clock"></i>
                    <span x-text="time"></span>
                    <span class="live-clock-format" x-text="clockFormat === '24' ? '24H' : '12H'"></span>
                </div>
                
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode" class="btn btn-secondary theme-toggle" :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
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
                init() {
                    this.updateTime();
                    this.interval = setInterval(() => this.updateTime(), 1000);
                },
                updateTime() {
                    const now = new Date();
                    const format = this.$root.querySelector('[x-data]')?._x_dataStack?.[0]?.clockFormat || localStorage.getItem('clockFormat') || '24';
                    
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
    </script>
</body>
</html>
