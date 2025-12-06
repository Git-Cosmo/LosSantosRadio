<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Coming Soon - Los Santos Radio</title>
    <meta name="description" content="Los Santos Radio is launching soon! Get ready for 24/7 music streaming, song requests, and an amazing gaming community.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Toastr CSS for notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
            --color-danger: #f85149;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg-primary);
            color: var(--color-text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Elements */
        .background-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .floating-note {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: floatUp 15s linear infinite;
            color: var(--color-accent);
        }

        @keyframes floatUp {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.1; }
            90% { opacity: 0.1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .radio-waves {
            position: absolute;
            width: 400px;
            height: 400px;
            border: 2px solid var(--color-accent);
            border-radius: 50%;
            opacity: 0;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: radioWave 4s ease-out infinite;
        }

        @keyframes radioWave {
            0% { transform: translate(-50%, -50%) scale(0.3); opacity: 0.3; }
            100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; }
        }

        /* Main Container */
        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 900px;
            width: 100%;
        }

        /* Logo Section */
        .logo-section {
            margin-bottom: 2rem;
        }

        .logo-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7, #ec4899);
            background-size: 200% 200%;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3.5rem;
            color: white;
            animation: logoFloat 3s ease-in-out infinite, gradientShift 4s ease infinite;
            box-shadow: 0 20px 60px rgba(88, 166, 255, 0.4);
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .site-title {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--color-accent), #a855f7, #ec4899);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
            margin-bottom: 0.5rem;
        }

        .tagline {
            font-size: 1.25rem;
            color: var(--color-text-secondary);
            margin-bottom: 1rem;
        }

        /* Coming Soon Badge */
        .coming-soon-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, rgba(88, 166, 255, 0.2), rgba(168, 85, 247, 0.2));
            border: 1px solid var(--color-accent);
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--color-accent);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(88, 166, 255, 0.4); }
            50% { opacity: 0.9; box-shadow: 0 0 0 10px rgba(88, 166, 255, 0); }
        }

        /* Countdown Section */
        .countdown-section {
            margin: 3rem 0;
        }

        .countdown-title {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            min-width: 100px;
            transition: all 0.3s ease;
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-color: var(--color-accent);
        }

        .countdown-value {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-text-muted);
            margin-top: 0.5rem;
        }

        /* Now Playing Section */
        .now-playing-section {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 16px;
            padding: 2rem;
            margin: 3rem 0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .now-playing-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .now-playing-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: var(--color-danger);
            color: white;
            font-size: 0.625rem;
            font-weight: 600;
            border-radius: 4px;
            text-transform: uppercase;
            animation: pulse 2s ease-in-out infinite;
        }

        .now-playing-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .album-art {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            background: var(--color-bg-tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .album-art img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .album-art-placeholder {
            font-size: 2.5rem;
            color: var(--color-text-muted);
        }

        .song-info {
            flex: 1;
            text-align: left;
        }

        .song-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .song-artist {
            font-size: 1rem;
            color: var(--color-text-secondary);
            margin-bottom: 1rem;
        }

        /* Audio Player Controls */
        .player-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .play-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(88, 166, 255, 0.4);
        }

        .play-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(88, 166, 255, 0.5);
        }

        .volume-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .volume-icon {
            color: var(--color-text-muted);
            font-size: 1rem;
            cursor: pointer;
        }

        .volume-slider {
            flex: 1;
            height: 4px;
            -webkit-appearance: none;
            appearance: none;
            background: var(--color-bg-tertiary);
            border-radius: 2px;
            cursor: pointer;
        }

        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--color-accent);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .volume-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        /* Equalizer Animation */
        .equalizer {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 24px;
        }

        .eq-bar {
            width: 4px;
            background: linear-gradient(to top, var(--color-accent), #a855f7);
            border-radius: 2px;
            animation: eqBounce 0.5s ease-in-out infinite alternate;
        }

        .eq-bar:nth-child(1) { animation-delay: 0s; height: 8px; }
        .eq-bar:nth-child(2) { animation-delay: 0.1s; height: 16px; }
        .eq-bar:nth-child(3) { animation-delay: 0.2s; height: 12px; }
        .eq-bar:nth-child(4) { animation-delay: 0.3s; height: 20px; }
        .eq-bar:nth-child(5) { animation-delay: 0.4s; height: 10px; }

        @keyframes eqBounce {
            0% { transform: scaleY(0.3); }
            100% { transform: scaleY(1); }
        }

        .equalizer.paused .eq-bar {
            animation-play-state: paused;
        }

        /* Features Preview */
        .features-section {
            margin: 3rem 0;
        }

        .features-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .feature-card {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--color-accent);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(88, 166, 255, 0.2), rgba(168, 85, 247, 0.2));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--color-accent);
            margin-bottom: 1rem;
        }

        .feature-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .feature-desc {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 100;
            padding: 0.75rem;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 50%;
            cursor: pointer;
            color: var(--color-text-primary);
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: rotate(15deg);
            border-color: var(--color-accent);
        }

        /* Footer */
        .footer {
            margin-top: 3rem;
            padding: 2rem;
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            transform: translateY(-3px);
            border-color: var(--color-accent);
            color: var(--color-accent);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .site-title {
                font-size: 2rem;
            }

            .countdown-item {
                padding: 1rem 1.5rem;
                min-width: 80px;
            }

            .countdown-value {
                font-size: 2rem;
            }

            .now-playing-content {
                flex-direction: column;
                text-align: center;
            }

            .song-info {
                text-align: center;
            }

            .player-controls {
                justify-content: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .floating-note,
            .radio-waves,
            .logo-icon,
            .eq-bar {
                animation: none;
            }

            .coming-soon-badge,
            .live-badge {
                animation: none;
            }

            .countdown-item:hover,
            .feature-card:hover,
            .play-btn:hover,
            .theme-toggle:hover,
            .social-link:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <!-- Background Elements -->
    <div class="background-elements">
        <div class="floating-note" style="left: 5%; animation-delay: 0s;"><i class="fas fa-music" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 15%; animation-delay: 2s;"><i class="fas fa-headphones" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 30%; animation-delay: 4s;"><i class="fas fa-radio" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 45%; animation-delay: 6s;"><i class="fas fa-music" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 60%; animation-delay: 8s;"><i class="fas fa-microphone" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 75%; animation-delay: 10s;"><i class="fas fa-volume-up" aria-hidden="true"></i></div>
        <div class="floating-note" style="left: 90%; animation-delay: 12s;"><i class="fas fa-compact-disc" aria-hidden="true"></i></div>
        <div class="radio-waves"></div>
        <div class="radio-waves" style="animation-delay: 1s;"></div>
        <div class="radio-waves" style="animation-delay: 2s;"></div>
        <div class="radio-waves" style="animation-delay: 3s;"></div>
    </div>

    <!-- Theme Toggle -->
    <button @click="darkMode = !darkMode" class="theme-toggle" :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
    </button>

    <!-- Main Container -->
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-radio" aria-hidden="true"></i>
            </div>
            <h1 class="site-title">Los Santos Radio</h1>
            <p class="tagline">Your 24/7 Online Radio & Gaming Community</p>
            <div class="coming-soon-badge">
                <i class="fas fa-rocket" aria-hidden="true"></i>
                <span>Launching Soon</span>
            </div>
        </div>

        <!-- Countdown Section -->
        <div class="countdown-section" x-data="countdown()" x-init="init()">
            <p class="countdown-title">Launching In</p>
            <div class="countdown">
                <div class="countdown-item">
                    <div class="countdown-value" x-text="days"></div>
                    <div class="countdown-label">Days</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-value" x-text="hours"></div>
                    <div class="countdown-label">Hours</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-value" x-text="minutes"></div>
                    <div class="countdown-label">Minutes</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-value" x-text="seconds"></div>
                    <div class="countdown-label">Seconds</div>
                </div>
            </div>
        </div>

        <!-- Now Playing Section -->
        <div class="now-playing-section" x-data="audioPlayer()" x-init="init()">
            <div class="now-playing-header">
                <i class="fas fa-broadcast-tower" style="color: var(--color-accent);" aria-hidden="true"></i>
                <span class="now-playing-title">Now Playing</span>
                <span class="live-badge" x-show="isLive">
                    <i class="fas fa-circle" style="font-size: 0.5rem;" aria-hidden="true"></i> LIVE
                </span>
            </div>
            <div class="now-playing-content">
                <div class="album-art">
                    <template x-if="albumArt">
                        <img :src="albumArt" :alt="songTitle + ' album art'" @error="albumArt = null">
                    </template>
                    <template x-if="!albumArt">
                        <i class="fas fa-music album-art-placeholder" aria-hidden="true"></i>
                    </template>
                </div>
                <div class="song-info">
                    <h3 class="song-title" x-text="songTitle">Loading...</h3>
                    <p class="song-artist" x-text="songArtist">Please wait</p>
                    <div class="equalizer" :class="{ 'paused': !isPlaying }">
                        <div class="eq-bar"></div>
                        <div class="eq-bar"></div>
                        <div class="eq-bar"></div>
                        <div class="eq-bar"></div>
                        <div class="eq-bar"></div>
                    </div>
                </div>
            </div>
            <div class="player-controls">
                <button class="play-btn" @click="togglePlay()" :aria-label="isPlaying ? 'Pause' : 'Play'">
                    <i class="fas" :class="isPlaying ? 'fa-pause' : 'fa-play'" aria-hidden="true"></i>
                </button>
                <div class="volume-control">
                    <i class="fas volume-icon" :class="volume === 0 ? 'fa-volume-mute' : (volume < 50 ? 'fa-volume-down' : 'fa-volume-up')" @click="toggleMute()" aria-hidden="true"></i>
                    <input type="range" class="volume-slider" min="0" max="100" x-model="volume" @input="setVolume()" aria-label="Volume">
                </div>
            </div>
        </div>

        <!-- Features Preview -->
        <div class="features-section">
            <h2 class="features-title">What's Coming</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-music" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-name">24/7 Music</h3>
                    <p class="feature-desc">Non-stop music streaming with live DJ shows</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-list-ul" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-name">Song Requests</h3>
                    <p class="feature-desc">Request your favorite tracks to be played</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gamepad" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-name">Gaming Community</h3>
                    <p class="feature-desc">Connect with fellow gamers and music lovers</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy" aria-hidden="true"></i>
                    </div>
                    <h3 class="feature-name">Rewards & XP</h3>
                    <p class="feature-desc">Earn experience and unlock achievements</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="social-links">
                <span role="button" tabindex="0" class="social-link" title="Discord - Coming Soon" aria-label="Join our Discord (Coming Soon)">
                    <i class="fab fa-discord" aria-hidden="true"></i>
                </span>
                <span role="button" tabindex="0" class="social-link" title="Twitter - Coming Soon" aria-label="Follow us on Twitter (Coming Soon)">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                </span>
                <span role="button" tabindex="0" class="social-link" title="Instagram - Coming Soon" aria-label="Follow us on Instagram (Coming Soon)">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                </span>
            </div>
            <p>&copy; {{ date('Y') }} Los Santos Radio. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // Configuration constants
        const NOW_PLAYING_REFRESH_INTERVAL = 10000; // 10 seconds

        // Countdown Timer
        function countdown() {
            return {
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                interval: null,
                // Target date is passed from Laravel config
                targetDate: new Date('{{ config('app.launch_date', '2024-12-10T18:00:00Z') }}'),

                init() {
                    this.updateCountdown();
                    this.interval = setInterval(() => this.updateCountdown(), 1000);
                },

                destroy() {
                    // Cleanup interval to prevent memory leaks
                    if (this.interval) {
                        clearInterval(this.interval);
                        this.interval = null;
                    }
                },

                updateCountdown() {
                    const now = new Date();
                    const diff = this.targetDate - now;

                    if (diff <= 0) {
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        clearInterval(this.interval);
                        // Optionally redirect when countdown ends
                        // window.location.reload();
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    this.days = days.toString().padStart(2, '0');
                    this.hours = hours.toString().padStart(2, '0');
                    this.minutes = minutes.toString().padStart(2, '0');
                    this.seconds = seconds.toString().padStart(2, '0');
                }
            };
        }

        // Audio Player
        function audioPlayer() {
            return {
                isPlaying: false,
                isLive: true,
                volume: 70,
                previousVolume: 70,
                songTitle: 'Loading...',
                songArtist: 'Please wait',
                albumArt: null,
                audio: null,
                streamUrl: '{{ $streamUrl ?? '' }}',
                refreshInterval: null,

                init() {
                    this.fetchNowPlaying();
                    this.refreshInterval = setInterval(() => this.fetchNowPlaying(), NOW_PLAYING_REFRESH_INTERVAL);
                },

                destroy() {
                    // Cleanup interval to prevent memory leaks
                    if (this.refreshInterval) {
                        clearInterval(this.refreshInterval);
                        this.refreshInterval = null;
                    }
                },

                fetchNowPlaying() {
                    fetch('/api/radio/now-playing')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data) {
                                this.songTitle = data.data.current_song?.title || 'Unknown Title';
                                this.songArtist = data.data.current_song?.artist || 'Unknown Artist';
                                // Validate album art URL to prevent XSS
                                const artUrl = data.data.current_song?.art;
                                this.albumArt = (artUrl && (artUrl.startsWith('http://') || artUrl.startsWith('https://'))) ? artUrl : null;
                                this.isLive = data.data.is_live || false;
                            }
                        })
                        .catch((error) => {
                            console.error('Failed to fetch now playing:', error);
                            this.songTitle = 'Stream Offline';
                            this.songArtist = 'Check back soon';
                        });
                },

                togglePlay() {
                    if (!this.streamUrl) {
                        window.showToast?.('info', 'Stream not available yet. Check back at launch!');
                        return;
                    }

                    if (!this.audio) {
                        this.audio = new Audio(this.streamUrl);
                        this.audio.volume = this.volume / 100;
                    }

                    if (this.isPlaying) {
                        this.audio.pause();
                        this.isPlaying = false;
                    } else {
                        this.audio.play().then(() => {
                            this.isPlaying = true;
                        }).catch((e) => {
                            console.error('Playback failed:', e);
                            window.showToast?.('error', 'Unable to play stream. Please try again.');
                        });
                    }
                },

                setVolume() {
                    if (this.audio) {
                        this.audio.volume = this.volume / 100;
                    }
                },

                toggleMute() {
                    if (this.volume > 0) {
                        this.previousVolume = this.volume;
                        this.volume = 0;
                    } else {
                        this.volume = this.previousVolume || 70;
                    }
                    this.setVolume();
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

        // Global toast helper function
        function showToast(type, message) {
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
        }
    </script>
</body>
</html>
