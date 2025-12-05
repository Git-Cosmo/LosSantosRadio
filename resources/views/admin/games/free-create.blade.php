<x-admin.layouts.app :title="'Add Free Game'">
    <div class="admin-header">
        <h1>Add Free Game</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.games.free.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Title *</label>
                    <input type="text" name="title" id="title" class="form-input" value="{{ old('title') }}" required>
                    @error('title')<span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2" style="gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="platform">Platform</label>
                        <input type="text" name="platform" id="platform" class="form-input" value="{{ old('platform') }}" placeholder="e.g., PC, Steam">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="store">Store</label>
                        <input type="text" name="store" id="store" class="form-input" value="{{ old('store') }}" placeholder="e.g., Epic Games, Steam">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="url">Game URL *</label>
                    <input type="url" name="url" id="url" class="form-input" value="{{ old('url') }}" required>
                    @error('url')<span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="image_url">Image URL</label>
                    <input type="url" name="image_url" id="image_url" class="form-input" value="{{ old('image_url') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="expires_at">Expires At</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" class="form-input" value="{{ old('expires_at') }}">
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Game
                    </button>
                    <a href="{{ route('admin.games.free') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
