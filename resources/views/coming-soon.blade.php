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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script></head>
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
                        <img :src="albumArt" :alt="songTitle + ' album art'" @@error="albumArt = null">
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
                <span role="button" tabindex="0" class="social-link" title="Discord - Coming Soon" aria-label="Join our Discord (Coming Soon)" @click="window.showToast?.('info', 'Discord link coming soon!')" @keydown.enter="window.showToast?.('info', 'Discord link coming soon!')" @keydown.space.prevent="window.showToast?.('info', 'Discord link coming soon!')">
                    <i class="fab fa-discord" aria-hidden="true"></i>
                </span>
                <span role="button" tabindex="0" class="social-link" title="Twitter - Coming Soon" aria-label="Follow us on Twitter (Coming Soon)" @click="window.showToast?.('info', 'Twitter link coming soon!')" @keydown.enter="window.showToast?.('info', 'Twitter link coming soon!')" @keydown.space.prevent="window.showToast?.('info', 'Twitter link coming soon!')">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                </span>
                <span role="button" tabindex="0" class="social-link" title="Instagram - Coming Soon" aria-label="Follow us on Instagram (Coming Soon)" @click="window.showToast?.('info', 'Instagram link coming soon!')" @keydown.enter="window.showToast?.('info', 'Instagram link coming soon!')" @keydown.space.prevent="window.showToast?.('info', 'Instagram link coming soon!')">
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
                streamUrl: 'https://radio.lossantosradio.com/listen/los_santos_radio/radio.mp3',
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

        // Global toast helper function - attached to window for consistency with optional chaining
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
</body>
</html>
