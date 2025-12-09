{{-- Enhanced Audio Player Component --}}
@props(['streamUrl' => config('services.radio.stream_url', 'https://radio.lossantosradio.com/listen/los_santos_radio/radio.mp3')])

<div class="enhanced-audio-player" x-data="audioPlayer('{{ $streamUrl }}')">
    <div class="player-container">
        {{-- Album Art / Visualizer --}}
        <div class="player-artwork">
            <div class="artwork-container">
                <img :src="nowPlaying.artwork || '/images/default-album.png'" 
                     :alt="nowPlaying.title + ' - ' + nowPlaying.artist"
                     class="artwork-image">
                <div class="visualizer" :class="{ 'active': isPlaying }">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
        </div>

        {{-- Player Info --}}
        <div class="player-info">
            <div class="song-info">
                <h3 class="song-title" x-text="nowPlaying.title || 'Los Santos Radio'"></h3>
                <p class="song-artist" x-text="nowPlaying.artist || 'Live Stream'"></p>
            </div>
            <div class="player-stats">
                <span class="listeners" x-show="listeners > 0">
                    <i class="fas fa-headphones"></i> 
                    <span x-text="listeners"></span> listening
                </span>
                <span class="status-badge" :class="isLive ? 'live' : 'autodj'" x-text="isLive ? 'LIVE' : 'AutoDJ'"></span>
            </div>
        </div>

        {{-- Player Controls --}}
        <div class="player-controls">
            {{-- Play/Pause Button --}}
            <button @click="togglePlayback" 
                    class="control-btn play-btn"
                    :class="{ 'playing': isPlaying, 'loading': isLoading }"
                    :aria-label="isLoading ? 'Loading...' : (isPlaying ? 'Pause' : 'Play')"
                    :disabled="isLoading">
                <i x-show="!isLoading" :class="isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
                <i x-show="isLoading" class="fas fa-spinner fa-spin"></i>
            </button>

            {{-- Volume Control --}}
            <div class="volume-control" x-data="{ showVolume: false }">
                <button @click="showVolume = !showVolume" 
                        @mouseenter="showVolume = true"
                        class="control-btn volume-btn"
                        :aria-label="isMuted ? 'Unmute' : 'Mute'">
                    <i :class="isMuted ? 'fas fa-volume-mute' : (volume > 50 ? 'fas fa-volume-up' : 'fas fa-volume-down')"></i>
                </button>
                <div class="volume-slider" 
                     x-show="showVolume" 
                     @mouseleave="showVolume = false"
                     @click.away="showVolume = false"
                     x-transition>
                    <input type="range" 
                           min="0" 
                           max="100" 
                           x-model="volume" 
                           @input="setVolume($event.target.value)"
                           class="slider"
                           aria-label="Volume">
                    <span class="volume-value" x-text="volume + '%'"></span>
                    <button @click="toggleMute" class="volume-mute-btn" :aria-label="isMuted ? 'Unmute' : 'Mute'">
                        <i :class="isMuted ? 'fas fa-volume-mute' : 'fas fa-volume-up'"></i>
                        <span x-text="isMuted ? 'Unmute' : 'Mute'"></span>
                    </button>
                </div>
            </div>

            {{-- Favorite Button --}}
            <button @click="toggleFavoriteStatus" 
                    class="control-btn favorite-btn"
                    :class="{ 'is-favorite': isFavorited }"
                    :aria-label="isFavorited ? 'Remove from favorites' : 'Add to favorites'">
                <i :class="isFavorited ? 'fas fa-heart' : 'far fa-heart'"></i>
            </button>

            {{-- Share Button --}}
            <button @click="shareNowPlaying" 
                    class="control-btn share-btn"
                    aria-label="Share now playing">
                <i class="fas fa-share-alt"></i>
            </button>

            {{-- Minimize Button --}}
            <button @click="toggleMinimize" 
                    class="control-btn minimize-btn"
                    :aria-label="isMinimized ? 'Expand player' : 'Minimize player'">
                <i :class="isMinimized ? 'fas fa-expand' : 'fas fa-compress'"></i>
            </button>

            {{-- Fullscreen Toggle (Optional) --}}
            <button @click="window.open('{{ config('services.radio.public_player_url') }}', 'radioPlayer', 'width=400,height=600')" 
                    class="control-btn fullscreen-btn"
                    aria-label="Open in new window">
                <i class="fas fa-external-link-alt"></i>
            </button>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="player-progress" x-show="progress.duration > 0 && !isMinimized">
        <div class="progress-bar">
            <div class="progress-fill" :style="{ width: progressPercent + '%' }"></div>
        </div>
        <div class="progress-times">
            <span x-text="formatTime(progress.elapsed)"></span>
            <span x-text="formatTime(progress.duration)"></span>
        </div>
    </div>
