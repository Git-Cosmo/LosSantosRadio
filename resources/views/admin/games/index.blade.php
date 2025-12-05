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
</x-admin.layouts.app>
