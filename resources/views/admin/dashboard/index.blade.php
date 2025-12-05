<x-admin.layouts.app title="Dashboard">
    <div class="admin-header">
        <h1>Dashboard</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['total_requests']) }}</div>
            <div class="stat-label">Total Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['pending_requests']) }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['played_requests']) }}</div>
            <div class="stat-label">Played Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['total_news']) }}</div>
            <div class="stat-label">Total News Articles</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['published_news']) }}</div>
            <div class="stat-label">Published Articles</div>
        </div>
    </div>

    <div class="grid grid-cols-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Song Requests</h3>
                <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-secondary">View All</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Song</th>
                            <th>Requester</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>
                                    <div>{{ Str::limit($request->song_title, 30) }}</div>
                                    <small style="color: var(--color-text-muted);">{{ Str::limit($request->song_artist, 25) }}</small>
                                </td>
                                <td>{{ $request->user?->name ?? 'Guest' }}</td>
                                <td>
                                    <span class="badge badge-{{ $request->status === 'played' ? 'success' : ($request->status === 'pending' ? 'warning' : 'gray') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--color-text-muted);">No recent requests</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
                <a href="{{ route('admin.activity.index') }}" class="btn btn-sm btn-secondary">View All</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity as $activity)
                            <tr>
                                <td>{{ $activity->causer?->name ?? 'System' }}</td>
                                <td>{{ Str::limit($activity->description, 40) }}</td>
                                <td>{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--color-text-muted);">No recent activity</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
