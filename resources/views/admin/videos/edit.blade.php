<x-admin.layouts.app :title="'Edit Video'">
    <div class="admin-header">
        <h1>Edit Video</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.videos.update', $video) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="title">Title *</label>
                    <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $video->title) }}" required>
                    @error('title')<span style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-textarea">{{ old('description', $video->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2" style="gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="category">Category *</label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="ylyl" {{ old('category', $video->category) === 'ylyl' ? 'selected' : '' }}>YLYL</option>
                            <option value="clips" {{ old('category', $video->category) === 'clips' ? 'selected' : '' }}>Clips</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="platform">Platform *</label>
                        <select name="platform" id="platform" class="form-select" required>
                            <option value="youtube" {{ old('platform', $video->platform) === 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="twitch" {{ old('platform', $video->platform) === 'twitch' ? 'selected' : '' }}>Twitch</option>
                            <option value="kick" {{ old('platform', $video->platform) === 'kick' ? 'selected' : '' }}>Kick</option>
                            <option value="reddit" {{ old('platform', $video->platform) === 'reddit' ? 'selected' : '' }}>Reddit</option>
                            <option value="other" {{ old('platform', $video->platform) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="video_url">Video URL *</label>
                    <input type="url" name="video_url" id="video_url" class="form-input" value="{{ old('video_url', $video->video_url) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="embed_url">Embed URL</label>
                    <input type="url" name="embed_url" id="embed_url" class="form-input" value="{{ old('embed_url', $video->embed_url) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="thumbnail_url">Thumbnail URL</label>
                    <input type="url" name="thumbnail_url" id="thumbnail_url" class="form-input" value="{{ old('thumbnail_url', $video->thumbnail_url) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="author">Author</label>
                    <input type="text" name="author" id="author" class="form-input" value="{{ old('author', $video->author) }}">
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                        <span class="form-check-label">Active</span>
                    </label>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Video
                    </button>
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
