<x-admin.layouts.app :title="'Discord Bot'">
    <div class="admin-header">
        <h1>Discord Bot</h1>
        <div class="header-actions">
            <a href="{{ route('admin.discord.settings') }}" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>

    @if(!$stats['configured'])
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            Discord bot is not configured. Please add <code>DISCORD_BOT_TOKEN</code> and <code>DISCORD_GUILD_ID</code> to your .env file.
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['local_roles'] }}</div>
            <div class="stat-label">Synced Roles</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['local_members'] }}</div>
            <div class="stat-label">Synced Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                @if($stats['configured'])
                    <span style="color: var(--color-success);"><i class="fas fa-check-circle"></i></span>
                @else
                    <span style="color: var(--color-danger);"><i class="fas fa-times-circle"></i></span>
                @endif
            </div>
            <div class="stat-label">Bot Status</div>
        </div>
    </div>

    @if($stats['bot'])
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h2 class="card-title">Bot Info</h2>
            </div>
            <div class="card-body">
                <p><strong>Username:</strong> {{ $stats['bot']['username'] }}</p>
                <p><strong>Bot ID:</strong> {{ $stats['bot']['id'] }}</p>
                @if($stats['guild'])
                    <p><strong>Server:</strong> {{ $stats['guild']['name'] }}</p>
                @endif
            </div>
        </div>
    @endif

    <div class="grid grid-cols-2" style="gap: 1.5rem;">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="card-title">Discord Roles</h2>
                <form action="{{ route('admin.discord.sync-roles') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary" {{ !$stats['configured'] ? 'disabled' : '' }}>
                        <i class="fas fa-sync"></i> Sync
                    </button>
                </form>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($roles->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles->take(10) as $role)
                                <tr>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <span style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $role->hex_color }}"></span>
                                            {{ $role->name }}
                                        </span>
                                    </td>
                                    <td>{{ $role->position }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="padding: 1rem; color: var(--color-text-muted);">No roles synced yet.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="card-title">Discord Members</h2>
                <form action="{{ route('admin.discord.sync-users') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary" {{ !$stats['configured'] ? 'disabled' : '' }}>
                        <i class="fas fa-sync"></i> Sync
                    </button>
                </form>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($members->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Linked User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>
                                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <img src="{{ $member->avatar_url }}" alt="{{ $member->tag }} avatar" style="width: 24px; height: 24px; border-radius: 50%;">
                                            {{ $member->tag }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($member->user)
                                            <a href="{{ route('admin.users.edit', $member->user) }}">{{ $member->user->name }}</a>
                                        @else
                                            <span class="badge badge-gray">Not linked</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="padding: 1rem; color: var(--color-text-muted);">No members synced yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">Activity Log</h2>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($logs->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Action</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $log->type === 'error' ? 'danger' : ($log->type === 'warning' ? 'warning' : 'primary') }}">
                                        {{ $log->type }}
                                    </span>
                                </td>
                                <td>{{ $log->action }}</td>
                                <td>{{ Str::limit($log->message, 50) }}</td>
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="padding: 1rem; color: var(--color-text-muted);">No activity logs yet.</p>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
