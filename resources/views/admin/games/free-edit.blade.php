<x-admin.layouts.app :title="'Edit Free Game'">
    <div class="admin-header">
        <h1>Edit Free Game</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.games.free.update', $game) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="title">Title *</label>
                    <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $game->title) }}" required>
                    @error('title')<span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea">{{ old('description', $game->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2" style="gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="platform">Platform</label>
                        <input type="text" name="platform" id="platform" class="form-input" value="{{ old('platform', $game->platform) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="store">Store</label>
                        <input type="text" name="store" id="store" class="form-input" value="{{ old('store', $game->store) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="url">Game URL *</label>
                    <input type="url" name="url" id="url" class="form-input" value="{{ old('url', $game->url) }}" required>
                    @error('url')<span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="image_url">Image URL</label>
                    <input type="url" name="image_url" id="image_url" class="form-input" value="{{ old('image_url', $game->image_url) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="expires_at">Expires At</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" class="form-input" value="{{ old('expires_at', $game->expires_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $game->is_active) ? 'checked' : '' }}>
                        <span class="form-check-label">Active</span>
                    </label>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Game
                    </button>
                    <a href="{{ route('admin.games.free') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
