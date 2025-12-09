<x-admin.layouts.app title="Settings">
    <div class="admin-header">
        <div>
            <h1>⚙️ Advanced Settings</h1>
            <p style="color: var(--color-text-secondary); margin-top: 0.5rem; font-size: 0.9375rem;">
                Key-value settings editor for power users. Most users should use the 
                <a href="{{ route('admin.settings.index') }}" style="color: var(--color-accent); font-weight: 600;">
                    <i class="fas fa-tachometer-alt"></i> Settings Dashboard
                </a> instead.
            </p>
        </div>
        <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Setting
        </a>
    </div>

    <div class="filters">
        <form method="GET" class="filter-group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search settings..." class="form-input" style="width: 200px;">
            <select name="group" class="form-select" style="width: 150px;">
                <option value="">All Groups</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                        {{ $group }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Group</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $typeColors = [
                            'string' => 'primary',
                            'integer' => 'success',
                            'boolean' => 'warning',
                            'json' => 'primary',
                        ];
                    @endphp
                    @forelse($settings as $setting)
                        <tr>
                            <td><code>{{ $setting->key }}</code></td>
                            <td>{{ Str::limit($setting->value, 50) }}</td>
                            <td>
                                <span class="badge badge-{{ $typeColors[$setting->type] ?? 'gray' }}">
                                    {{ ucfirst($setting->type) }}
                                </span>
                            </td>
                            <td>{{ $setting->group ?? '-' }}</td>
                            <td>{{ $setting->updated_at->diffForHumans() }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.settings.destroy', $setting) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--color-text-muted);">No settings found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $settings->links() }}
    </div>
</x-admin.layouts.app>
