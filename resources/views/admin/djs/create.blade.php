<x-admin.layouts.app>
    <x-slot name="title">Add DJ</x-slot>

    <div class="admin-header">
        <h1>Add DJ</h1>
        <a href="{{ route('admin.djs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.djs.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">User Account *</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select a user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                    @if($users->isEmpty())
                        <p style="color: var(--color-warning); font-size: 0.875rem; margin-top: 0.5rem;">
                            <i class="fas fa-exclamation-triangle"></i> No users available without DJ profiles.
                        </p>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Stage Name *</label>
                    <input type="text" name="stage_name" class="form-input" value="{{ old('stage_name') }}" required>
                    @error('stage_name')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-textarea" rows="3" placeholder="Tell listeners about this DJ...">{{ old('bio') }}</textarea>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Show Name</label>
                        <input type="text" name="show_name" class="form-input" value="{{ old('show_name') }}" placeholder="e.g., The Morning Vibes">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Genres (comma separated)</label>
                        <input type="text" name="genres" class="form-input" value="{{ old('genres') }}" placeholder="e.g., Hip-Hop, R&B, Pop">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Show Description</label>
                    <textarea name="show_description" class="form-textarea" rows="2" placeholder="Describe the show...">{{ old('show_description') }}</textarea>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" {{ old('is_featured') ? 'checked' : '' }}>
                            <span>Featured DJ</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add DJ
                    </button>
                    <a href="{{ route('admin.djs.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
