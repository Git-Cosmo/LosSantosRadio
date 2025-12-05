<x-admin.layouts.app title="Song Requests">
    <div class="admin-header">
        <h1>Song Requests</h1>
    </div>

    <div class="filters">
        <form method="GET" class="filter-group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search songs..." class="form-input" style="width: 200px;">
            <select name="status" class="form-select" style="width: 150px;">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                        {{ $label }}
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
                        <th>#</th>
                        <th>Song</th>
                        <th>Requester</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>{{ $request->queue_order ?? '-' }}</td>
                            <td>
                                <div>{{ Str::limit($request->song_title, 40) }}</div>
                                <small style="color: var(--color-text-muted);">{{ Str::limit($request->song_artist, 30) }}</small>
                            </td>
                            <td>{{ $request->user?->name ?? 'Guest' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'playing' => 'primary',
                                        'played' => 'success',
                                        'rejected' => 'danger',
                                        'cancelled' => 'gray',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$request->status] ?? 'gray' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->diffForHumans() }}</td>
                            <td>
                                <div style="display: flex; gap: 0.25rem;">
                                    @if($request->status === 'pending')
                                        <form action="{{ route('admin.requests.move-up', $request) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary" title="Move Up">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.requests.move-down', $request) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary" title="Move Down">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.requests.mark-played', $request) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Mark Played">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.requests.reject', $request) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reject this request?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.requests.edit', $request) }}" class="btn btn-sm btn-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--color-text-muted);">No song requests found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $requests->links() }}
    </div>
</x-admin.layouts.app>
