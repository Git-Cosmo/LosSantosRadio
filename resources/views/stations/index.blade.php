<x-layouts.app :title="'Stations'">
    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-broadcast-tower" style="color: var(--color-accent);"></i>
                Radio Stations
            </h1>
            <span class="listeners-count" id="total-listeners">
                <i class="fas fa-headphones"></i>
                <span id="listener-count">{{ $stations->sum('listeners') }}</span> total listeners
            </span>
        </div>
        <div class="card-body">
            @if($stations->count() > 0)
                <div class="stations-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem;">
                    @foreach($stations as $station)
                        <div class="station-card" style="background: var(--color-bg-tertiary); border-radius: 12px; overflow: hidden; border: 1px solid {{ $station->isOnline ? 'var(--color-accent)' : 'var(--color-border)' }}; transition: all 0.3s ease;">
                            <!-- Station Header -->
                            <div style="background: linear-gradient(135deg, var(--color-bg-secondary) 0%, rgba(88, 166, 255, 0.1) 100%); padding: 1rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border);">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-radio" style="color: white; font-size: 1.25rem;"></i>
                                    </div>
                                    <div>
                                        <h3 style="font-weight: 600; font-size: 1.125rem; margin-bottom: 0.125rem;">
                                            {{ $station->currentSong->title ?? 'Unknown' }}
                                        </h3>
                                        <p style="color: var(--color-text-muted); font-size: 0.8125rem;">
                                            Station
                                        </p>
                                    </div>
                                </div>
                                @if($station->isOnline)
                                    <span class="badge badge-live pulse-animation">
                                        <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                                        LIVE
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                                        OFFLINE
                                    </span>
                                @endif
                            </div>

                            <!-- Now Playing Section -->
                            <div style="padding: 1rem;">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <img src="{{ $station->currentSong->art ?? '' }}"
                                         alt="Album Art"
                                         style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover; background: var(--color-bg-secondary); flex-shrink: 0;"
                                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="color: var(--color-text-muted); font-size: 0.75rem; margin-bottom: 0.25rem;">
                                            <i class="fas fa-play-circle" style="margin-right: 0.25rem;"></i>
                                            NOW PLAYING
                                        </p>
                                        <h4 style="font-weight: 600; font-size: 0.9375rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $station->currentSong->title ?? 'Unknown Track' }}
                                        </h4>
                                        <p style="color: var(--color-text-secondary); font-size: 0.8125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $station->currentSong->artist ?? 'Unknown Artist' }}
                                        </p>

                                        <!-- Progress Bar -->
                                        @if($station->duration > 0)
                                            <div class="progress-bar" style="margin-top: 0.75rem; height: 4px;">
                                                <div class="progress-fill" style="width: {{ $station->progressPercentage() }}%;"></div>
                                            </div>
                                            <div class="time-info" style="margin-top: 0.25rem; font-size: 0.6875rem;">
                                                <span>{{ gmdate('i:s', $station->elapsed) }}</span>
                                                <span>{{ gmdate('i:s', $station->duration) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Station Info Footer -->
                            <div style="padding: 0.75rem 1rem; background: var(--color-bg-secondary); border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 1rem; color: var(--color-text-muted); font-size: 0.8125rem;">
                                    <span>
                                        <i class="fas fa-headphones"></i> {{ $station->listeners }} listeners
                                    </span>
                                    <span>
                                        <i class="fas fa-{{ $station->isLive ? 'microphone' : 'robot' }}"></i>
                                        {{ $station->isLive ? ($station->streamerName ?? 'Live DJ') : 'AutoDJ' }}
                                    </span>
                                </div>
                                @if($station->isOnline)
                                    <button class="btn btn-primary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;" onclick="playStation({{ $station->currentSong->id ?? 0 }})">
                                        <i class="fas fa-play"></i> Listen
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 3rem 2rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem; color: var(--color-text-muted);">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">
                        No Stations Available
                    </h3>
                    <p style="color: var(--color-text-secondary); max-width: 400px; margin: 0 auto;">
                        There are currently no stations broadcasting. Please check back later.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Station Info Card -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                About Our Network
            </h2>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">
                Welcome to the Los Santos Radio network! We offer multiple stations playing different genres of music around the clock.
                Click on any station to start listening, or use the links below to explore more.
            </p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Main Station
                </a>
                <a href="{{ route('schedule') }}" class="btn btn-secondary">
                    <i class="fas fa-calendar-alt"></i> View Schedule
                </a>
                <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                    <i class="fas fa-music"></i> Request a Song
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh station data every 30 seconds
        function refreshStations() {
            fetch('/api/stations/now-playing')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const totalListeners = data.data.reduce((sum, station) => sum + (station.listeners || 0), 0);
                        const listenerCount = document.getElementById('listener-count');
                        if (listenerCount) {
                            listenerCount.textContent = totalListeners;
                        }
                    }
                })
                .catch(console.error);
        }

        // Refresh every 30 seconds
        setInterval(refreshStations, 30000);

        function playStation(stationId) {
            // This could be extended to play specific stations
            // For now, redirect to home page
            window.location.href = '{{ route('home') }}';
        }
    </script>
    <style>
        .station-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .stations-grid {
                grid-template-columns: 1fr !important;
            }

            .station-card:hover {
                transform: none;
            }
        }
    </style>
    @endpush
</x-layouts.app>
