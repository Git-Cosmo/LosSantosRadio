<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO: Basic Meta Tags --}}
    <title>{{ isset($title) ? $title . ' - Los Santos Radio' : 'Los Santos Radio - 24/7 Online Radio & Gaming Community' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Los Santos Radio - Your 24/7 online radio station. Listen live, request songs, join our gaming community, and connect with listeners worldwide.' }}">
    <meta name="keywords" content="Los Santos Radio, online radio, music streaming, song requests, gaming community, live DJ, Discord, radio station, internet radio, 24/7 music">
    <meta name="author" content="Los Santos Radio">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="theme-color" content="#0d1117">
    <meta name="msapplication-TileColor" content="#0d1117">

    {{-- SEO: Canonical URL --}}
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">

    {{-- SEO: Open Graph Meta Tags for Social Sharing --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:site_name" content="Los Santos Radio">
    <meta property="og:title" content="{{ isset($title) ? $title . ' - Los Santos Radio' : 'Los Santos Radio - 24/7 Online Radio & Gaming Community' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Los Santos Radio - Your 24/7 online radio station. Listen live, request songs, and connect with the gaming community.' }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/icons/icon-512x512.png') }}">
    @if(isset($ogImageWidth) && isset($ogImageHeight))
    <meta property="og:image:width" content="{{ $ogImageWidth }}">
    <meta property="og:image:height" content="{{ $ogImageHeight }}">
    @endif
    <meta property="og:image:alt" content="{{ $ogImageAlt ?? 'Los Santos Radio Logo' }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    {{-- SEO: Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ isset($title) ? $title . ' - Los Santos Radio' : 'Los Santos Radio - 24/7 Online Radio & Gaming Community' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Los Santos Radio - Your 24/7 online radio station. Listen live, request songs, and connect with the gaming community.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/icons/icon-512x512.png') }}">
    <meta name="twitter:image:alt" content="{{ $ogImageAlt ?? 'Los Santos Radio Logo' }}">

    {{-- SEO: Structured Data (JSON-LD) - RadioStation --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'RadioStation',
        '@id' => config('app.url') . '/#radiostation',
        'name' => 'Los Santos Radio',
        'alternateName' => 'LSR',
        'description' => $metaDescription ?? 'Los Santos Radio - Your 24/7 online radio station featuring music streaming, song requests, and an active gaming community.',
        'url' => config('app.url'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/icons/icon-512x512.png'),
            'width' => 512,
            'height' => 512
        ],
        'image' => asset('images/icons/icon-512x512.png'),
        'broadcaster' => [
            '@type' => 'Organization',
            'name' => 'Los Santos Radio',
            'url' => config('app.url')
        ],
        'areaServed' => [
            '@type' => 'Place',
            'name' => 'Worldwide'
        ],
        'potentialAction' => [
            '@type' => 'ListenAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => config('app.url'),
                'actionPlatform' => [
                    'http://schema.org/DesktopWebPlatform',
                    'http://schema.org/MobileWebPlatform'
                ]
            ],
            'expectsAcceptanceOf' => [
                '@type' => 'Offer',
                'price' => 0,
                'priceCurrency' => 'USD',
                'eligibleRegion' => [
                    '@type' => 'Place',
                    'name' => 'Worldwide'
                ]
            ]
        ],
        'sameAs' => array_filter([
            config('services.discord.invite_url'),
        ])
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- SEO: Structured Data (JSON-LD) - WebSite with SearchAction --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => config('app.url') . '/#website',
        'name' => 'Los Santos Radio',
        'url' => config('app.url'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => config('app.url') . '/search?q={search_term_string}'
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- SEO: Structured Data (JSON-LD) - BreadcrumbList --}}
    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbs)->map(function($item, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null
            ];
        })->values()->toArray()
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    {{-- Additional page-specific structured data --}}
    @if(isset($structuredData))
    <script type="application/ld+json">
    {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    {{-- Additional head content from child views --}}
    @stack('head')

    <!-- PWA Meta Tags -->
    @laravelPWA

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Alpine.js for theme toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles -->
    <style>
        /* Light Theme Colors (default) */
        :root {
            --color-bg-primary: #ffffff;
            --color-bg-secondary: #f6f8fa;
            --color-bg-tertiary: #eaeef2;
            --color-bg-hover: #d0d7de;
            --color-border: #d0d7de;
            --color-border-light: #eaeef2;

            --color-text-primary: #1f2328;
            --color-text-secondary: #656d76;
            --color-text-muted: #8c959f;

            --color-accent: #0969da;
            --color-accent-rgb: 9 105 218;
            --color-accent-hover: #0550ae;
            --color-success: #1a7f37;
            --color-warning: #9a6700;
            --color-danger: #cf222e;

            /* Provider Colors */
            --color-discord: #5865F2;
            --color-twitch: #9146FF;
            --color-steam: #1b2838;
            --color-battlenet: #00AEFF;
        }

        /* Dark Theme Colors */
        html.dark {
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
            --color-accent-rgb: 88 166 255;
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
            background-color: var(--color-bg-primary);
            color: var(--color-text-primary);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        /* Shine effect only on primary CTA buttons */
        .btn-primary::before,
        .btn-discord::before,
        .btn-twitch::before,
        .btn-battlenet::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before,
        .btn-discord:hover::before,
        .btn-twitch:hover::before,
        .btn-battlenet:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-accent), #7c3aed);
            color: white;
            box-shadow: 0 4px 15px rgba(88, 166, 255, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--color-accent-hover), #8b5cf6);
            text-decoration: none;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(88, 166, 255, 0.4);
        }

        .btn-secondary {
            background-color: var(--color-bg-tertiary);
            border-color: var(--color-border);
            color: var(--color-text-primary);
        }

        .btn-secondary:hover {
            background-color: var(--color-bg-hover);
            text-decoration: none;
            transform: translateY(-2px);
            border-color: var(--color-accent);
        }

        .btn-discord {
            background: linear-gradient(135deg, var(--color-discord), #4752c4);
            color: white;
            box-shadow: 0 4px 15px rgba(88, 101, 242, 0.3);
        }

        .btn-discord:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(88, 101, 242, 0.4);
        }

        .btn-twitch {
            background: linear-gradient(135deg, var(--color-twitch), #772ce8);
            color: white;
            box-shadow: 0 4px 15px rgba(145, 70, 255, 0.3);
        }

        .btn-steam {
            background: linear-gradient(135deg, var(--color-steam), #2a475e);
            color: white;
            border-color: #2a475e;
        }

        .btn-battlenet {
            background: linear-gradient(135deg, var(--color-battlenet), #0088cc);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 174, 255, 0.3);
        }

        /* Cards */
        .card {
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            border-color: var(--color-accent);
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
            flex: 1 0 auto;
            width: 100%;
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
            border-radius: 12px;
            background-color: var(--color-bg-tertiary);
            flex-shrink: 0;
            object-fit: cover;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .now-playing-art:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 15px 50px rgba(88, 166, 255, 0.3);
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
            height: 6px;
            background-color: var(--color-bg-tertiary);
            border-radius: 3px;
            margin: 1rem 0;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent), #a855f7);
            border-radius: 3px;
            transition: width 0.3s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: progressShimmer 2s ease-in-out infinite;
        }

        @keyframes progressShimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
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
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .history-item:hover {
            background-color: var(--color-bg-hover);
            transform: translateX(5px);
            border-color: var(--color-border);
        }

        .history-art {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            background-color: var(--color-bg-tertiary);
            object-fit: cover;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .history-item:hover .history-art {
            transform: scale(1.1);
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

        /* Theme Toggle */
        .theme-toggle {
            padding: 0.5rem;
            min-width: 38px;
            transition: transform 0.3s ease, background-color 0.2s ease;
        }

        .theme-toggle:hover {
            transform: rotate(15deg);
        }

        .theme-toggle i {
            transition: transform 0.3s ease;
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
            min-width: 100px;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .live-clock:hover {
            background-color: var(--color-bg-hover);
            border-color: var(--color-accent);
        }

        .live-clock i {
            color: var(--color-accent);
            font-size: 0.875rem;
        }

        .live-clock-time {
            letter-spacing: 0.05em;
        }

        .live-clock-format {
            font-size: 0.625rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .live-clock {
                display: none;
            }
            .live-clock-mobile {
                display: flex !important;
                margin: 0.5rem 0;
                justify-content: center;
            }
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
            margin-top: auto;
            text-align: center;
            color: var(--color-text-muted);
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-tertiary) 100%);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(88, 166, 255, 0.1) 0%, transparent 50%);
            animation: heroGlow 8s ease-in-out infinite;
        }

        @keyframes heroGlow {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.5; }
            25% { transform: translate(10%, 10%) scale(1.1); opacity: 0.7; }
            50% { transform: translate(5%, -5%) scale(1); opacity: 0.6; }
            75% { transform: translate(-10%, 5%) scale(1.05); opacity: 0.8; }
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-brand {
            margin-bottom: 1.5rem;
        }

        .hero-logo {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            color: white;
            animation: logoFloat 3s ease-in-out infinite;
            box-shadow: 0 10px 40px rgba(88, 166, 255, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hero-logo:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 50px rgba(88, 166, 255, 0.4);
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--color-accent), #a855f7, #ec4899);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            animation: gradientShift 4s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero-tagline {
            color: var(--color-text-secondary);
            font-size: 1.125rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .hero-status {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        }

        .hero-listeners {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Audio Equalizer Animation */
        .equalizer {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 3px;
            height: 24px;
            margin: 0 0.5rem;
        }

        .equalizer-bar {
            width: 4px;
            background: linear-gradient(to top, var(--color-accent), #a855f7);
            border-radius: 2px;
            animation: equalizerBounce 0.5s ease-in-out infinite alternate;
        }

        .equalizer-bar:nth-child(1) { animation-delay: 0s; height: 8px; }
        .equalizer-bar:nth-child(2) { animation-delay: 0.1s; height: 16px; }
        .equalizer-bar:nth-child(3) { animation-delay: 0.2s; height: 12px; }
        .equalizer-bar:nth-child(4) { animation-delay: 0.3s; height: 20px; }
        .equalizer-bar:nth-child(5) { animation-delay: 0.4s; height: 10px; }

        @keyframes equalizerBounce {
            0% { transform: scaleY(0.3); }
            100% { transform: scaleY(1); }
        }

        .equalizer.paused .equalizer-bar {
            animation-play-state: paused;
        }

        /* Song Rating */
        .song-rating {
            display: flex;
            gap: 0.75rem;
            margin: 1rem 0;
        }

        .rating-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 6px;
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-btn:hover {
            background-color: var(--color-bg-hover);
            color: var(--color-text-primary);
            transform: scale(1.05);
        }

        .rating-btn.upvote:hover, .rating-btn.upvote.active {
            background-color: rgba(63, 185, 80, 0.2);
            border-color: var(--color-success);
            color: var(--color-success);
        }

        .rating-btn.downvote:hover, .rating-btn.downvote.active {
            background-color: rgba(248, 81, 73, 0.2);
            border-color: var(--color-danger);
            color: var(--color-danger);
        }

        /* Schedule */
        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background-color: var(--color-bg-tertiary);
            border-radius: 8px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .schedule-item:hover {
            transform: translateX(5px);
            background-color: var(--color-bg-hover);
        }

        .schedule-item.active {
            border-color: var(--color-accent);
            background-color: rgba(88, 166, 255, 0.1);
            animation: activeGlow 2s ease-in-out infinite;
        }

        @keyframes activeGlow {
            0%, 100% { box-shadow: 0 0 5px rgba(88, 166, 255, 0.2); }
            50% { box-shadow: 0 0 15px rgba(88, 166, 255, 0.4); }
        }

        .schedule-time {
            min-width: 50px;
            text-align: center;
        }

        .schedule-hour {
            font-weight: 600;
            color: var(--color-accent);
        }

        .schedule-info {
            flex: 1;
        }

        .schedule-title {
            font-weight: 500;
            margin-bottom: 0.125rem;
        }

        .schedule-desc {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
        }

        /* Trending Songs */
        .trending-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .trending-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .trending-item:hover {
            background-color: var(--color-bg-hover);
            transform: translateX(5px);
            border-color: var(--color-border);
        }

        .trending-rank {
            font-weight: 700;
            color: var(--color-accent);
            min-width: 30px;
            font-size: 1.125rem;
        }

        .trending-item:first-child .trending-rank {
            color: #fbbf24;
            text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
        }

        .trending-item:nth-child(2) .trending-rank {
            color: #9ca3af;
        }

        .trending-item:nth-child(3) .trending-rank {
            color: #cd7f32;
        }

        .trending-info {
            flex: 1;
            min-width: 0;
        }

        .trending-title {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .trending-artist {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
        }

        .trending-score {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-weight: 500;
        }

        /* DJ Profiles */
        .dj-profiles {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dj-profile {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background-color: var(--color-bg-tertiary);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .dj-profile:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .dj-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            animation: avatarPulse 3s ease-in-out infinite;
            color: white;
            flex-shrink: 0;
        }

        @keyframes avatarPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(88, 166, 255, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(88, 166, 255, 0); }
        }

        .dj-info {
            flex: 1;
        }

        .dj-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .dj-bio {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
        }

        /* News */
        .news-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .news-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background-color: var(--color-bg-tertiary);
            border-radius: 8px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .news-item:hover {
            transform: translateX(5px);
            border-left-color: var(--color-accent);
            background-color: var(--color-bg-hover);
        }

        .news-date {
            font-size: 1.25rem;
            min-width: 30px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .news-item:hover .news-date {
            transform: scale(1.2);
        }

        .news-content {
            flex: 1;
        }

        .news-title {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .news-desc {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }

        /* Discord Widget */
        .discord-widget {
            text-align: center;
        }

        /* Header Animation */
        @media (min-width: 769px) {
            .header {
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
        }

        .logo-icon {
            transition: transform 0.3s ease;
        }

        .logo:hover .logo-icon {
            transform: rotate(15deg) scale(1.1);
        }

        .nav-link {
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--color-accent), #a855f7);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Navigation Dropdowns */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .nav-dropdown-toggle::after {
            display: none;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 180px;
            background-color: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 0.5rem;
            z-index: 100;
            margin-top: 0.5rem;
        }

        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            color: var(--color-text-secondary);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .nav-dropdown-item:hover {
            background-color: var(--color-bg-tertiary);
            color: var(--color-text-primary);
            text-decoration: none;
        }

        .nav-dropdown-item.active {
            background-color: var(--color-accent);
            color: white;
        }

        .nav-dropdown-item i {
            width: 18px;
            text-align: center;
        }

        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            background: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--color-text-primary);
            font-family: inherit;
            font-size: 0.875rem;
        }

        .user-dropdown-toggle:hover {
            background: var(--color-bg-hover);
            border-color: var(--color-accent);
        }

        .user-dropdown-name {
            color: var(--color-text-secondary);
            font-weight: 500;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-dropdown-arrow {
            font-size: 0.625rem;
            color: var(--color-text-muted);
            transition: transform 0.2s ease;
        }

        .user-dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            right: 0;
            left: auto;
            min-width: 220px;
        }

        .user-dropdown-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .user-dropdown-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-dropdown-info {
            display: flex;
            flex-direction: column;
        }

        .user-dropdown-fullname {
            font-weight: 600;
            color: var(--color-text-primary);
            font-size: 0.875rem;
        }

        .user-dropdown-level {
            font-size: 0.75rem;
            color: var(--color-accent);
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--color-border);
            margin: 0.5rem 0;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
            color: var(--color-danger);
        }

        .logout-btn:hover {
            background-color: rgba(248, 81, 73, 0.1);
            color: var(--color-danger);
        }

        .admin-link {
            color: var(--color-accent) !important;
        }

        .admin-link:hover {
            background-color: rgba(88, 166, 255, 0.1);
        }

        @media (max-width: 768px) {
            .user-dropdown-name {
                display: none;
            }

            .user-dropdown-toggle {
                padding: 0.375rem;
            }

            .user-dropdown-menu {
                position: fixed;
                top: 60px;
                right: 0.5rem;
                left: auto;
            }
        }

        [x-cloak] {
            display: none !important;
        }

        /* Footer Enhancement - Compact & Modern */
        .footer {
            background: var(--color-bg-secondary);
            border-top: 1px solid var(--color-border);
            padding: 1.5rem 1rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .footer-section {
            text-align: left;
        }

        .footer-section h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-links a {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
        }

        .footer-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--color-accent);
            transition: width 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--color-accent);
            transform: translateX(4px);
        }

        .footer-links a:hover::after {
            width: 100%;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .footer-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .footer-brand-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .footer-tagline {
            color: var(--color-text-secondary);
            font-size: 0.8125rem;
            margin-bottom: 0.75rem;
        }

        .footer-bottom {
            padding-top: 1rem;
            border-top: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .footer-copyright {
            color: var(--color-text-muted);
            font-size: 0.8125rem;
        }

        .footer-legal-links {
            display: flex;
            gap: 1rem;
        }

        .footer-legal-links a {
            color: var(--color-text-muted);
            font-size: 0.8125rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .footer-legal-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: -2px;
            left: 0;
            background-color: var(--color-accent);
            transition: width 0.3s ease;
        }

        .footer-legal-links a:hover {
            color: var(--color-accent);
        }

        .footer-legal-links a:hover::after {
            width: 100%;
        }

        /* Footer Mobile Responsive */
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-legal-links {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .footer {
                padding: 1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .footer-section {
                text-align: center;
            }

            .footer-brand {
                justify-content: center;
            }

            .footer-links {
                align-items: center;
            }
        }

        /* Cookie Consent Banner */
        .cookie-consent {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--color-bg-secondary);
            border-top: 1px solid var(--color-border);
            padding: 1rem 1.5rem;
            z-index: 9999;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
        }

        .cookie-consent-enter {
            animation: slideUp 0.3s ease-out;
        }

        .cookie-consent-leave {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(100%); opacity: 0; }
        }

        .cookie-consent-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .cookie-consent-text {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            flex: 1;
            min-width: 300px;
        }

        .cookie-icon {
            font-size: 1.5rem;
            color: var(--color-accent);
            flex-shrink: 0;
        }

        .cookie-title {
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.25rem;
        }

        .cookie-description {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .cookie-learn-more {
            color: var(--color-accent);
            text-decoration: underline;
        }

        .cookie-consent-actions {
            display: flex;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .footer-links {
                flex-direction: column;
                gap: 0.75rem;
            }

            .footer-legal {
                flex-direction: column;
                gap: 0.75rem;
            }

            .cookie-consent-content {
                flex-direction: column;
                text-align: center;
            }

            .cookie-consent-text {
                flex-direction: column;
                align-items: center;
            }

            .cookie-consent-actions {
                width: 100%;
                justify-content: center;
            }
        }

        /* Scroll to Top Indicator */
        .scroll-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(88, 166, 255, 0.3);
        }

        .scroll-indicator.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-indicator:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(88, 166, 255, 0.4);
        }

        /* Loading Skeleton Animation - Only when motion is OK */
        .skeleton {
            background: var(--color-bg-tertiary);
        }

        @media (prefers-reduced-motion: no-preference) {
            .skeleton {
                background: linear-gradient(90deg, var(--color-bg-tertiary) 0%, var(--color-bg-hover) 50%, var(--color-bg-tertiary) 100%);
                background-size: 200% 100%;
                animation: skeletonShimmer 1.5s ease-in-out infinite;
            }
        }

        @keyframes skeletonShimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Entrance animations - via CSS classes */
        .card-entrance {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .card-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--color-text-primary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 1rem;
            }

            .hero-logo {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .hero-tagline {
                font-size: 1rem;
            }

            .hero-actions {
                flex-direction: column;
            }

            .hero-status {
                flex-direction: column;
                gap: 0.5rem;
            }

            .song-rating {
                justify-content: center;
            }

            .schedule-item {
                flex-wrap: wrap;
            }

            .trending-item {
                padding: 0.5rem;
            }

            .dj-profile {
                flex-direction: column;
                text-align: center;
            }

            .dj-avatar {
                margin: 0 auto;
            }

            .news-item {
                flex-direction: column;
                text-align: center;
            }

            .card:hover {
                transform: none;
            }

            .now-playing-art {
                width: 150px;
                height: 150px;
            }

            .nav-links {
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                background: var(--color-bg-secondary);
                border-bottom: 1px solid var(--color-border);
                flex-direction: column;
                padding: 1rem;
                gap: 0.5rem;
                display: none;
            }

            .nav-links.mobile-open {
                display: flex;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .header-content {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }

        /* Tablet Responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .hero-section {
                padding: 2.5rem 1.5rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .now-playing-art {
                width: 180px;
                height: 180px;
            }
        }

        /* Focus States for Accessibility */
        .btn:focus,
        .nav-link:focus,
        .rating-btn:focus {
            outline: 2px solid var(--color-accent);
            outline-offset: 2px;
        }

        /* Reduced Motion Preference */
        @media (prefers-reduced-motion: reduce) {
            .hero-section::before,
            .hero-logo,
            .hero-title,
            .equalizer-bar,
            .dj-avatar,
            .schedule-item.active {
                animation: none;
            }

            .btn-primary::before,
            .btn-discord::before,
            .btn-twitch::before,
            .btn-battlenet::before {
                display: none;
            }

            .progress-fill::after {
                animation: none;
            }

            /* Reset hover transforms for motion sensitivity */
            .btn:hover,
            .card:hover,
            .history-item:hover,
            .trending-item:hover,
            .schedule-item:hover,
            .news-item:hover,
            .dj-profile:hover,
            .scroll-indicator:hover,
            .hero-logo:hover,
            .now-playing-art:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    {{-- Floating Background Effects - Subtle gamer feel --}}
    <x-floating-background intensity="subtle" :icons="['music', 'headphones', 'radio', 'gamepad']" />

    <header class="header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-radio" style="color: white;"></i>
                </div>
                <span>Los Santos Radio</span>
            </a>

            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle navigation menu">
                <i class="fas fa-bars" id="mobile-menu-icon"></i>
            </button>

            <nav class="nav-links" id="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="{{ route('schedule') }}" class="nav-link {{ request()->routeIs('schedule') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Schedule
                </a>
                <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> News
                </a>
                <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Events
                </a>
                <a href="{{ route('polls.index') }}" class="nav-link {{ request()->routeIs('polls.*') ? 'active' : '' }}">
                    <i class="fas fa-poll"></i> Polls
                </a>
                <a href="{{ route('requests.index') }}" class="nav-link {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                    <i class="fas fa-music"></i> Request
                </a>
                <!-- Games Dropdown -->
                <div class="nav-dropdown" x-data="{ open: false }">
                    <button @click="open = !open" class="nav-link nav-dropdown-toggle {{ request()->routeIs('games.*') ? 'active' : '' }}">
                        <i class="fas fa-gamepad"></i> Games <i class="fas fa-chevron-down" style="font-size: 0.625rem; margin-left: 0.25rem;"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="nav-dropdown-menu" x-cloak>
                        <a href="{{ route('games.free') }}" class="nav-dropdown-item {{ request()->routeIs('games.free') ? 'active' : '' }}">
                            <i class="fas fa-gift"></i> Free Games
                        </a>
                        <a href="{{ route('games.deals') }}" class="nav-dropdown-item {{ request()->routeIs('games.deals') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i> Game Deals
                        </a>
                    </div>
                </div>
                <!-- Videos Dropdown -->
                <div class="nav-dropdown" x-data="{ open: false }">
                    <button @click="open = !open" class="nav-link nav-dropdown-toggle {{ request()->routeIs('videos.*') ? 'active' : '' }}">
                        <i class="fas fa-video"></i> Videos <i class="fas fa-chevron-down" style="font-size: 0.625rem; margin-left: 0.25rem;"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="nav-dropdown-menu" x-cloak>
                        <a href="{{ route('videos.ylyl') }}" class="nav-dropdown-item {{ request()->routeIs('videos.ylyl') ? 'active' : '' }}">
                            <i class="fas fa-laugh-squint"></i> YLYL
                        </a>
                        <a href="{{ route('videos.clips') }}" class="nav-dropdown-item {{ request()->routeIs('videos.clips') ? 'active' : '' }}">
                            <i class="fas fa-tv"></i> Streamers Clips
                        </a>
                    </div>
                </div>
            </nav>

            <div class="user-menu">
                <!-- Search Button -->
                <button @click="$dispatch('open-search-modal')" class="btn btn-secondary search-toggle" title="Search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>

                <!-- Live Clock -->
                <div class="live-clock" @click="toggleFormat()" title="Click to toggle 12/24 hour format" x-data="liveClock()" x-init="init()">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <span class="live-clock-time" x-text="time"></span>
                    <span class="live-clock-format" x-text="getFormatLabel()"></span>
                </div>

                <!-- Theme Toggle Button -->
                <button @click="darkMode = !darkMode" class="btn btn-secondary theme-toggle" :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
                </button>

                @auth
                    <!-- User Dropdown Menu -->
                    <div class="nav-dropdown user-dropdown" x-data="{ open: false }">
                        <button @click="open = !open" class="user-dropdown-toggle">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                            <span class="user-dropdown-name">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down user-dropdown-arrow" :class="{ 'rotated': open }"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="nav-dropdown-menu user-dropdown-menu" x-cloak>
                            <div class="user-dropdown-header">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-dropdown-avatar">
                                <div class="user-dropdown-info">
                                    <span class="user-dropdown-fullname">{{ auth()->user()->name }}</span>
                                    <span class="user-dropdown-level">Level {{ auth()->user()->level ?? 1 }}</span>
                                </div>
                            </div>
                            <div class="user-dropdown-divider"></div>
                            <a href="{{ route('messages.index') }}" class="nav-dropdown-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                                <i class="fas fa-envelope"></i> Messages
                            </a>
                            <a href="{{ route('profile.edit') }}" class="nav-dropdown-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                <i class="fas fa-user-cog"></i> Settings
                            </a>
                            <a href="{{ route('profile.show', auth()->user()) }}" class="nav-dropdown-item">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            @if(auth()->user()->hasAnyRole(['admin', 'staff']))
                            <div class="user-dropdown-divider"></div>
                            <a href="{{ route('admin.dashboard') }}" class="nav-dropdown-item admin-link">
                                <i class="fas fa-shield-alt"></i> Admin Panel
                            </a>
                            @endif
                            <div class="user-dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="nav-dropdown-item logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                @endguest
            </div>
        </div>
    </header>

    <main class="main-content">
        {{ $slot }}
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-grid">
                {{-- Brand Section --}}
                <div class="footer-section">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <i class="fas fa-radio"></i>
                        </div>
                        <span class="footer-brand-name">Los Santos Radio</span>
                    </div>
                    <p class="footer-tagline">24/7 Online Radio & Gaming Hub</p>
                </div>

                {{-- Quick Links --}}
                <div class="footer-section">
                    <h4>Explore</h4>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('schedule') }}">Schedule</a>
                        <a href="{{ route('requests.index') }}">Request Songs</a>
                        <a href="{{ route('news.index') }}">News</a>
                    </div>
                </div>

                {{-- Community --}}
                <div class="footer-section">
                    <h4>Community</h4>
                    <div class="footer-links">
                        <a href="{{ route('events.index') }}">Events</a>
                        <a href="{{ route('games.index') }}">Games</a>
                        <a href="{{ route('videos.index') }}">Videos</a>
                        <a href="{{ route('djs.index') }}">DJ Profiles</a>
                    </div>
                </div>

                {{-- Connect --}}
                <div class="footer-section">
                    <h4>Connect</h4>
                    <div class="footer-links">
                        @if(config('services.discord.invite_url'))
                            <a href="{{ config('services.discord.invite_url') }}" target="_blank" rel="noopener">
                                <i class="fab fa-discord"></i> Discord
                            </a>
                        @endif
                        <a href="{{ route('about') }}">About Us</a>
                        <a href="{{ route('contact') }}">Contact</a>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="footer-bottom">
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} Los Santos Radio. Powered by AzuraCast.
                </div>
                <div class="footer-legal-links">
                    <a href="{{ route('legal.terms') }}">Terms</a>
                    <a href="{{ route('legal.privacy') }}">Privacy</a>
                    <a href="{{ route('legal.cookies') }}">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cookie Consent Banner -->
    <div x-data="cookieConsent()" x-init="init()" x-show="showBanner" x-cloak
         class="cookie-consent" x-transition:enter="cookie-consent-enter" x-transition:leave="cookie-consent-leave">
        <div class="cookie-consent-content">
            <div class="cookie-consent-text">
                <i class="fas fa-cookie-bite cookie-icon"></i>
                <div>
                    <p class="cookie-title">We use cookies</p>
                    <p class="cookie-description">
                        We use cookies to enhance your browsing experience, analyze site traffic, and personalize content.
                        By clicking "Accept All", you consent to our use of cookies.
                        <a href="{{ route('legal.cookies') }}" class="cookie-learn-more">Learn more</a>
                    </p>
                </div>
            </div>
            <div class="cookie-consent-actions">
                <button @click="acceptEssential()" class="btn btn-secondary btn-sm">Essential Only</button>
                <button @click="acceptAll()" class="btn btn-primary btn-sm">Accept All</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize cookie consent with default (essential only) until user chooses
        // This ensures scripts can check consent status early in page lifecycle
        window.cookieConsent = { analytics: false, marketing: false };

        // CSRF token for AJAX requests
        window.csrfToken = '{{ csrf_token() }}';

        // Mobile menu toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('nav-links');
            const icon = document.getElementById('mobile-menu-icon');
            navLinks.classList.toggle('mobile-open');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }

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
                        hours = hours ? hours : 12; // 0 should be 12
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

        // Cookie Consent Alpine.js component
        function cookieConsent() {
            return {
                showBanner: false,
                consentGiven: false,
                storageAvailable: true,

                init() {
                    // Check if localStorage is available
                    try {
                        const test = '__localStorage_test__';
                        localStorage.setItem(test, test);
                        localStorage.removeItem(test);
                        this.storageAvailable = true;
                    } catch (e) {
                        this.storageAvailable = false;
                    }

                    // Check if consent was already given
                    try {
                        const consent = this.storageAvailable ? localStorage.getItem('cookie_consent') : null;
                        if (!consent) {
                            // Show banner immediately for GDPR compliance
                            this.showBanner = true;
                        } else {
                            this.consentGiven = true;
                            this.applyConsent(consent);
                        }
                    } catch (e) {
                        // If localStorage fails, show the banner immediately
                        this.showBanner = true;
                    }
                },

                acceptAll() {
                    this.saveConsent('all');
                    this.showBanner = false;
                    this.applyConsent('all');
                    if (window.showToast) {
                        window.showToast('success', 'Cookie preferences saved');
                    }
                },

                acceptEssential() {
                    this.saveConsent('essential');
                    this.showBanner = false;
                    this.applyConsent('essential');
                    if (window.showToast) {
                        window.showToast('info', 'Only essential cookies enabled');
                    }
                },

                saveConsent(level) {
                    try {
                        if (this.storageAvailable) {
                            localStorage.setItem('cookie_consent', level);
                            localStorage.setItem('cookie_consent_date', new Date().toISOString());
                        }
                    } catch (e) {
                        // Storage failed, consent will only persist for this session
                        console.warn('Could not save cookie consent to localStorage');
                    }
                    this.consentGiven = true;
                },

                applyConsent(level) {
                    // Apply consent settings
                    if (level === 'all') {
                        // Enable analytics and other optional cookies
                        window.cookieConsent = { analytics: true, marketing: true };
                    } else {
                        // Only essential cookies
                        window.cookieConsent = { analytics: false, marketing: false };
                    }

                    // Dispatch event for other scripts to listen to
                    document.dispatchEvent(new CustomEvent('cookieConsentChanged', {
                        detail: window.cookieConsent
                    }));
                }
            };
        }
    </script>

    <!-- jQuery (required for Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configure Toastr options
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Display session flash messages as toasts
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        // Global toast helper function
        window.showToast = function(type, message) {
            switch(type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                default:
                    toastr.info(message);
            }
        };
    </script>

    <!-- Search Modal -->
    <div x-data="searchModal()" @open-search-modal.window="openModal()" @keydown.escape.window="closeModal()"
         data-search-url="{{ route('search.api') }}">
        <div x-show="isOpen" x-cloak class="search-modal-overlay" @click="closeModal()">
            <div class="search-modal" @click.stop>
                <div class="search-modal-header">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text"
                               x-model="query"
                               @input.debounce.300ms="search()"
                               placeholder="Search news, events, games, videos..."
                               class="search-input"
                               x-ref="searchInput"
                               autocomplete="off">
                        <button x-show="query" @click="clearSearch()" class="search-clear-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <button @click="closeModal()" class="search-close-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="search-modal-body">
                    <div x-show="loading" class="search-loading">
                        <i class="fas fa-spinner fa-spin"></i> Searching...
                    </div>

                    <div x-show="!loading && results.length > 0" class="search-results-list">
                        <template x-for="result in results" :key="result.id + '-' + result.type">
                            <a :href="result.url" class="search-result-item">
                                <div class="search-result-icon">
                                    <i :class="getResultIcon(result.type)"></i>
                                </div>
                                <div class="search-result-content">
                                    <div class="search-result-title" x-text="result.title"></div>
                                    <div class="search-result-meta">
                                        <span class="search-result-type" x-text="formatType(result.type)"></span>
                                        <span x-text="result.date_formatted"></span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div x-show="!loading && query.length >= 2 && results.length === 0" class="search-no-results">
                        <i class="fas fa-search"></i>
                        <p>No results found for "<span x-text="query"></span>"</p>
                    </div>

                    <div x-show="!loading && query.length < 2" class="search-prompt">
                        <i class="fas fa-lightbulb"></i>
                        <p>Type at least 2 characters to search</p>
                        <div class="search-shortcuts">
                            <span><kbd>ESC</kbd> to close</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchModal() {
            return {
                isOpen: false,
                query: '',
                results: [],
                loading: false,
                searchTimeout: null,

                openModal() {
                    this.isOpen = true;
                    this.$nextTick(() => {
                        this.$refs.searchInput.focus();
                    });
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                },

                clearSearch() {
                    this.query = '';
                    this.results = [];
                    this.$refs.searchInput.focus();
                },

                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    this.loading = true;

                    try {
                        // Use the data attribute for the search URL from Laravel route helper
                        const searchUrl = this.$root.dataset.searchUrl || '/api/search';
                        const response = await fetch(`${searchUrl}?q=${encodeURIComponent(this.query)}`);
                        const data = await response.json();

                        if (data.success) {
                            this.results = data.results;
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                getResultIcon(type) {
                    const icons = {
                        'news': 'fas fa-newspaper',
                        'event': 'fas fa-calendar-alt',
                        'free_game': 'fas fa-gift',
                        'deal': 'fas fa-tags',
                        'video': 'fas fa-video'
                    };
                    return icons[type] || 'fas fa-file';
                },

                formatType(type) {
                    return type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                }
            }
        }
    </script>

    <style>
        .search-toggle {
            padding: 0.5rem;
            min-width: 36px;
        }

        .search-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 10vh;
        }

        .search-modal {
            background: var(--color-bg-primary);
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            margin: 0 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: searchModalIn 0.2s ease-out;
        }

        @keyframes searchModalIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .search-modal-header {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--color-border);
            gap: 0.75rem;
        }

        .search-input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            background: var(--color-bg-secondary);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            gap: 0.5rem;
        }

        .search-input-wrapper .search-icon {
            color: var(--color-text-muted);
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1rem;
            color: var(--color-text-primary);
            outline: none;
        }

        .search-input::placeholder {
            color: var(--color-text-muted);
        }

        .search-clear-btn,
        .search-close-btn {
            background: none;
            border: none;
            color: var(--color-text-muted);
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .search-clear-btn:hover,
        .search-close-btn:hover {
            color: var(--color-text-primary);
        }

        .search-modal-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .search-loading,
        .search-no-results,
        .search-prompt {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: var(--color-text-muted);
        }

        .search-loading i,
        .search-no-results i,
        .search-prompt i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .search-shortcuts {
            margin-top: 1rem;
            font-size: 0.75rem;
        }

        .search-shortcuts kbd {
            background: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 4px;
            padding: 0.125rem 0.375rem;
            font-family: monospace;
        }

        .search-results-list {
            padding: 0.5rem;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .search-result-item:hover {
            background: var(--color-bg-secondary);
        }

        .search-result-icon {
            width: 36px;
            height: 36px;
            background: var(--color-bg-tertiary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-accent);
            flex-shrink: 0;
        }

        .search-result-content {
            flex: 1;
            min-width: 0;
        }

        .search-result-title {
            color: var(--color-text-primary);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-meta {
            display: flex;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .search-result-type {
            background: var(--color-bg-tertiary);
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
        }

        /* Listen Modal Styles */
        .listen-icon-btn {
            position: fixed;
            bottom: 24px;
            left: 24px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--color-accent), var(--color-accent-hover));
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .listen-icon-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
        }

        .listen-icon-btn i {
            animation: pulse-icon 2s ease-in-out infinite;
        }

        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .listen-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .listen-modal-backdrop.show {
            display: flex;
        }

        .listen-modal {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 16px;
            max-width: 550px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .listen-modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .listen-modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .listen-modal-close {
            background: none;
            border: none;
            color: var(--color-text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .listen-modal-close:hover {
            background: var(--color-bg-tertiary);
            color: var(--color-text-primary);
        }

        .listen-modal-body {
            padding: 24px;
        }

        .listen-option {
            background: var(--color-bg-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s;
        }

        .listen-option:hover {
            border-color: var(--color-accent);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .listen-option-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .listen-option-desc {
            color: var(--color-text-secondary);
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .listen-option-action {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .listen-btn {
            padding: 10px 20px;
            background: var(--color-accent);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .listen-btn:hover {
            background: var(--color-accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stream-url-box {
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 12px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.875rem;
            color: var(--color-accent);
            word-break: break-all;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .copy-btn {
            padding: 6px 12px;
            background: var(--color-accent);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: var(--color-accent-hover);
        }

        .listen-option-icon {
            color: var(--color-accent);
        }

        @media (max-width: 640px) {
            .listen-icon-btn {
                width: 48px;
                height: 48px;
                font-size: 20px;
                bottom: 20px;
                left: 20px;
            }

            .listen-modal {
                width: 95%;
            }

            .listen-option-action {
                flex-direction: column;
            }

            .listen-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <!-- Listen Button -->
    <button class="listen-icon-btn" 
            onclick="toggleListenModal()" 
            aria-label="How to Listen"
            aria-expanded="false"
            aria-controls="listenModal">
        <i class="fas fa-headphones" aria-hidden="true"></i>
    </button>

    <!-- Listen Modal -->
    <div id="listenModal" 
         class="listen-modal-backdrop" 
         role="dialog"
         aria-modal="true"
         aria-labelledby="listenModalTitle"
         onclick="if(event.target === this) toggleListenModal()">
        <div class="listen-modal">
            <div class="listen-modal-header">
                <h2 class="listen-modal-title" id="listenModalTitle">
                    <i class="fas fa-broadcast-tower" aria-hidden="true"></i>
                    How to Listen
                </h2>
                <button class="listen-modal-close" onclick="toggleListenModal()" aria-label="Close modal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <div class="listen-modal-body">
                <div class="listen-option">
                    <div class="listen-option-title">
                        <i class="fas fa-window-maximize listen-option-icon" aria-hidden="true"></i>
                        Open in Popup Window
                    </div>
                    <div class="listen-option-desc">
                        Listen in a separate popup window that stays on top while you browse.
                    </div>
                    <div class="listen-option-action">
                        <button class="listen-btn" onclick="window.open('{{ config('services.radio.public_player_url', 'https://radio.lossantosradio.com/public/los_santos_radio') }}', 'radioPlayer', 'width=400,height=600,resizable=yes')">
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            Open Popup Player
                        </button>
                    </div>
                </div>

                <div class="listen-option">
                    <div class="listen-option-title">
                        <i class="fab fa-chromecast listen-option-icon" aria-hidden="true"></i>
                        Use VLC or Media Player
                    </div>
                    <div class="listen-option-desc">
                        Copy the stream URL and open it in VLC, Windows Media Player, or any media player that supports streaming.
                    </div>
                    <div class="stream-url-box">
                        <code id="streamUrl">{{ config('services.radio.stream_url', 'https://radio.lossantosradio.com/listen/los_santos_radio/radio.mp3') }}</code>
                        <button class="copy-btn" onclick="copyStreamUrl(event)" aria-label="Copy stream URL">
                            <i class="fas fa-copy" aria-hidden="true"></i> Copy
                        </button>
                    </div>
                </div>

                <div class="listen-option">
                    <div class="listen-option-title">
                        <i class="fas fa-mobile-alt listen-option-icon" aria-hidden="true"></i>
                        Listen on Mobile
                    </div>
                    <div class="listen-option-desc">
                        Use your favorite radio streaming app (like TuneIn Radio) and add the stream URL above, or open the popup player link on your mobile browser.
                    </div>
                </div>

                <div class="listen-option">
                    <div class="listen-option-title">
                        <i class="fas fa-home listen-option-icon" aria-hidden="true"></i>
                        Smart Speakers
                    </div>
                    <div class="listen-option-desc">
                        Add Los Santos Radio to your smart speaker by providing the stream URL to services like Alexa Skills or Google Home routines.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleListenModal() {
            const modal = document.getElementById('listenModal');
            const button = document.querySelector('.listen-icon-btn');
            const isShowing = modal.classList.toggle('show');
            
            // Update aria-expanded for accessibility
            if (button) {
                button.setAttribute('aria-expanded', isShowing);
            }

            // Handle Escape key to close modal
            if (isShowing) {
                document.addEventListener('keydown', handleEscapeKey);
            } else {
                document.removeEventListener('keydown', handleEscapeKey);
            }
        }

        function handleEscapeKey(event) {
            if (event.key === 'Escape') {
                toggleListenModal();
            }
        }

        function copyStreamUrl(event) {
            const urlElement = document.getElementById('streamUrl');
            const url = urlElement.textContent;
            const btn = event.target.closest('.copy-btn');
            
            navigator.clipboard.writeText(url).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> Copied!';
                btn.style.background = '#10b981';
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                // Show error feedback instead of deprecated fallback
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i> Copy failed';
                btn.style.background = '#ef4444';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '';
                }, 2000);
            });
        }
    </script>

    {{-- Theme Overlay Loader --}}
    @php
        try {
            $activeTheme = \App\Models\Setting::get('site_theme', 'none');
            $allowedThemes = ['christmas', 'newyear'];
        } catch (\Exception $e) {
            $activeTheme = 'none';
            \Illuminate\Support\Facades\Log::warning('Failed to load theme setting', ['error' => $e->getMessage()]);
        }
    @endphp
    @if($activeTheme && in_array($activeTheme, $allowedThemes, true))
        <script src="{{ asset('themes/' . $activeTheme . '.js') }}" defer></script>
    @endif

    @stack('scripts')
</body>
</html>
