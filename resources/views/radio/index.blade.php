<x-layouts.app>
    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

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
        </div>
    </div>

    @push('scripts')
    <script>
        let audioPlayer = null;
        let isPlaying = false;

        function togglePlayback() {
            const btn = document.getElementById('play-btn');
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
                isPlaying = false;
            } else {
                audioPlayer.play();
                btn.innerHTML = '<i class="fas fa-pause"></i> Stop Listening';
                isPlaying = true;
            }
        }

        // Update progress bar and song info
        document.addEventListener('nowPlayingUpdate', function(e) {
            const data = e.detail;

            // Update song info
            document.getElementById('song-title').textContent = data.current_song.title;
            document.getElementById('song-artist').textContent = data.current_song.artist;

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
    </script>
    @endpush
</x-layouts.app>
