<x-layouts.app :title="'Songs'">
    @if($error)
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <!-- Search Section -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-compact-disc" style="color: var(--color-accent);"></i>
                Song Library
            </h1>
        </div>
        <div class="card-body">
            <form action="{{ route('songs') }}" method="GET" style="display: flex; gap: 1rem;">
                <input type="text"
                       name="search"
                       class="form-input"
                       placeholder="Search songs, artists, or albums..."
                       value="{{ $search }}"
                       style="flex: 1;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if($search)
                    <a href="{{ route('songs') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if($search && $searchResults->count() > 0)
        <!-- Search Results -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-search" style="color: var(--color-accent);"></i>
                    Search Results for "{{ $search }}"
                </h2>
            </div>
            <div class="card-body" style="padding: 0.5rem;">
                @foreach($searchResults as $song)
                    <div class="history-item">
                        <img src="{{ $song->art ?? '' }}"
                             alt=""
                             class="history-art"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                        <div class="history-info">
                            <p class="history-title">{{ $song->title }}</p>
                            <p class="history-artist">{{ $song->artist }}</p>
                        </div>
                        @if($song->album)
                            <span style="color: var(--color-text-muted); font-size: 0.8125rem;">
                                {{ $song->album }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($search)
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body" style="text-align: center; padding: 2rem;">
                <i class="fas fa-search" style="font-size: 2rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                <p style="color: var(--color-text-muted);">No songs found for "{{ $search }}"</p>
            </div>
        </div>
    @endif

    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Now Playing -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-play-circle" style="color: var(--color-accent);"></i>
                    Now Playing
                </h2>
            </div>
            <div class="card-body">
                @if($nowPlaying && $nowPlaying->currentSong)
                    <div style="display: flex; gap: 1.25rem; align-items: center;">
                        <img src="{{ $nowPlaying->currentSong->art ?? '' }}"
                             alt="Album Art"
                             style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; background: var(--color-bg-tertiary);"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                        <div>
                            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.25rem;">
                                {{ $nowPlaying->currentSong->title }}
                            </h3>
                            <p style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">
                                {{ $nowPlaying->currentSong->artist }}
                            </p>
                            @if($nowPlaying->currentSong->album)
                                <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                                    <i class="fas fa-compact-disc"></i> {{ $nowPlaying->currentSong->album }}
                                </p>
                            @endif
                            <div style="margin-top: 0.75rem;">
                                <span class="badge badge-live">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i> LIVE
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($nowPlaying->nextSong)
                        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                            <p style="color: var(--color-text-muted); font-size: 0.75rem; margin-bottom: 0.5rem;">UP NEXT</p>
                            <div style="display: flex; gap: 0.75rem; align-items: center;">
                                <img src="{{ $nowPlaying->nextSong->art ?? '' }}"
                                     alt=""
                                     style="width: 48px; height: 48px; border-radius: 6px; background: var(--color-bg-tertiary);"
                                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                                <div>
                                    <p style="font-size: 0.875rem; font-weight: 500;">{{ $nowPlaying->nextSong->title }}</p>
                                    <p style="font-size: 0.8125rem; color: var(--color-text-secondary);">{{ $nowPlaying->nextSong->artist }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <p style="color: var(--color-text-muted); text-align: center; padding: 2rem;">
                        No song currently playing.
                    </p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-bolt" style="color: #f59e0b;"></i>
                    Quick Actions
                </h2>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 1rem;">
                    <a href="{{ route('home') }}" class="btn btn-primary" style="justify-content: flex-start;">
                        <i class="fas fa-play"></i> Listen Live
                    </a>
                    <a href="{{ route('requests.index') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-music"></i> Request a Song
                    </a>
                    <a href="{{ route('schedule') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-calendar-alt"></i> View Schedule
                    </a>
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                    <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                        <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                        Can't find your favorite song? Use the search above or request it to be added to our library!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Played -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-history" style="color: var(--color-accent);"></i>
                Recently Played
            </h2>
        </div>
        <div class="card-body">
            @if($history->count() > 0)
                <div class="songs-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    @foreach($history as $item)
                        <div class="history-item" style="background: var(--color-bg-tertiary); border-radius: 8px; padding: 0.75rem;">
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
                </div>
            @else
                <p style="color: var(--color-text-muted); text-align: center; padding: 2rem;">
                    No recently played songs available.
                </p>
            @endif
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</x-layouts.app>
