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

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Floating Background Effects - Subtle gamer feel --}}
    <x-floating-background intensity="subtle" :icons="['music', 'headphones', 'radio', 'gamepad']" />

    <header class="sticky top-0 z-50 bg-gray-900/95 backdrop-blur-md border-b border-gray-800/50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-300"></div>
                        <div class="relative w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition duration-300">
                            <i class="fas fa-radio text-white text-lg" aria-hidden="true"></i>
                        </div>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">Los Santos Radio</span>
                </a>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200" aria-label="Toggle navigation menu" x-data="{ mobileMenuOpen: false }">
                    <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex lg:items-center lg:space-x-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request()->routeIs('home') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        <i class="fas fa-home mr-2" aria-hidden="true"></i>Home
                    </a>
                    
                    <!-- Radio Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 inline-flex items-center {{ request()->routeIs('schedule') || request()->routeIs('requests.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                            <i class="fas fa-radio mr-2" aria-hidden="true"></i>Radio
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 rounded-xl bg-gray-800/95 backdrop-blur-md shadow-xl ring-1 ring-gray-700/50 py-2">
                            <a href="{{ route('schedule') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('schedule') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-calendar-alt w-5 mr-3" aria-hidden="true"></i>Schedule
                            </a>
                            <a href="{{ route('requests.index') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('requests.*') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-music w-5 mr-3" aria-hidden="true"></i>Requests
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('news.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request()->routeIs('news.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        <i class="fas fa-newspaper mr-2" aria-hidden="true"></i>News
                    </a>
                    
                    <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request()->routeIs('events.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        <i class="fas fa-calendar-check mr-2" aria-hidden="true"></i>Events
                    </a>
                    
                    <a href="{{ route('polls.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request()->routeIs('polls.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                        <i class="fas fa-poll mr-2" aria-hidden="true"></i>Polls
                    </a>

                    <!-- Games Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 inline-flex items-center {{ request()->routeIs('games.*') || request()->routeIs('media.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                            <i class="fas fa-gamepad mr-2" aria-hidden="true"></i>Games
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 rounded-xl bg-gray-800/95 backdrop-blur-md shadow-xl ring-1 ring-gray-700/50 py-2">
                            <a href="{{ route('games.free') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('games.free') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-gift w-5 mr-3" aria-hidden="true"></i>Free Games
                            </a>
                            <a href="{{ route('games.deals') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('games.deals') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-tags w-5 mr-3" aria-hidden="true"></i>Game Deals
                            </a>
                            <a href="{{ route('media.index') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('media.*') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-download w-5 mr-3" aria-hidden="true"></i>Downloads
                            </a>
                        </div>
                    </div>

                    <!-- Videos Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 inline-flex items-center {{ request()->routeIs('videos.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800' }}">
                            <i class="fas fa-video mr-2" aria-hidden="true"></i>Videos
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 rounded-xl bg-gray-800/95 backdrop-blur-md shadow-xl ring-1 ring-gray-700/50 py-2">
                            <a href="{{ route('videos.ylyl') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('videos.ylyl') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-laugh-squint w-5 mr-3" aria-hidden="true"></i>YLYL
                            </a>
                            <a href="{{ route('videos.clips') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('videos.clips') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                <i class="fas fa-tv w-5 mr-3" aria-hidden="true"></i>Streamers Clips
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Right side actions -->
                <div class="hidden lg:flex lg:items-center lg:space-x-2">
                    <!-- Search Button -->
                    <button @click="$dispatch('open-search-modal')" class="p-2.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200" title="Search">
                        <i class="fas fa-search text-lg" aria-hidden="true"></i>
                    </button>

                    <!-- Live Clock -->
                    <div @click="toggleFormat()" title="Click to toggle 12/24 hour format" x-data="liveClock()" x-init="init()" class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-800/50 text-sm font-medium text-gray-300 hover:bg-gray-800 transition duration-200 cursor-pointer">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                        <span x-text="time"></span>
                        <span class="text-xs text-gray-500" x-text="getFormatLabel()"></span>
                    </div>

                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200" :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                        <i class="fas text-lg" :class="darkMode ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
                    </button>

                    @auth
                        <!-- User Dropdown -->
                        <div class="relative ml-3" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-3 p-1.5 rounded-lg hover:bg-gray-800 transition duration-200">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-full ring-2 ring-gray-700">
                                <span class="text-sm font-medium text-gray-300 hidden xl:block">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-64 rounded-xl bg-gray-800/95 backdrop-blur-md shadow-xl ring-1 ring-gray-700/50 py-2">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-12 w-12 rounded-full ring-2 ring-blue-500">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-400">Level {{ auth()->user()->level ?? 1 }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('messages.index') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('messages.*') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                        <i class="fas fa-envelope w-5 mr-3" aria-hidden="true"></i>Messages
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm transition duration-200 {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700/50 hover:text-white' }}">
                                        <i class="fas fa-user-cog w-5 mr-3" aria-hidden="true"></i>Settings
                                    </a>
                                    <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center px-4 py-3 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-white transition duration-200">
                                        <i class="fas fa-user w-5 mr-3" aria-hidden="true"></i>My Profile
                                    </a>
                                </div>

                                @if(auth()->user()->hasAnyRole(['admin', 'staff']))
                                <div class="border-t border-gray-700 py-2">
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-purple-400 hover:bg-gray-700/50 hover:text-purple-300 transition duration-200">
                                        <i class="fas fa-shield-alt w-5 mr-3" aria-hidden="true"></i>Admin Panel
                                    </a>
                                </div>
                                @endif

                                <div class="border-t border-gray-700 py-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-400 hover:bg-gray-700/50 hover:text-red-300 transition duration-200">
                                            <i class="fas fa-sign-out-alt w-5 mr-3" aria-hidden="true"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition duration-200">
                            <i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>Sign In
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-data="{ mobileMenuOpen: false }" x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false" class="lg:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('home') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-home mr-3" aria-hidden="true"></i>Home
                </a>
                
                <!-- Mobile Radio Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('schedule') || request()->routeIs('requests.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span><i class="fas fa-radio mr-3" aria-hidden="true"></i>Radio</span>
                        <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-6 space-y-1 mt-1">
                        <a href="{{ route('schedule') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('schedule') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-calendar-alt mr-3" aria-hidden="true"></i>Schedule
                        </a>
                        <a href="{{ route('requests.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('requests.*') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-music mr-3" aria-hidden="true"></i>Requests
                        </a>
                    </div>
                </div>

                <a href="{{ route('news.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('news.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-newspaper mr-3" aria-hidden="true"></i>News
                </a>
                
                <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('events.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-calendar-check mr-3" aria-hidden="true"></i>Events
                </a>
                
                <a href="{{ route('polls.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('polls.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-poll mr-3" aria-hidden="true"></i>Polls
                </a>

                <!-- Mobile Games Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('games.*') || request()->routeIs('media.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span><i class="fas fa-gamepad mr-3" aria-hidden="true"></i>Games</span>
                        <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-6 space-y-1 mt-1">
                        <a href="{{ route('games.free') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('games.free') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-gift mr-3" aria-hidden="true"></i>Free Games
                        </a>
                        <a href="{{ route('games.deals') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('games.deals') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tags mr-3" aria-hidden="true"></i>Game Deals
                        </a>
                        <a href="{{ route('media.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('media.*') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-download mr-3" aria-hidden="true"></i>Downloads
                        </a>
                    </div>
                </div>

                <!-- Mobile Videos Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('videos.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span><i class="fas fa-video mr-3" aria-hidden="true"></i>Videos</span>
                        <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-6 space-y-1 mt-1">
                        <a href="{{ route('videos.ylyl') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('videos.ylyl') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-laugh-squint mr-3" aria-hidden="true"></i>YLYL
                        </a>
                        <a href="{{ route('videos.clips') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('videos.clips') ? 'bg-blue-600/20 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-tv mr-3" aria-hidden="true"></i>Streamers Clips
                        </a>
                    </div>
                </div>

                <!-- Mobile Actions -->
                <div class="pt-4 border-t border-gray-800 space-y-2">
                    <button @click="$dispatch('open-search-modal')" class="w-full flex items-center px-3 py-2 rounded-lg text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas fa-search mr-3" aria-hidden="true"></i>Search
                    </button>
                    <button @click="darkMode = !darkMode" class="w-full flex items-center px-3 py-2 rounded-lg text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
                        <i class="fas mr-3" :class="darkMode ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
                        <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                    </button>
                    
                    @auth
                        <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-6 w-6 rounded-full mr-3">
                            {{ auth()->user()->name }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg text-base font-medium text-red-400 hover:bg-gray-800 hover:text-red-300">
                                <i class="fas fa-sign-out-alt mr-3" aria-hidden="true"></i>Logout
                            </button>
                        </form>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700">
                            <i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>Sign In
                        </a>
                    @endguest
                </div>
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
        } catch (\Exception $e) {
            $activeTheme = 'none';
            \Illuminate\Support\Facades\Log::warning('Failed to load theme setting', ['error' => $e->getMessage()]);
        }
        $allowedThemes = ['christmas', 'newyear'];
    @endphp
    @if($activeTheme && $activeTheme !== 'none' && in_array($activeTheme, $allowedThemes, true))
        <script src="{{ asset('themes/' . $activeTheme . '.js') }}" defer></script>
    @endif

    @stack('scripts')
</body>
</html>
