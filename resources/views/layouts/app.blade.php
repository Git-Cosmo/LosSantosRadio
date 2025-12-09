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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <!-- Radio Dropdown -->
                <div class="nav-dropdown" x-data="{ open: false }">
                    <button @click="open = !open" class="nav-link nav-dropdown-toggle {{ request()->routeIs('schedule') || request()->routeIs('requests.*') ? 'active' : '' }}">
                        <i class="fas fa-radio"></i> Radio <i class="fas fa-chevron-down" style="font-size: 0.625rem; margin-left: 0.25rem;"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="nav-dropdown-menu" x-cloak>
                        <a href="{{ route('schedule') }}" class="nav-dropdown-item {{ request()->routeIs('schedule') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i> Schedule
                        </a>
                        <a href="{{ route('requests.index') }}" class="nav-dropdown-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                            <i class="fas fa-music"></i> Requests
                        </a>
                    </div>
                </div>
                <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> News
                </a>
                <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Events
                </a>
                <a href="{{ route('polls.index') }}" class="nav-link {{ request()->routeIs('polls.*') ? 'active' : '' }}">
                    <i class="fas fa-poll"></i> Polls
                </a>
                <!-- Games Dropdown -->
                <div class="nav-dropdown" x-data="{ open: false }">
                    <button @click="open = !open" class="nav-link nav-dropdown-toggle {{ request()->routeIs('games.*') || request()->routeIs('media.*') ? 'active' : '' }}">
                        <i class="fas fa-gamepad"></i> Games <i class="fas fa-chevron-down" style="font-size: 0.625rem; margin-left: 0.25rem;"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="nav-dropdown-menu" x-cloak>
                        <a href="{{ route('games.free') }}" class="nav-dropdown-item {{ request()->routeIs('games.free') ? 'active' : '' }}">
                            <i class="fas fa-gift"></i> Free Games
                        </a>
                        <a href="{{ route('games.deals') }}" class="nav-dropdown-item {{ request()->routeIs('games.deals') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i> Game Deals
                        </a>
                        <a href="{{ route('media.index') }}" class="nav-dropdown-item {{ request()->routeIs('media.*') ? 'active' : '' }}">
                            <i class="fas fa-download"></i> Downloads
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
