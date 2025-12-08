<x-admin.layouts.app title="Activity Details">
    <div class="admin-header">
        <h1>Activity Details</h1>
        <a href="{{ route('admin.activity.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Log
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <p>{{ $activity->description }}</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Event</label>
                    <p>{{ $activity->event ?? 'N/A' }}</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Performed By</label>
                    @if($activity->causer)
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <img src="{{ $activity->causer->avatar_url }}" alt="{{ $activity->causer->name }} avatar" class="avatar">
                            <span>{{ $activity->causer->name }}</span>
                        </div>
                    @else
                        <p style="color: var(--color-text-muted);">System</p>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Subject</label>
                    @if($activity->subject)
                        <p>{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</p>
                    @else
                        <p style="color: var(--color-text-muted);">N/A</p>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Time</label>
                    <p>{{ $activity->created_at->format('M j, Y g:i A') }} ({{ $activity->created_at->diffForHumans() }})</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Log Name</label>
                    <p>{{ $activity->log_name ?? 'default' }}</p>
                </div>
            </div>

            @if($activity->properties && count($activity->properties) > 0)
                <div class="form-group" style="margin-top: 1rem;">
                    <label class="form-label">Properties</label>
                    <pre style="background: var(--color-bg-tertiary); padding: 1rem; border-radius: 6px; overflow-x: auto; font-size: 0.875rem;">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
