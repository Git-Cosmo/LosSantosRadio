<x-layouts.app>
    <x-slot:title>Request a Song</x-slot:title>

    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <div class="grid" style="grid-template-columns: 1fr 350px; gap: 1.5rem;">
        <!-- Song Library -->
        <div>
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <h2 class="card-title">
                        <i class="fas fa-music" style="color: var(--color-accent);"></i>
                        Song Library
                    </h2>
                    <form action="{{ route('requests.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                        <input type="text"
                               name="search"
                               placeholder="Search songs..."
                               value="{{ $search ?? '' }}"
                               class="form-input"
                               style="width: 250px;">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($songs->count() > 0)
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--color-border);">
                                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Song</th>
                                    <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Artist</th>
                                    <th style="padding: 0.75rem 1rem; text-align: right; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($songs as $song)
                                    <tr class="song-row"
                                        style="border-bottom: 1px solid var(--color-border-light);"
                                        data-song-id="{{ $song->id }}"
                                        data-song-title="{{ $song->title }}"
                                        data-song-artist="{{ $song->artist }}">
                                        <td style="padding: 0.75rem 1rem;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <img src="{{ $song->art ?? '' }}"
                                                     alt=""
                                                     style="width: 40px; height: 40px; border-radius: 4px; background-color: var(--color-bg-tertiary);"
                                                     onerror="this.style.display='none'">
                                                <span style="font-weight: 500;">{{ $song->title }}</span>
                                            </div>
                                        </td>
                                        <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary);">
                                            {{ $song->artist }}
                                        </td>
                                        <td style="padding: 0.75rem 1rem; text-align: right;">
                                            @if($canRequest['allowed'])
                                                <button class="btn btn-primary request-btn"
                                                        onclick="requestSong('{{ $song->id }}', '{{ addslashes($song->title) }}', '{{ addslashes($song->artist) }}')"
                                                        {{ !$canRequest['allowed'] ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i> Request
                                                </button>
                                            @else
                                                <span style="color: var(--color-text-muted); font-size: 0.875rem;">
                                                    <i class="fas fa-clock"></i> Limit reached
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            @if($search)
                                <p>No songs found matching "{{ $search }}"</p>
                            @else
                                <p>No songs available for request at this time.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Request Limits -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                        Request Status
                    </h3>
                </div>
                <div class="card-body">
                    @if($canRequest['allowed'])
                        <p style="color: var(--color-success); margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i> You can request songs!
                        </p>
                        @if(isset($canRequest['remaining']))
                            <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                                {{ $canRequest['remaining'] }} requests remaining
                            </p>
                        @endif
                    @else
                        <p style="color: var(--color-warning); margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-triangle"></i> Request limit reached
                        </p>
                        <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                            {{ $canRequest['reason'] }}
                        </p>
                    @endif

                    @guest
                        <hr style="border-color: var(--color-border); margin: 1rem 0;">
                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                            <a href="{{ route('login') }}">Sign in</a> for more requests and to track your history!
                        </p>
                    @endguest
                </div>
            </div>

            <!-- Request Queue -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list" style="color: var(--color-accent);"></i>
                        Request Queue
                    </h3>
                </div>
                <div class="card-body" style="padding: 0.5rem;">
                    @if($queue->count() > 0)
                        @foreach($queue->take(5) as $index => $song)
                            <div class="history-item">
                                <span style="color: var(--color-text-muted); width: 20px;">#{{ $index + 1 }}</span>
                                <div class="history-info">
                                    <p class="history-title">{{ $song->title }}</p>
                                    <p class="history-artist">{{ $song->artist }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                            No songs in queue
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function requestSong(songId, title, artist) {
            const btn = event.target.closest('button');
            btn.disabled = true;
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Requested';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-secondary');

                    // Show success message
                    showToast('success', data.message);
                } else {
                    btn.innerHTML = '<i class="fas fa-plus"></i> Request';
                    btn.disabled = false;
                    showToast('error', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = '<i class="fas fa-plus"></i> Request';
                btn.disabled = false;
                showToast('error', 'An error occurred. Please try again.');
            });
        }

        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-' + (type === 'success' ? 'success' : 'error');
            toast.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000; max-width: 300px; animation: slideIn 0.3s ease;';
            toast.innerHTML = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .song-row:hover {
            background-color: var(--color-bg-hover);
        }
    </style>
    @endpush
</x-layouts.app>
