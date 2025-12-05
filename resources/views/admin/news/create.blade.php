<x-admin.layouts.app title="Create News Article">
    <div class="admin-header">
        <h1>Create News Article</h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to News
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.news.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-input" required>
                    @error('title')
                        <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" class="form-textarea" rows="2">{{ old('excerpt') }}</textarea>
                    <small style="color: var(--color-text-muted);">Short summary shown in previews</small>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-textarea" rows="10" required>{{ old('content') }}</textarea>
                    @error('content')
                        <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="author_id" class="form-label">Author</label>
                        <select id="author_id" name="author_id" class="form-select">
                            <option value="">Select Author</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="source" class="form-label">Source</label>
                        <select id="source" name="source" class="form-select" required>
                            <option value="manual" {{ old('source') === 'manual' ? 'selected' : '' }}>Manual Entry</option>
                            <option value="rss" {{ old('source') === 'rss' ? 'selected' : '' }}>RSS Feed</option>
                            <option value="api" {{ old('source') === 'api' ? 'selected' : '' }}>External API</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="source_url" class="form-label">Source URL</label>
                        <input type="url" id="source_url" name="source_url" value="{{ old('source_url') }}" class="form-input">
                        <small style="color: var(--color-text-muted);">Original source URL for imported news</small>
                    </div>

                    <div class="form-group">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="url" id="image" name="image" value="{{ old('image') }}" class="form-input">
                        <small style="color: var(--color-text-muted);">External image URL</small>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="form-check-input">
                            <span>Published</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="published_at" class="form-label">Publish Date</label>
                        <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}" class="form-input">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Article
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
