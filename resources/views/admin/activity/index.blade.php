<x-admin.layouts.app title="Activity Log">
    <div class="admin-header">
        <h1>Activity Log</h1>
    </div>

    <div class="filters">
        <form method="GET" class="filter-group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity..." class="form-input" style="width: 200px;">
            <select name="event" class="form-select" style="width: 150px;">
                <option value="">All Events</option>
                @foreach($events as $event)
                    <option value="{{ $event }}" {{ request('event') === $event ? 'selected' : '' }}>
                        {{ ucfirst($event) }}
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
                        <th>User</th>
                        <th>Action</th>
                        <th>Subject</th>
                        <th>Event</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td>
                                @if($activity->causer)
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <img src="{{ $activity->causer->avatar_url }}" alt="" class="avatar-sm">
                                        <span>{{ $activity->causer->name }}</span>
                                    </div>
                                @else
                                    <span style="color: var(--color-text-muted);">System</span>
                                @endif
                            </td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                @if($activity->subject)
                                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                @else
                                    <span style="color: var(--color-text-muted);">-</span>
                                @endif
                            </td>
                            <td>
                                @if($activity->event)
                                    <span class="badge badge-gray">{{ ucfirst($activity->event) }}</span>
                                @else
                                    <span style="color: var(--color-text-muted);">-</span>
                                @endif
                            </td>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.activity.show', $activity) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--color-text-muted);">No activity found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $activities->links() }}
    </div>
</x-admin.layouts.app>
