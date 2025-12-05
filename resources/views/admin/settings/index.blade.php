<x-admin.layouts.app title="Settings">
    <div class="admin-header">
        <h1>Settings</h1>
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
                    @forelse($settings as $setting)
                        <tr>
                            <td><code>{{ $setting->key }}</code></td>
                            <td>{{ Str::limit($setting->value, 50) }}</td>
                            <td>
                                @php
                                    $typeColors = [
                                        'string' => 'primary',
                                        'integer' => 'success',
                                        'boolean' => 'warning',
                                        'json' => 'primary',
                                    ];
                                @endphp
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
