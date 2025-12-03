<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Los Santos Radio' }} - Los Santos Radio</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            /* Copilot Dark Theme Colors */
            --color-bg-primary: #0d1117;
            --color-bg-secondary: #161b22;
            --color-bg-tertiary: #21262d;
            --color-bg-hover: #30363d;
            --color-border: #30363d;
            --color-border-light: #21262d;

            --color-text-primary: #e6edf3;
            --color-text-secondary: #8b949e;
            --color-text-muted: #6e7681;

            --color-accent: #58a6ff;
            --color-accent-hover: #79c0ff;
            --color-success: #3fb950;
            --color-warning: #d29922;
            --color-danger: #f85149;

            /* Provider Colors */
            --color-discord: #5865F2;
            --color-twitch: #9146FF;
            --color-steam: #1b2838;
            --color-battlenet: #00AEFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
            background-color: var(--color-bg-primary);
            color: var(--color-text-primary);
            line-height: 1.5;
            min-height: 100vh;
        }

        a {
            color: var(--color-accent);
            text-decoration: none;
        }

        a:hover {
            color: var(--color-accent-hover);
            text-decoration: underline;
        }

        /* Header */
        .header {
            background-color: var(--color-bg-secondary);
            border-bottom: 1px solid var(--color-border);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--color-text-primary);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .logo:hover {
            text-decoration: none;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-link {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .nav-link:hover {
            color: var(--color-text-primary);
            text-decoration: none;
        }

        .nav-link.active {
            color: var(--color-text-primary);
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
            transition: all 0.15s ease;
        }

        .btn-primary {
            background-color: var(--color-accent);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--color-accent-hover);
            text-decoration: none;
            color: white;
        }

        .btn-secondary {
            background-color: var(--color-bg-tertiary);
            border-color: var(--color-border);
            color: var(--color-text-primary);
        }

        .btn-secondary:hover {
            background-color: var(--color-bg-hover);
            text-decoration: none;
        }

        .btn-discord {
            background-color: var(--color-discord);
            color: white;
        }

        .btn-twitch {
            background-color: var(--color-twitch);
            color: white;
        }

        .btn-steam {
            background-color: var(--color-steam);
            color: white;
            border-color: #2a475e;
        }

        .btn-battlenet {
            background-color: var(--color-battlenet);
            color: white;
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
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        /* Now Playing */
        .now-playing {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .now-playing-art {
            width: 200px;
            height: 200px;
            border-radius: 8px;
            background-color: var(--color-bg-tertiary);
            flex-shrink: 0;
            object-fit: cover;
        }

        .now-playing-info {
            flex: 1;
        }

        .now-playing-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .now-playing-artist {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            margin-bottom: 0.5rem;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: var(--color-bg-tertiary);
            border-radius: 2px;
            margin: 1rem 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: var(--color-accent);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .time-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        /* Song History */
        .history-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border-radius: 6px;
            transition: background-color 0.15s ease;
        }

        .history-item:hover {
            background-color: var(--color-bg-hover);
        }

        .history-art {
            width: 48px;
            height: 48px;
            border-radius: 4px;
            background-color: var(--color-bg-tertiary);
            object-fit: cover;
            flex-shrink: 0;
        }

        .history-info {
            flex: 1;
            min-width: 0;
        }

        .history-title {
            font-size: 0.875rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-artist {
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }

        .history-time {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        /* Grid Layouts */
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
            .grid-cols-2, .grid-cols-3 {
                grid-template-columns: 1fr;
            }

            .now-playing {
                flex-direction: column;
                text-align: center;
            }

            .now-playing-art {
                width: 150px;
                height: 150px;
            }
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

        .badge-live {
            background-color: var(--color-danger);
            color: white;
            animation: pulse 2s infinite;
        }

        .badge-success {
            background-color: rgba(63, 185, 80, 0.2);
            color: var(--color-success);
        }

        .badge-warning {
            background-color: rgba(210, 153, 34, 0.2);
            color: var(--color-warning);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Listeners */
        .listeners-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .alert-error {
            background-color: rgba(248, 81, 73, 0.1);
            border: 1px solid rgba(248, 81, 73, 0.3);
            color: var(--color-danger);
        }

        .alert-success {
            background-color: rgba(63, 185, 80, 0.1);
            border: 1px solid rgba(63, 185, 80, 0.3);
            color: var(--color-success);
        }

        /* Forms */
        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            background-color: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: 6px;
            color: var(--color-text-primary);
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.15);
        }

        .form-input::placeholder {
            color: var(--color-text-muted);
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--color-bg-tertiary);
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--color-border);
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-radio" style="color: white;"></i>
                </div>
                <span>Los Santos Radio</span>
            </a>

            <nav class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="{{ route('requests.index') }}" class="nav-link {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                    <i class="fas fa-music"></i> Request a Song
                </a>
                @auth
                    <a href="{{ route('requests.history') }}" class="nav-link">
                        <i class="fas fa-history"></i> My Requests
                    </a>
                @endauth
            </nav>

            <div class="user-menu">
                @auth
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                    <span style="color: var(--color-text-secondary);">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="main-content">
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

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Los Santos Radio. Powered by AzuraCast.</p>
    </footer>

    <script>
        // CSRF token for AJAX requests
        window.csrfToken = '{{ csrf_token() }}';

        // Auto-refresh now playing
        function updateNowPlaying() {
            fetch('/api/radio/now-playing')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const event = new CustomEvent('nowPlayingUpdate', { detail: data.data });
                        document.dispatchEvent(event);
                    }
                })
                .catch(console.error);
        }

        // Refresh every 10 seconds
        setInterval(updateNowPlaying, 10000);
    </script>

    @stack('scripts')
</body>
</html>
