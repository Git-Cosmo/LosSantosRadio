<x-admin.layouts.app :title="'Games Management'">
    <div class="admin-header">
        <h1>Games Management</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $freeGamesCount }}</div>
            <div class="stat-label">Free Games</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $dealsCount }}</div>
            <div class="stat-label">Active Deals</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $storesCount }}</div>
            <div class="stat-label">Stores</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $gamesCount }}</div>
            <div class="stat-label">Games (IGDB)</div>
        </div>
    </div>

    <div class="grid grid-cols-2" style="gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('admin.games.free') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-gift"></i> Manage Free Games
                    </a>
                    <a href="{{ route('admin.games.deals') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-tags"></i> Manage Deals
                    </a>
                    <a href="{{ route('admin.games.stores') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-store"></i> View Stores
                    </a>
                    <hr style="border-color: var(--color-border);">
                    @if($igdbConfigured)
                    <div style="padding: 0.75rem; background: var(--color-success-bg); border-radius: 0.5rem; margin-bottom: 0.75rem;">
                        <p style="color: var(--color-success); font-size: 0.875rem; margin: 0;">
                            <i class="fas fa-check-circle"></i> IGDB API Configured
                        </p>
                    </div>
                    <button type="button" onclick="openIgdbSearch()" class="btn btn-primary" style="width: 100%; justify-content: flex-start;">
                        <i class="fas fa-search"></i> Search & Import from IGDB
                    </button>
                    @else
                    <div style="padding: 0.75rem; background: var(--color-warning-bg); border-radius: 0.5rem; margin-bottom: 0.75rem;">
                        <p style="color: var(--color-warning); font-size: 0.875rem; margin: 0;">
                            <i class="fas fa-exclamation-triangle"></i> IGDB API not configured. Set IGDB_CLIENT_ID and IGDB_CLIENT_SECRET in .env
                        </p>
                    </div>
                    @endif
                    <form action="{{ route('admin.games.sync-free') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: flex-start;">
                            <i class="fas fa-sync"></i> Sync Free Games from Reddit
                        </button>
                    </form>
                    <form action="{{ route('admin.games.sync-deals') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: flex-start;">
                            <i class="fas fa-sync"></i> Sync Deals from CheapShark
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Free Games</h2>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($recentGames->count() > 0)
                    <table class="table">
                        <tbody>
                            @foreach($recentGames as $game)
                                <tr>
                                    <td>{{ Str::limit($game->title, 40) }}</td>
                                    <td>
                                        @if($game->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-gray">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="padding: 1rem; color: var(--color-text-muted);">No free games yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- IGDB Search Modal -->
    <div id="igdbSearchModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: var(--color-bg-card); border-radius: 0.5rem; width: 90%; max-width: 600px; max-height: 80vh; overflow: auto; padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; color: var(--color-text);">Search IGDB</h3>
                <button onclick="closeIgdbSearch()" style="background: none; border: none; color: var(--color-text-muted); cursor: pointer; font-size: 1.5rem;">&times;</button>
            </div>
            <form id="igdbSearchForm" onsubmit="searchIgdb(event)">
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <input type="text" id="igdbQuery" placeholder="Search for a game..." style="flex: 1; padding: 0.5rem; background: var(--color-bg); border: 1px solid var(--color-border); border-radius: 0.25rem; color: var(--color-text);" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
            <div id="igdbResults"></div>
        </div>
    </div>

    <script>
        function openIgdbSearch() {
            document.getElementById('igdbSearchModal').style.display = 'flex';
        }

        function closeIgdbSearch() {
            document.getElementById('igdbSearchModal').style.display = 'none';
            document.getElementById('igdbResults').innerHTML = '';
            document.getElementById('igdbQuery').value = '';
        }

        function searchIgdb(event) {
            event.preventDefault();
            const query = document.getElementById('igdbQuery').value;
            const resultsDiv = document.getElementById('igdbResults');
            
            resultsDiv.innerHTML = '<p style="color: var(--color-text-muted);">Searching...</p>';

            fetch('{{ route('admin.games.igdb.search') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ query: query })
            })
            .then(response => response.json())
            .then(data => {
                // HTML escape helper
                const escapeHtml = (text) => {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                };
                
                if (data.error) {
                    resultsDiv.innerHTML = `<p style="color: var(--color-danger);">${escapeHtml(data.error)}</p>`;
                    return;
                }

                if (!data.results || data.results.length === 0) {
                    resultsDiv.innerHTML = '<p style="color: var(--color-text-muted);">No results found.</p>';
                    return;
                }

                let html = '<div style="display: flex; flex-direction: column; gap: 0.75rem;">';
                data.results.forEach(game => {
                    const coverUrl = game.cover?.url ? 'https:' + game.cover.url.replace('t_thumb', 't_cover_small') : '';
                    const rating = game.rating ? Math.round(game.rating) : null;
                    
                    html += `
                        <div style="display: flex; gap: 1rem; padding: 0.75rem; background: var(--color-bg); border-radius: 0.5rem; align-items: start;">
                            ${coverUrl ? `<img src="${escapeHtml(coverUrl)}" alt="${escapeHtml(game.name)}" style="width: 60px; height: 80px; object-fit: cover; border-radius: 0.25rem;">` : ''}
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 0.5rem 0; color: var(--color-text);">${escapeHtml(game.name)}</h4>
                                ${game.summary ? `<p style="font-size: 0.875rem; color: var(--color-text-muted); margin: 0 0 0.5rem 0;">${escapeHtml(game.summary.substring(0, 100))}...</p>` : ''}
                                ${rating ? `<span style="color: var(--color-warning);">⭐ ${rating}</span>` : ''}
                            </div>
                            <form action="{{ route('admin.games.igdb.import') }}" method="POST">
                                @csrf
                                <input type="hidden" name="igdb_id" value="${game.id}">
                                <button type="submit" class="btn btn-sm btn-primary">Import</button>
                            </form>
                        </div>
                    `;
                });
                html += '</div>';
                resultsDiv.innerHTML = html;
            })
            .catch(error => {
                const escapeHtml = (text) => {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                };
                resultsDiv.innerHTML = `<p style="color: var(--color-danger);">Error: ${escapeHtml(error.message)}</p>`;
            });
        }
    </script>
</x-admin.layouts.app>
