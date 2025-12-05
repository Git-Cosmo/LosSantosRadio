<x-admin.layouts.app>
    <x-slot name="title">Edit Event</x-slot>

    <div class="admin-header">
        <h1>Edit Event</h1>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.events.update', $event) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-input" value="{{ old('title', $event->title) }}" required>
                    @error('title')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Event Type *</label>
                        <select name="event_type" class="form-select" required>
                            <option value="general" {{ old('event_type', $event->event_type) === 'general' ? 'selected' : '' }}>General</option>
                            <option value="special" {{ old('event_type', $event->event_type) === 'special' ? 'selected' : '' }}>Special</option>
                            <option value="live_show" {{ old('event_type', $event->event_type) === 'live_show' ? 'selected' : '' }}>Live Show</option>
                            <option value="contest" {{ old('event_type', $event->event_type) === 'contest' ? 'selected' : '' }}>Contest</option>
                            <option value="meetup" {{ old('event_type', $event->event_type) === 'meetup' ? 'selected' : '' }}>Meetup</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-input" value="{{ old('location', $event->location) }}">
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Start Date/Time *</label>
                        <input type="datetime-local" name="starts_at" class="form-input" value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date/Time</label>
                        <input type="datetime-local" name="ends_at" class="form-input" value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                            <span>Featured Event</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input" {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                            <span>Published</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Event
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