</div>
<script>
    function audioPlayer(streamUrl) {
        return {
            audio: null,
            isPlaying: false,
            isMuted: false,
            isLoading: false,
            isMinimized: false,
            isFavorited: false,
            volume: 70,
            nowPlaying: {
                title: 'Los Santos Radio',
                artist: 'Loading...',
                artwork: '/images/default-album.png',
                id: null
            },
            progress: {
                elapsed: 0,
                duration: 0
            },
            listeners: 0,
            isLive: false,
            progressPercent: 0,

            init() {
                // Check minimize state
                const minimized = localStorage.getItem('playerMinimized');
                this.isMinimized = minimized === 'true';
                
                // Apply minimized class to player
                if (this.isMinimized) {
                    this.$el.classList.add('minimized');
                }
                // Initialize audio element
                this.audio = new Audio(streamUrl);
                this.audio.volume = this.volume / 100;
                
                // Add audio event listeners
                this.audio.addEventListener('waiting', () => this.isLoading = true);
                this.audio.addEventListener('canplay', () => this.isLoading = false);
                this.audio.addEventListener('error', () => {
                    this.isLoading = false;
                    window.showToast?.('error', 'Unable to connect to stream');
                });
                
                // Load saved volume from localStorage
                const savedVolume = localStorage.getItem('playerVolume');
                if (savedVolume) {
                    this.volume = parseInt(savedVolume);
                    this.audio.volume = this.volume / 100;
                }

                // Check if should autoplay
                const autoplay = localStorage.getItem('playerAutoplay');
                if (autoplay === 'true') {
                    this.play();
                }

                // Listen for now playing updates
                document.addEventListener('nowPlayingUpdate', (e) => {
                    this.updateNowPlaying(e.detail);
                });

                // Fetch initial now playing data
                this.fetchNowPlaying();
                
                // Update every 10 seconds
                setInterval(() => this.fetchNowPlaying(), 10000);
            },

            togglePlayback() {
                if (this.isPlaying) {
                    this.pause();
                } else {
                    this.play();
                }
            },

            play() {
                this.isLoading = true;
                this.audio.play().then(() => {
                    this.isLoading = false;
                }).catch(err => {
                    console.error('Playback failed:', err);
                    this.isLoading = false;
                    window.showToast?.('error', 'Failed to start playback. Please check your connection.');
                });
                this.isPlaying = true;
                localStorage.setItem('playerAutoplay', 'true');
            },

            pause() {
                this.audio.pause();
                this.isPlaying = false;
                this.isLoading = false;
                localStorage.setItem('playerAutoplay', 'false');
            },

            toggleMute() {
                this.isMuted = !this.isMuted;
                this.audio.muted = this.isMuted;
            },

            setVolume(value) {
                this.volume = parseInt(value);
                this.audio.volume = this.volume / 100;
                this.isMuted = false;
                this.audio.muted = false;
                localStorage.setItem('playerVolume', this.volume);
            },

            formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins}:${secs.toString().padStart(2, '0')}`;
            },

            updateNowPlaying(data) {
                if (data.current_song) {
                    this.nowPlaying = {
                        title: data.current_song.title || 'Los Santos Radio',
                        artist: data.current_song.artist || 'Live Stream',
                        artwork: data.current_song.art || '/images/default-album.png'
                    };
                }
                
                if (data.listeners !== undefined) {
                    this.listeners = data.listeners.current || 0;
                }

                if (data.live !== undefined) {
                    this.isLive = data.live.is_live || false;
                }

                if (data.elapsed !== undefined && data.duration !== undefined) {
                    this.progress.elapsed = data.elapsed;
                    this.progress.duration = data.duration;
                    this.progressPercent = data.duration > 0 ? (data.elapsed / data.duration) * 100 : 0;
                }
            },

            async fetchNowPlaying() {
                try {
                    // Use the radio now-playing endpoint
                    const response = await fetch('/api/radio/now-playing');
                    const data = await response.json();
                    if (data.success) {
                        this.updateNowPlaying(data.data);
                    }
                } catch (err) {
                    console.error('Failed to fetch now playing:', err);
                }
            },

            toggleFavoriteStatus() {
                if (!this.nowPlaying.title || this.nowPlaying.title === 'Los Santos Radio') {
                    window.showToast?.('info', 'No song currently playing');
                    return;
                }

                const song = {
                    id: this.nowPlaying.id || `${this.nowPlaying.title}-${this.nowPlaying.artist}`,
                    title: this.nowPlaying.title,
                    artist: this.nowPlaying.artist,
                    artwork: this.nowPlaying.artwork
                };

                const isFav = window.toggleFavorite?.(song);
                this.isFavorited = isFav !== undefined ? isFav : !this.isFavorited;
                
                // Check favorite status on updates
                this.checkFavoriteStatus();
            },

            checkFavoriteStatus() {
                if (this.nowPlaying.id && window.isFavorite) {
                    this.isFavorited = window.isFavorite(this.nowPlaying.id);
                }
            },

            shareNowPlaying() {
                if (!this.nowPlaying.title || this.nowPlaying.title === 'Los Santos Radio') {
                    window.showToast?.('info', 'No song currently playing');
                    return;
                }

                const shareText = `🎵 Now Playing on Los Santos Radio:\n${this.nowPlaying.title} - ${this.nowPlaying.artist}\n\nListen live: ${window.location.origin}`;

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(shareText).then(() => {
                        window.showToast?.('success', 'Copied to clipboard! ✓');
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                        window.showToast?.('error', 'Failed to copy to clipboard');
                    });
                } else {
                    // Fallback for older browsers
                    const textarea = document.createElement('textarea');
                    textarea.value = shareText;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    window.showToast?.('success', 'Copied to clipboard! ✓');
                }
            },

            toggleMinimize() {
                this.isMinimized = !this.isMinimized;
                localStorage.setItem('playerMinimized', this.isMinimized);
                
                if (this.isMinimized) {
                    this.$el.classList.add('minimized');
                    window.showToast?.('info', 'Player minimized');
                } else {
                    this.$el.classList.remove('minimized');
                }
            }
        };
    }
</script>
