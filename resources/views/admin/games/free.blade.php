<x-admin.layouts.app :title="'Free Games'">
    <div class="admin-header">
        <h1>Free Games</h1>
        <div class="header-actions">
            <form action="{{ route('admin.games.sync-free') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Sync from Reddit
                </button>
            </form>
            <a href="{{ route('admin.games.free.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Game
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($games->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Platform</th>
                            <th>Store</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                            <tr>
                                <td>
                                    <a href="{{ $game->url }}" target="_blank" rel="noopener">
                                        {{ Str::limit($game->title, 50) }}
                                    </a>
                                </td>
                                <td>{{ $game->platform ?? '-' }}</td>
                                <td>{{ $game->store ?? '-' }}</td>
                                <td><span class="badge badge-gray">{{ $game->source }}</span></td>
                                <td>
                                    @if($game->is_active && !$game->hasExpired())
                                        <span class="badge badge-success">Active</span>
                                    @elseif($game->hasExpired())
                                        <span class="badge badge-danger">Expired</span>
                                    @else
                                        <span class="badge badge-gray">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.25rem;">
                                        <a href="{{ route('admin.games.free.edit', $game) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.games.free.destroy', $game) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="padding: 2rem; text-align: center; color: var(--color-text-muted);">No free games yet. Click "Sync from Reddit" or "Add Game" to get started.</p>
            @endif
        </div>
    </div>

    <div style="margin-top: 1rem;">
        {{ $games->links() }}
    </div>
</x-admin.layouts.app>
