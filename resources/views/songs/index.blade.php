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
            <form action="{{ route('songs') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="text"
                       name="search"
                       class="form-input"
                       placeholder="Search songs, artists, or albums..."
                       value="{{ $search }}"
                       style="flex: 1; min-width: 200px;">
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
        <!-- Search Results with Request Functionality -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <h2 class="card-title">
                    <i class="fas fa-search" style="color: var(--color-accent);"></i>
                    Search Results for "{{ $search }}"
                </h2>
                <span style="color: var(--color-text-muted); font-size: 0.875rem;">
                    {{ $searchResults->count() }} songs found
                </span>
            </div>
            <div class="card-body" style="padding: 0;">
                @php $firstResult = $searchResults->first(); @endphp
                <div class="song-table-wrapper" style="overflow-x: auto;">
                    <table class="song-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--color-border); background: var(--color-bg-tertiary);">
                                <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Song</th>
                                <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Artist</th>
                                <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Album</th>
                                @if($firstResult && $firstResult->genre)
                                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Genre</th>
                                @endif
                                <th style="padding: 0.75rem 1rem; text-align: right; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($searchResults as $song)
                                <tr class="song-row" style="border-bottom: 1px solid var(--color-border-light);">
                                    <td style="padding: 0.75rem 1rem;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <img src="{{ $song->art ?? '' }}"
                                                 alt="{{ $song->title }}"
                                                 style="width: 48px; height: 48px; border-radius: 6px; background-color: var(--color-bg-tertiary); object-fit: cover; flex-shrink: 0;"
                                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                                            <div style="min-width: 0;">
                                                <p style="font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</p>
                                                @if($song->duration)
                                                    <p style="font-size: 0.75rem; color: var(--color-text-muted);">
                                                        <i class="fas fa-clock"></i> {{ gmdate('i:s', $song->duration) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary);">
                                        {{ $song->artist }}
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: var(--color-text-muted);">
                                        {{ $song->album ?? '-' }}
                                    </td>
                                    @if($firstResult && $firstResult->genre)
                                        <td style="padding: 0.75rem 1rem;">
                                            @if($song->genre)
                                                <span style="display: inline-block; padding: 0.25rem 0.5rem; background: var(--color-bg-tertiary); border-radius: 4px; font-size: 0.75rem; color: var(--color-text-secondary);">
                                                    {{ $song->genre }}
                                                </span>
                                            @else
                                                <span style="color: var(--color-text-muted);">-</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td style="padding: 0.75rem 1rem; text-align: right;">
                                        <button class="btn btn-primary request-btn"
                                                onclick="requestSong({{ Js::from($song->id) }}, {{ Js::from($song->title) }}, {{ Js::from($song->artist) }}, this)"
                                                aria-label="Request {{ $song->title }} by {{ $song->artist }}"
                                                style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">
                                            <i class="fas fa-plus"></i> Request
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($search)
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-body" style="text-align: center; padding: 3rem;">
                <i class="fas fa-search" style="font-size: 3rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">No songs found</h3>
                <p style="color: var(--color-text-muted); margin-bottom: 1.5rem;">No songs found for "{{ $search }}"</p>
                <a href="{{ route('requests.index') }}" class="btn btn-primary">
                    <i class="fas fa-music"></i> Request a Song Instead
                </a>
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
                             style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; background: var(--color-bg-tertiary); flex-shrink: 0;"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                        <div style="min-width: 0;">
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
                            <p style="color: var(--color-text-muted); font-size: 0.75rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-forward"></i> UP NEXT
                            </p>
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
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="card-title">
                <i class="fas fa-history" style="color: var(--color-accent);"></i>
                Recently Played
            </h2>
            <span style="color: var(--color-text-muted); font-size: 0.875rem;">
                Last {{ $history->count() }} songs
            </span>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($history->count() > 0)
                <div class="song-table-wrapper" style="overflow-x: auto;">
                    <table class="song-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--color-border); background: var(--color-bg-tertiary);">
                                <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Song</th>
                                <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Artist</th>
                                <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Album</th>
                                <th style="padding: 0.75rem 1rem; text-align: right; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Played</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $item)
                                <tr class="song-row" style="border-bottom: 1px solid var(--color-border-light);">
                                    <td style="padding: 0.75rem 1rem;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <img src="{{ $item->song->art ?? '' }}"
                                                 alt="{{ $item->song->title }}"
                                                 style="width: 40px; height: 40px; border-radius: 6px; background-color: var(--color-bg-tertiary); object-fit: cover; flex-shrink: 0;"
                                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                                            <span style="font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->song->title }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary);">
                                        {{ $item->song->artist }}
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: var(--color-text-muted);">
                                        {{ $item->song->album ?? '-' }}
                                    </td>
                                    <td style="padding: 0.75rem 1rem; text-align: right; color: var(--color-text-muted); font-size: 0.8125rem;">
                                        <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                                        {{ $item->playedAt->diffForHumans(null, true, true) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="color: var(--color-text-muted); text-align: center; padding: 2rem;">
                    No recently played songs available.
                </p>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        /**
         * Request a song via the AzuraCast API.
         * 
         * @param {number|string} songId - The song ID to request
         * @param {string} title - The song title (for display)
         * @param {string} artist - The song artist (for display)
         * @param {HTMLElement} btn - The button element that was clicked
         */
        function requestSong(songId, title, artist, btn) {
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('{{ route('requests.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    song_id: songId,
                    song_title: title,
                    song_artist: artist
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed with status ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Requested';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-secondary');
                    showToast('success', data.message || 'Song request submitted!');
                } else {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    showToast('error', data.error || 'Failed to request song');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                showToast('error', 'An error occurred. Please try again.');
            });
        }
    </script>
    @endpush

    <style>
        .song-row {
            transition: background-color 0.2s ease;
        }

        .song-row:hover {
            background-color: var(--color-bg-hover);
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr !important;
            }

            .song-table th:nth-child(3),
            .song-table td:nth-child(3),
            .song-table th:nth-child(4),
            .song-table td:nth-child(4) {
                display: none;
            }
        }
    </style>
</x-layouts.app>
