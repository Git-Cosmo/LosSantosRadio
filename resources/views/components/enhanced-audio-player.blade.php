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
                    :class="{ 'playing': isPlaying }"
                    :aria-label="isPlaying ? 'Pause' : 'Play'">
                <i :class="isPlaying ? 'fas fa-pause' : 'fas fa-play'"></i>
            </button>

            {{-- Volume Control --}}
            <div class="volume-control" x-data="{ showVolume: false }">
                <button @click="toggleMute" 
                        @mouseenter="showVolume = true"
                        class="control-btn volume-btn"
                        :aria-label="isMuted ? 'Unmute' : 'Mute'">
                    <i :class="isMuted ? 'fas fa-volume-mute' : (volume > 50 ? 'fas fa-volume-up' : 'fas fa-volume-down')"></i>
                </button>
                <div class="volume-slider" 
                     x-show="showVolume" 
                     @mouseleave="showVolume = false"
                     x-transition>
                    <input type="range" 
                           min="0" 
                           max="100" 
                           x-model="volume" 
                           @input="setVolume($event.target.value)"
                           class="slider"
                           aria-label="Volume">
                    <span class="volume-value" x-text="volume + '%'"></span>
                </div>
            </div>

            {{-- Fullscreen Toggle (Optional) --}}
            <button @click="window.open('{{ config('services.radio.public_player_url') }}', 'radioPlayer', 'width=400,height=600')" 
                    class="control-btn fullscreen-btn"
                    aria-label="Open in new window">
                <i class="fas fa-external-link-alt"></i>
            </button>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="player-progress" x-show="progress.duration > 0">
        <div class="progress-bar">
            <div class="progress-fill" :style="{ width: progressPercent + '%' }"></div>
        </div>
        <div class="progress-times">
            <span x-text="formatTime(progress.elapsed)"></span>
            <span x-text="formatTime(progress.duration)"></span>
        </div>
    </div>
</div>

<style>
    .enhanced-audio-player {
        background: linear-gradient(135deg, var(--color-bg-secondary), var(--color-bg-tertiary));
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        margin: 1rem 0;
    }

    .player-container {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        align-items: center;
    }

    /* Artwork & Visualizer */
    .player-artwork {
        position: relative;
        width: 80px;
        height: 80px;
    }

    .artwork-container {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .artwork-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .artwork-container:hover .artwork-image {
        transform: scale(1.05);
    }

    .visualizer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 24px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 3px;
        padding: 4px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .visualizer.active {
        opacity: 1;
    }

    .visualizer .bar {
        width: 3px;
        background: var(--color-accent);
        border-radius: 2px;
        transition: height 0.15s ease;
    }

    .visualizer.active .bar {
        animation: pulse 0.8s ease-in-out infinite;
    }

    .visualizer .bar:nth-child(1) { animation-delay: 0s; }
    .visualizer .bar:nth-child(2) { animation-delay: 0.1s; }
    .visualizer .bar:nth-child(3) { animation-delay: 0.2s; }
    .visualizer .bar:nth-child(4) { animation-delay: 0.3s; }
    .visualizer .bar:nth-child(5) { animation-delay: 0.4s; }

    @keyframes pulse {
        0%, 100% { height: 4px; }
        50% { height: 16px; }
    }

    /* Player Info */
    .player-info {
        min-width: 0;
    }

    .song-info {
        margin-bottom: 0.5rem;
    }

    .song-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--color-text-primary);
        margin: 0 0 0.25rem 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .song-artist {
        font-size: 0.875rem;
        color: var(--color-text-secondary);
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .player-stats {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.75rem;
    }

    .listeners {
        color: var(--color-text-muted);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.625rem;
        letter-spacing: 0.05em;
    }

    .status-badge.live {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        animation: livePulse 2s ease-in-out infinite;
    }

    .status-badge.autodj {
        background: var(--color-bg-tertiary);
        color: var(--color-text-secondary);
    }

    @keyframes livePulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    /* Player Controls */
    .player-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        background: var(--color-bg-tertiary);
        color: var(--color-text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .control-btn:hover {
        background: var(--color-accent);
        color: white;
        transform: scale(1.05);
    }

    .play-btn {
        width: 56px;
        height: 56px;
        font-size: 1.25rem;
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-hover));
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .play-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
    }

    .play-btn.playing {
        animation: playingPulse 2s ease-in-out infinite;
    }

    @keyframes playingPulse {
        0%, 100% { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); }
        50% { box-shadow: 0 4px 20px var(--color-accent); }
    }

    /* Volume Control */
    .volume-control {
        position: relative;
    }

    .volume-slider {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 1rem 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        z-index: 10;
    }

    .volume-slider input[type="range"] {
        writing-mode: bt-lr;
        -webkit-appearance: slider-vertical;
        width: 8px;
        height: 100px;
        background: var(--color-bg-tertiary);
        border-radius: 4px;
        outline: none;
    }

    .volume-slider input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--color-accent);
        cursor: pointer;
    }

    .volume-slider input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--color-accent);
        cursor: pointer;
        border: none;
    }

    .volume-value {
        font-size: 0.75rem;
        color: var(--color-text-secondary);
        font-weight: 600;
    }

    /* Progress Bar */
    .player-progress {
        margin-top: 1rem;
    }

    .progress-bar {
        height: 4px;
        background: var(--color-bg-tertiary);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--color-accent), var(--color-accent-hover));
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .progress-times {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--color-text-muted);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .player-container {
            grid-template-columns: auto 1fr;
            gap: 1rem;
        }

        .player-controls {
            grid-column: 1 / -1;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .song-title {
            font-size: 1rem;
        }

        .song-artist {
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 480px) {
        .enhanced-audio-player {
            padding: 1rem;
        }

        .player-artwork {
            width: 60px;
            height: 60px;
        }

        .control-btn {
            width: 40px;
            height: 40px;
        }

        .play-btn {
            width: 48px;
            height: 48px;
        }
    }
</style>

<script>
    function audioPlayer(streamUrl) {
        return {
            audio: null,
            isPlaying: false,
            isMuted: false,
            volume: 70,
            nowPlaying: {
                title: '',
                artist: '',
                artwork: ''
            },
            progress: {
                elapsed: 0,
                duration: 0
            },
            listeners: 0,
            isLive: false,
            progressPercent: 0,

            init() {
                // Initialize audio element
                this.audio = new Audio(streamUrl);
                this.audio.volume = this.volume / 100;
                
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
                this.audio.play().catch(err => {
                    console.error('Playback failed:', err);
                    window.showToast?.('error', 'Failed to start playback');
                });
                this.isPlaying = true;
                localStorage.setItem('playerAutoplay', 'true');
            },

            pause() {
                this.audio.pause();
                this.isPlaying = false;
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
            }
        };
    }
</script>
