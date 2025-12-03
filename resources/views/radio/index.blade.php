<x-layouts.app>
    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-brand">
                <div class="hero-logo">
                    <i class="fas fa-radio"></i>
                </div>
                <h1 class="hero-title">Los Santos Radio</h1>
                <p class="hero-tagline">Your 24/7 source for the best music from Los Santos and beyond</p>
            </div>
            <div class="hero-actions">
                <button id="hero-play-btn" class="btn btn-primary btn-lg" onclick="togglePlayback()">
                    <i class="fas fa-play"></i> Listen Live
                </button>
                <a href="{{ route('requests.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-music"></i> Request a Song
                </a>
            </div>
            @if(isset($streamStatus) && $streamStatus['is_online'])
                <div class="hero-status">
                    <span class="badge badge-live pulse-animation">
                        <i class="fas fa-circle"></i> LIVE NOW
                    </span>
                    <span class="hero-listeners">
                        <i class="fas fa-headphones"></i>
                        {{ $nowPlaying?->listeners ?? 0 }} listeners tuned in
                    </span>
                </div>
            @endif
        </div>
    </section>

    <div class="grid grid-cols-3" style="grid-template-columns: 2fr 1fr;">
        <!-- Main Content -->
        <div>
            <!-- Now Playing Card -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="card-title">
                        <i class="fas fa-play-circle" style="color: var(--color-accent);"></i>
                        Now Playing
                    </h2>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        @if(isset($streamStatus) && $streamStatus['is_online'])
                            <span class="badge badge-live">
                                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                                LIVE
                            </span>
                        @endif
                        <span class="listeners-count">
                            <i class="fas fa-headphones"></i>
                            {{ $nowPlaying?->listeners ?? 0 }} listeners
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($nowPlaying)
                        <div class="now-playing" id="now-playing">
                            <img src="{{ $nowPlaying->currentSong->art ?? '/images/default-album.png' }}"
                                 alt="Album Art"
                                 class="now-playing-art"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                            <div class="now-playing-info">
                                <h3 class="now-playing-title" id="song-title">{{ $nowPlaying->currentSong->title }}</h3>
                                <p class="now-playing-artist" id="song-artist">{{ $nowPlaying->currentSong->artist }}</p>
                                @if($nowPlaying->currentSong->album)
                                    <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                                        {{ $nowPlaying->currentSong->album }}
                                    </p>
                                @endif

                                <!-- Song Rating UI -->
                                <div class="song-rating" id="song-rating"
                                     data-song-id="{{ $nowPlaying->currentSong->id }}"
                                     data-song-title="{{ $nowPlaying->currentSong->title }}"
                                     data-song-artist="{{ $nowPlaying->currentSong->artist }}">
                                    <button class="rating-btn upvote" onclick="rateSong(1)" title="Like this song">
                                        <i class="fas fa-thumbs-up"></i>
                                        <span id="upvote-count">0</span>
                                    </button>
                                    <button class="rating-btn downvote" onclick="rateSong(-1)" title="Dislike this song">
                                        <i class="fas fa-thumbs-down"></i>
                                        <span id="downvote-count">0</span>
                                    </button>
                                </div>

                                <div class="progress-bar">
                                    <div class="progress-fill" id="progress-fill" style="width: {{ $nowPlaying->progressPercentage() }}%;"></div>
                                </div>
                                <div class="time-info">
                                    <span id="elapsed-time">{{ gmdate('i:s', $nowPlaying->elapsed) }}</span>
                                    <span id="total-time">{{ gmdate('i:s', $nowPlaying->duration) }}</span>
                                </div>

                                @if($nowPlaying->nextSong)
                                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                                        <p style="color: var(--color-text-muted); font-size: 0.75rem; margin-bottom: 0.25rem;">UP NEXT</p>
                                        <p style="font-size: 0.875rem;">
                                            <strong>{{ $nowPlaying->nextSong->title }}</strong>
                                            <span style="color: var(--color-text-secondary);">by {{ $nowPlaying->nextSong->artist }}</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Audio Player Controls -->
                        <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                            <button id="play-btn" class="btn btn-primary" onclick="togglePlayback()">
                                <i class="fas fa-play"></i> Listen Live
                            </button>
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-music"></i> Request a Song
                            </a>
                        </div>
                    @else
                        <p style="color: var(--color-text-muted); text-align: center; padding: 2rem;">
                            <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>
                            Loading now playing information...
                        </p>
                    @endif
                </div>
            </div>

            <!-- Schedule Display -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                        Today's Schedule
                    </h2>
                </div>
                <div class="card-body">
                    <div class="schedule-list" id="schedule-list">
                        <div class="schedule-item active">
                            <div class="schedule-time">
                                <span class="schedule-hour">Now</span>
                            </div>
                            <div class="schedule-info">
                                <h4 class="schedule-title">{{ $nowPlaying?->isLive ? 'Live Show' : 'AutoDJ' }}</h4>
                                <p class="schedule-desc">Currently broadcasting</p>
                            </div>
                            <span class="badge badge-live">ON AIR</span>
                        </div>
                        <div class="schedule-fallback" id="schedule-fallback">
                            <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                                Schedule data unavailable. AutoDJ is playing your favorite tracks 24/7!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trending Songs -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-fire" style="color: #f97316;"></i>
                        Trending Songs
                    </h2>
                </div>
                <div class="card-body">
                    <div id="trending-songs" class="trending-list">
                        <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                            <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>
                            Loading trending songs...
                        </p>
                    </div>
                </div>
            </div>

            <!-- DJ Profiles Section -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-users" style="color: var(--color-accent);"></i>
                        Our DJs
                    </h2>
                </div>
                <div class="card-body">
                    <div class="dj-profiles">
                        <div class="dj-profile">
                            <div class="dj-avatar">
                                <i class="fas fa-headphones-alt"></i>
                            </div>
                            <div class="dj-info">
                                <h4 class="dj-name">AutoDJ</h4>
                                <p class="dj-bio">Your 24/7 music companion. Always playing the best hits from Los Santos!</p>
                            </div>
                        </div>
                        <p class="dj-cta" style="color: var(--color-text-muted); text-align: center; padding: 1rem; margin-top: 1rem; border-top: 1px solid var(--color-border);">
                            <i class="fas fa-microphone" style="margin-right: 0.5rem;"></i>
                            Want to become a DJ? Join our Discord to learn more!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Station Info -->
            @if(isset($station))
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                            About {{ $station->name }}
                        </h2>
                    </div>
                    <div class="card-body">
                        @if($station->description)
                            <p style="color: var(--color-text-secondary);">{{ $station->description }}</p>
                        @else
                            <p style="color: var(--color-text-secondary);">
                                Welcome to Los Santos Radio! Your 24/7 source for the best music. Tune in and enjoy!
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Song History -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-history" style="color: var(--color-accent);"></i>
                        Recently Played
                    </h2>
                </div>
                <div class="card-body" style="padding: 0.5rem;">
                    @if($history->count() > 0)
                        @foreach($history->take(8) as $item)
                            <div class="history-item">
                                <img src="{{ $item->song->art ?? '' }}"
                                     alt=""
                                     class="history-art"
                                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                                <div class="history-info">
                                    <p class="history-title">{{ $item->song->title }}</p>
                                    <p class="history-artist">{{ $item->song->artist }}</p>
                                </div>
                                <span class="history-time">{{ $item->playedAt->diffForHumans(null, true, true) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                            No recent history available.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-chart-bar" style="color: var(--color-accent);"></i>
                        Quick Stats
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: grid; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--color-text-secondary);">Current Listeners</span>
                            <span style="font-weight: 600;">{{ $nowPlaying?->listeners ?? 0 }}</span>
                        </div>
                        @if(isset($streamStatus))
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--color-text-secondary);">Peak Listeners</span>
                                <span style="font-weight: 600;">{{ $streamStatus['peak_listeners'] ?? 0 }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--color-text-secondary);">Stream Status</span>
                                <span class="badge {{ $streamStatus['is_online'] ? 'badge-success' : 'badge-warning' }}">
                                    {{ $streamStatus['is_online'] ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- News & Events Section -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-newspaper" style="color: var(--color-accent);"></i>
                        News & Events
                    </h2>
                </div>
                <div class="card-body">
                    <div class="news-list">
                        <div class="news-item">
                            <div class="news-date">
                                <i class="fas fa-bullhorn" style="color: var(--color-accent);"></i>
                            </div>
                            <div class="news-content">
                                <h4 class="news-title">Welcome to Los Santos Radio!</h4>
                                <p class="news-desc">We're broadcasting 24/7 with the best music. Request your favorite songs anytime!</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <div class="news-date">
                                <i class="fas fa-star" style="color: #f59e0b;"></i>
                            </div>
                            <div class="news-content">
                                <h4 class="news-title">New Song Rating Feature</h4>
                                <p class="news-desc">Like or dislike songs to help us curate the best playlist for you!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discord Widget Section -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fab fa-discord" style="color: #5865F2;"></i>
                        Join Our Community
                    </h2>
                </div>
                <div class="card-body">
                    <div class="discord-widget">
                        <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">
                            Join our Discord server to chat with other listeners, get updates, and participate in events!
                        </p>
                        <a href="#" class="btn btn-discord" style="width: 100%; justify-content: center;">
                            <i class="fab fa-discord"></i> Join Discord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let audioPlayer = null;
        let isPlaying = false;

        function togglePlayback() {
            const btn = document.getElementById('play-btn');
            const heroBtn = document.getElementById('hero-play-btn');
            const streamUrl = '{{ $streamUrl ?? '' }}';

            if (!streamUrl) {
                alert('Stream URL not available');
                return;
            }

            if (!audioPlayer) {
                audioPlayer = new Audio(streamUrl);
            }

            if (isPlaying) {
                audioPlayer.pause();
                btn.innerHTML = '<i class="fas fa-play"></i> Listen Live';
                if (heroBtn) heroBtn.innerHTML = '<i class="fas fa-play"></i> Listen Live';
                isPlaying = false;
            } else {
                audioPlayer.play();
                btn.innerHTML = '<i class="fas fa-pause"></i> Stop Listening';
                if (heroBtn) heroBtn.innerHTML = '<i class="fas fa-pause"></i> Stop Listening';
                isPlaying = true;
            }
        }

        // Song rating functionality
        function rateSong(rating) {
            const ratingEl = document.getElementById('song-rating');
            const songId = ratingEl.dataset.songId;
            const songTitle = ratingEl.dataset.songTitle;
            const songArtist = ratingEl.dataset.songArtist;

            fetch('/api/ratings/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    song_id: songId,
                    song_title: songTitle,
                    song_artist: songArtist,
                    rating: rating
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('upvote-count').textContent = data.data.upvotes;
                    document.getElementById('downvote-count').textContent = data.data.downvotes;

                    // Update button states
                    const upvoteBtn = document.querySelector('.rating-btn.upvote');
                    const downvoteBtn = document.querySelector('.rating-btn.downvote');

                    upvoteBtn.classList.remove('active');
                    downvoteBtn.classList.remove('active');

                    if (data.action !== 'removed') {
                        if (rating === 1) upvoteBtn.classList.add('active');
                        if (rating === -1) downvoteBtn.classList.add('active');
                    }
                }
            })
            .catch(console.error);
        }

        // Load rating data for current song
        function loadSongRating() {
            const ratingEl = document.getElementById('song-rating');
            if (!ratingEl) return;

            const songId = ratingEl.dataset.songId;
            if (!songId) return;

            fetch(`/api/ratings/song/${encodeURIComponent(songId)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('upvote-count').textContent = data.data.upvotes;
                        document.getElementById('downvote-count').textContent = data.data.downvotes;

                        if (data.data.user_rating === 1) {
                            document.querySelector('.rating-btn.upvote').classList.add('active');
                        } else if (data.data.user_rating === -1) {
                            document.querySelector('.rating-btn.downvote').classList.add('active');
                        }
                    }
                })
                .catch(console.error);
        }

        // Load trending songs
        function loadTrendingSongs() {
            fetch('/api/ratings/trending')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('trending-songs');
                    if (data.success && data.data.length > 0) {
                        container.innerHTML = data.data.map((song, index) => `
                            <div class="trending-item">
                                <span class="trending-rank">#${index + 1}</span>
                                <div class="trending-info">
                                    <p class="trending-title">${song.song_title}</p>
                                    <p class="trending-artist">${song.song_artist}</p>
                                </div>
                                <span class="trending-score">
                                    <i class="fas fa-heart" style="color: #ef4444;"></i>
                                    ${song.score}
                                </span>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">No trending songs yet. Rate songs to see them here!</p>';
                    }
                })
                .catch(() => {
                    document.getElementById('trending-songs').innerHTML = '<p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">Unable to load trending songs.</p>';
                });
        }

        // Update progress bar and song info
        document.addEventListener('nowPlayingUpdate', function(e) {
            const data = e.detail;

            // Update song info
            document.getElementById('song-title').textContent = data.current_song.title;
            document.getElementById('song-artist').textContent = data.current_song.artist;

            // Update rating data attributes and reload
            const ratingEl = document.getElementById('song-rating');
            if (ratingEl && ratingEl.dataset.songId !== data.current_song.id) {
                ratingEl.dataset.songId = data.current_song.id;
                ratingEl.dataset.songTitle = data.current_song.title;
                ratingEl.dataset.songArtist = data.current_song.artist;
                loadSongRating();
            }

            // Update progress
            const progress = data.duration > 0 ? (data.elapsed / data.duration) * 100 : 0;
            document.getElementById('progress-fill').style.width = progress + '%';

            // Update times
            document.getElementById('elapsed-time').textContent = formatTime(data.elapsed);
            document.getElementById('total-time').textContent = formatTime(data.duration);
        });

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSongRating();
            loadTrendingSongs();
        });
    </script>
    @endpush
</x-layouts.app>
