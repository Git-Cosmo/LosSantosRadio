<x-admin.layouts.app>
    <x-slot name="title">Edit DJ Profile</x-slot>

    <div class="admin-header">
        <h1>Edit DJ Profile</h1>
        <a href="{{ route('admin.djs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.djs.update', $djProfile) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">User Account</label>
                    <input type="text" class="form-input" value="{{ $djProfile->user->name }} ({{ $djProfile->user->email }})" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Stage Name *</label>
                    <input type="text" name="stage_name" class="form-input" value="{{ old('stage_name', $djProfile->stage_name) }}" required>
                    @error('stage_name')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-textarea" rows="3">{{ old('bio', $djProfile->bio) }}</textarea>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Show Name</label>
                        <input type="text" name="show_name" class="form-input" value="{{ old('show_name', $djProfile->show_name) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Genres (comma separated)</label>
                        <input type="text" name="genres" class="form-input" value="{{ old('genres', $djProfile->formatted_genres) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Show Description</label>
                    <textarea name="show_description" class="form-textarea" rows="2">{{ old('show_description', $djProfile->show_description) }}</textarea>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $djProfile->is_active) ? 'checked' : '' }}>
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" {{ old('is_featured', $djProfile->is_featured) ? 'checked' : '' }}>
                            <span>Featured DJ</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update DJ Profile
                    </button>
                    <a href="{{ route('admin.djs.schedules', $djProfile) }}" class="btn btn-secondary">
                        <i class="fas fa-calendar"></i> Manage Schedule
                    </a>
                    <a href="{{ route('admin.djs.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
