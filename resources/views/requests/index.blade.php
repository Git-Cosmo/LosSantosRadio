<x-layouts.app>
    <x-slot:title>Request a Song</x-slot:title>

    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">
        <div class="page-header" style="margin-bottom: 2rem;">
            <h1 style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, #ffffff, var(--color-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 0.5rem;">
                <i class="fas fa-music" style="color: var(--color-accent); -webkit-text-fill-color: currentColor;"></i>
                Request a Song
            </h1>
            <p style="font-size: 1.125rem; color: var(--color-text-secondary);">
                Browse our library and request your favorite tracks
            </p>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 350px; gap: 2rem;">
            <!-- Song Library -->
            <div>
                <div class="card" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding: 1.5rem;">
                        <h2 class="card-title" style="margin: 0;">
                            Song Library
                            @if(isset($total) && $total > 0)
                                <span style="font-size: 0.875rem; color: var(--color-text-muted); font-weight: normal; margin-left: 0.5rem;">
                                    ({{ number_format($total) }} songs)
                                </span>
                            @endif
                        </h2>
                        <form action="{{ route('requests.index') }}" method="GET" style="display: flex; gap: 0.5rem; flex: 1; max-width: 400px;">
                            <input type="text"
                                   name="search"
                                   placeholder="Search by title or artist..."
                                   value="{{ $search ?? '' }}"
                                   class="form-input"
                                   style="flex: 1;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @if($songs->count() > 0)
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background: var(--color-bg-tertiary);">
                                    <tr style="border-bottom: 2px solid var(--color-border);">
                                        <th style="padding: 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Song</th>
                                        <th style="padding: 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Artist</th>
                                        <th style="padding: 1rem; text-align: right; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($songs as $song)
                                        <tr class="song-row"
                                            style="border-bottom: 1px solid var(--color-border); transition: all 0.2s ease;"
                                            data-song-id="{{ $song->id }}"
                                            data-song-title="{{ $song->title }}"
                                            data-song-artist="{{ $song->artist }}">
                                            <td style="padding: 1rem;">
                                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                    <div style="position: relative; flex-shrink: 0;">
                                                        <img src="{{ $song->art ?? '' }}"
                                                             alt="{{ $song->title }} by {{ $song->artist }}"
                                                             style="width: 48px; height: 48px; border-radius: 8px; background-color: var(--color-bg-tertiary); object-fit: cover; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div style="display: none; width: 48px; height: 48px; border-radius: 8px; background: linear-gradient(135deg, var(--color-accent), #a855f7); align-items: center; justify-content: center; font-size: 1.25rem;">
                                                            🎵
                                                        </div>
                                                    </div>
                                                    <span style="font-weight: 600; color: var(--color-text);">{{ $song->title }}</span>
                                                </div>
                                            </td>
                                            <td style="padding: 1rem; color: var(--color-text-secondary); font-weight: 500;">
                                                {{ $song->artist }}
                                            </td>
                                            <td style="padding: 1rem; text-align: right;">
                                                @if($canRequest['allowed'])
                                                    <button class="btn btn-primary request-btn"
                                                            onclick="requestSong({{ Js::from($song->id) }}, {{ Js::from($song->title) }}, {{ Js::from($song->artist) }})"
                                                            style="box-shadow: 0 2px 8px rgba(88, 166, 255, 0.3);">
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

                            <!-- Pagination -->
                            @if(isset($totalPages) && $totalPages > 1)
                                <div class="pagination-container" style="padding: 1.5rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem; border-top: 1px solid var(--color-border); background: var(--color-bg-tertiary);">
                                    @php
                                        $currentPage = $page ?? 1;
                                        $queryParams = $search ? ['search' => $search] : [];
                                    @endphp

                                    {{-- Previous button --}}
                                    @if($currentPage > 1)
                                        <a href="{{ route('requests.index', array_merge($queryParams, ['page' => $currentPage - 1])) }}"
                                           class="btn btn-secondary btn-sm">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </button>
                                    @endif

                                    {{-- Page info --}}
                                    <span style="padding: 0 1rem; color: var(--color-text-secondary); font-weight: 600;">
                                        Page {{ $currentPage }} of {{ $totalPages }}
                                    </span>

                                    {{-- Next button --}}
                                    @if($currentPage < $totalPages)
                                        <a href="{{ route('requests.index', array_merge($queryParams, ['page' => $currentPage + 1])) }}"
                                           class="btn btn-secondary btn-sm">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            Next <i class="fas fa-chevron-right"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div style="padding: 4rem; text-align: center; color: var(--color-text-muted);">
                                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                                @if($search)
                                    <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">No songs found matching "{{ $search }}"</p>
                                    <p style="font-size: 0.9375rem;">Try a different search term or browse all songs</p>
                                @else
                                    <p style="font-size: 1.125rem; font-weight: 600;">No songs available for request at this time.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Request Status -->
                <div class="card" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="padding: 1.5rem;">
                        <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                            Request Status
                        </h3>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @if($canRequest['allowed'])
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: linear-gradient(135deg, rgba(67, 181, 129, 0.1), rgba(67, 181, 129, 0.05)); border-radius: 8px; border: 1px solid rgba(67, 181, 129, 0.2); margin-bottom: 1rem;">
                                <i class="fas fa-check-circle" style="color: #43b581; font-size: 1.5rem;"></i>
                                <div>
                                    <p style="color: #43b581; margin-bottom: 0.25rem; font-weight: 700;">You can request songs!</p>
                                    @if(isset($canRequest['remaining']))
                                        <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin: 0;">
                                            {{ $canRequest['remaining'] }} requests remaining
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(251, 191, 36, 0.05)); border-radius: 8px; border: 1px solid rgba(251, 191, 36, 0.2); margin-bottom: 1rem;">
                                <i class="fas fa-exclamation-triangle" style="color: #fbbf24; font-size: 1.5rem;"></i>
                                <div>
                                    <p style="color: #fbbf24; margin-bottom: 0.25rem; font-weight: 700;">Request limit reached</p>
                                    <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin: 0;">
                                        {{ $canRequest['reason'] }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @guest
                            <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px; text-align: center;">
                                <i class="fas fa-user-plus" style="font-size: 2rem; color: var(--color-accent); margin-bottom: 0.5rem; display: block;"></i>
                                <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 0.75rem;">
                                    Sign in for more requests and to track your history!
                                </p>
                                <a href="{{ route('login') }}" class="btn btn-accent" style="width: 100%;">
                                    <i class="fas fa-sign-in-alt"></i> Sign In
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>

                <!-- Request Queue -->
                <div class="card" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="padding: 1.5rem;">
                        <h3 class="card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-list" style="color: var(--color-accent);"></i>
                            Request Queue
                        </h3>
                    </div>
                    <div class="card-body" style="padding: 0.75rem;">
                        @if($queue->count() > 0)
                            @foreach($queue->take(5) as $index => $song)
                                <div class="history-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border-radius: 8px; margin-bottom: 0.5rem; background: var(--color-bg-tertiary); transition: all 0.2s ease;">
                                    <span style="color: var(--color-accent); font-weight: 700; font-size: 1rem; min-width: 24px; text-align: center;">#{{ $index + 1 }}</span>
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="font-weight: 600; color: var(--color-text); margin-bottom: 0.125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</p>
                                        <p style="font-size: 0.8125rem; color: var(--color-text-secondary); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 2rem;">
                                <i class="fas fa-inbox" style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.5; margin-bottom: 0.5rem; display: block;"></i>
                                <p style="color: var(--color-text-muted); margin: 0;">
                                    No songs in queue
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function requestSong(songId, title, artist) {
            const btn = event.target.closest('button');
            if (!btn) return;
            
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Requesting...';

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
                    
                    // Reload page after 2 seconds to update queue
                    setTimeout(() => location.reload(), 2000);
                } else {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    showToast('error', data.error || 'Request failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                showToast('error', 'An error occurred. Please try again.');
            });
        }

        function showToast(type, message) {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'linear-gradient(135deg, #43b581, #38a169)' : 'linear-gradient(135deg, #f04747, #dc2626)';
            toast.style.cssText = `
                position: fixed; 
                bottom: 24px; 
                right: 24px; 
                z-index: 10000; 
                max-width: 400px; 
                padding: 1rem 1.5rem;
                background: ${bgColor};
                color: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                font-weight: 600;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            `;
            const icon = type === 'success' ? '<i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>' : '<i class="fas fa-exclamation-circle" style="font-size: 1.25rem;"></i>';
            toast.innerHTML = icon + '<span>' + message + '</span>';

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
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
            transform: translateX(4px);
        }
        .history-item:hover {
            background: var(--color-bg-hover) !important;
            transform: translateX(4px);
        }
        
        @media (max-width: 1024px) {
            .grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    @endpush
</x-layouts.app>
