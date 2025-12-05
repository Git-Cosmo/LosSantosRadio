<x-admin.layouts.app :title="'Add Video'">
    <div class="admin-header">
        <h1>Add Video</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.videos.store') }}" method="POST">
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
                        <label class="form-label" for="category">Category *</label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="ylyl" {{ old('category') === 'ylyl' ? 'selected' : '' }}>YLYL</option>
                            <option value="clips" {{ old('category') === 'clips' ? 'selected' : '' }}>Clips</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="platform">Platform *</label>
                        <select name="platform" id="platform" class="form-select" required>
                            <option value="youtube">YouTube</option>
                            <option value="twitch">Twitch</option>
                            <option value="kick">Kick</option>
                            <option value="reddit">Reddit</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="video_url">Video URL *</label>
                    <input type="url" name="video_url" id="video_url" class="form-input" value="{{ old('video_url') }}" required>
                    @error('video_url')<span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="embed_url">Embed URL</label>
                    <input type="url" name="embed_url" id="embed_url" class="form-input" value="{{ old('embed_url') }}">
                    <small style="color: var(--color-text-muted);">For YouTube, use: https://www.youtube.com/embed/VIDEO_ID</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="thumbnail_url">Thumbnail URL</label>
                    <input type="url" name="thumbnail_url" id="thumbnail_url" class="form-input" value="{{ old('thumbnail_url') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="author">Author</label>
                    <input type="text" name="author" id="author" class="form-input" value="{{ old('author') }}">
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Video
                    </button>
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
