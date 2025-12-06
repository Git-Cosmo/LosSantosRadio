<x-admin.layouts.app :title="'Add RSS Feed'">
    <div class="admin-header">
        <h1>Add RSS Feed</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.rss-feeds.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Feed Name *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="url">RSS Feed URL *</label>
                    <input type="url" name="url" id="url" class="form-input" value="{{ old('url') }}" required placeholder="https://example.com/feed.xml">
                    <small style="color: var(--color-text-muted);">The full URL to the RSS or Atom feed</small>
                    @error('url')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" name="category" id="category" class="form-input" value="{{ old('category') }}" placeholder="Gaming News">
                    <small style="color: var(--color-text-muted);">Optional category for organizing feeds</small>
                    @error('category')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" class="form-input" rows="3" placeholder="Brief description of this feed">{{ old('description') }}</textarea>
                    @error('description')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="fetch_interval">Fetch Interval (seconds) *</label>
                    <input type="number" name="fetch_interval" id="fetch_interval" class="form-input" value="{{ old('fetch_interval', 3600) }}" min="300" max="86400" required>
                    <small style="color: var(--color-text-muted);">How often to check for new articles (min: 300, max: 86400). Default: 3600 (1 hour)</small>
                    @error('fetch_interval')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', '1') == '1')>
                        <span class="form-check-label">Active</span>
                    </label>
                    <small style="color: var(--color-text-muted); display: block; margin-top: 0.25rem;">Only active feeds will be automatically fetched</small>
                    @error('is_active')
                        <small style="color: #f04747;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Feed
                    </button>
                    <a href="{{ route('admin.rss-feeds.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">Popular Gaming RSS Feeds</h2>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">Here are some popular gaming RSS feeds you can add:</p>
            <ul style="color: var(--color-text-secondary); list-style: none; padding: 0;">
                <li style="margin-bottom: 0.75rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <strong>IGN News</strong><br>
                    <code style="font-size: 0.875rem;">https://feeds.ign.com/ign/news</code>
                </li>
                <li style="margin-bottom: 0.75rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <strong>GameSpot News</strong><br>
                    <code style="font-size: 0.875rem;">https://www.gamespot.com/feeds/news/</code>
                </li>
                <li style="margin-bottom: 0.75rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <strong>PC Gamer</strong><br>
                    <code style="font-size: 0.875rem;">https://www.pcgamer.com/rss/</code>
                </li>
                <li style="margin-bottom: 0.75rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <strong>Kotaku</strong><br>
                    <code style="font-size: 0.875rem;">https://kotaku.com/rss</code>
                </li>
                <li style="margin-bottom: 0.75rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 4px;">
                    <strong>Polygon</strong><br>
                    <code style="font-size: 0.875rem;">https://www.polygon.com/rss/index.xml</code>
                </li>
            </ul>
        </div>
    </div>
</x-admin.layouts.app>
