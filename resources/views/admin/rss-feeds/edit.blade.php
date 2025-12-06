<x-admin.layouts.app :title="'Edit RSS Feed'">
    <div class="admin-header">
        <h1>Edit RSS Feed</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.rss-feeds.update', $rssFeed) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="name">Feed Name *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $rssFeed->name) }}" required>
                    @error('name')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="url">RSS Feed URL *</label>
                    <input type="url" name="url" id="url" class="form-input" value="{{ old('url', $rssFeed->url) }}" required placeholder="https://example.com/feed.xml">
                    <small style="color: var(--color-text-muted);">The full URL to the RSS or Atom feed</small>
                    @error('url')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" name="category" id="category" class="form-input" value="{{ old('category', $rssFeed->category) }}" placeholder="Gaming News">
                    <small style="color: var(--color-text-muted);">Optional category for organizing feeds</small>
                    @error('category')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="3" placeholder="Brief description of this feed">{{ old('description', $rssFeed->description) }}</textarea>
                    @error('description')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="fetch_interval">Fetch Interval (seconds) *</label>
                    <input type="number" name="fetch_interval" id="fetch_interval" class="form-input" value="{{ old('fetch_interval', $rssFeed->fetch_interval) }}" min="300" max="86400" required>
                    <small style="color: var(--color-text-muted);">How often to check for new articles (min: 300, max: 86400)</small>
                    @error('fetch_interval')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $rssFeed->is_active) ? 'checked' : '' }}>
                        <span class="form-check-label">Active</span>
                    </label>
                    <small style="color: var(--color-text-muted); display: block; margin-top: 0.25rem;">Only active feeds will be automatically fetched</small>
                    @error('is_active')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Feed
                    </button>
                    <a href="{{ route('admin.rss-feeds.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">Feed Statistics</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <div style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">Articles Imported</div>
                    <div style="font-size: 1.5rem; font-weight: 600;">{{ number_format($rssFeed->articles_imported) }}</div>
                </div>
                <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <div style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">Last Fetched</div>
                    <div style="font-size: 1.5rem; font-weight: 600;">
                        @if($rssFeed->last_fetched_at)
                            {{ $rssFeed->last_fetched_at->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
                <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <div style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">Status</div>
                    <div style="font-size: 1.5rem; font-weight: 600;">
                        @if($rssFeed->is_active)
                            <span style="color: #43b581;">Active</span>
                        @else
                            <span style="color: #f04747;">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
