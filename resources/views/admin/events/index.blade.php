<x-admin.layouts.app>
    <x-slot name="title">Events</x-slot>

    <div class="admin-header">
        <h1>Events</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Event
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <strong>{{ $event->title }}</strong>
                                @if($event->is_featured)
                                    <span class="badge badge-warning">Featured</span>
                                @endif
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</td>
                            <td>{{ $event->starts_at->format('M j, Y g:i A') }}</td>
                            <td>
                                @if($event->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-gray">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
                                No events yet. Create your first event!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $events->links() }}
    </div>
</x-admin.layouts.app>
