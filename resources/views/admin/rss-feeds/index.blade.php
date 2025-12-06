<x-admin.layouts.app :title="'RSS Feeds'">
    <div class="admin-header">
        <h1>RSS Feeds Management</h1>
        <div style="display: flex; gap: 0.5rem;">
            @if($feeds->isEmpty())
                <form action="{{ route('admin.rss-feeds.seed') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-success" title="Automatically add popular gaming news RSS feeds">
                        <i class="fas fa-magic"></i> Quick Populate
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.rss-feeds.import-all') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary" data-confirm="Import articles from all active feeds?">
                    <i class="fas fa-sync-alt"></i> Import All Feeds
                </button>
            </form>
            <a href="{{ route('admin.rss-feeds.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add RSS Feed
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($feeds->isEmpty())
                <div class="alert" style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border); text-align: center; padding: 2rem;">
                    <i class="fas fa-rss" style="font-size: 3rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                    <p style="color: var(--color-text-secondary); margin-bottom: 1rem; font-size: 1.125rem; font-weight: 600;">No RSS feeds configured yet.</p>
                    <p style="color: var(--color-text-muted); margin-bottom: 1.5rem;">Get started by adding individual feeds or use Quick Populate to automatically add 15 high-quality gaming news sources (IGN, GameSpot, Polygon, PC Gamer, and more).</p>
                    <div style="display: flex; gap: 0.75rem; justify-content: center;">
                        <form action="{{ route('admin.rss-feeds.seed') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-magic"></i> Quick Populate (15 Feeds)
                            </button>
                        </form>
                        <a href="{{ route('admin.rss-feeds.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Custom Feed
                        </a>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>URL</th>
                                <th>Status</th>
                                <th>Articles</th>
                                <th>Last Fetched</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feeds as $feed)
                                <tr>
                                    <td>
                                        <strong>{{ $feed->name }}</strong>
                                        @if($feed->description)
                                            <br>
                                            <small style="color: var(--color-text-muted);">{{ Str::limit($feed->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($feed->category)
                                            <span class="badge" style="background: var(--color-accent);">{{ $feed->category }}</span>
                                        @else
                                            <span style="color: var(--color-text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $feed->url }}" target="_blank" rel="noopener" style="font-size: 0.875rem; color: var(--color-accent);">
                                            {{ Str::limit($feed->url, 40) }} <i class="fas fa-external-link-alt" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @if($feed->is_active)
                                            <span class="badge badge-live" style="background: #43b581;">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge" style="background: #f04747; color: white;">
                                                <i class="fas fa-pause-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($feed->articles_imported) }}</strong>
                                    </td>
                                    <td>
                                        @if($feed->last_fetched_at)
                                            <span style="font-size: 0.875rem;">{{ $feed->last_fetched_at->diffForHumans() }}</span>
                                        @else
                                            <span style="color: var(--color-text-muted); font-size: 0.875rem;">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.25rem;">
                                            @if($feed->is_active)
                                                <form action="{{ route('admin.rss-feeds.import', $feed) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="Import Now">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.rss-feeds.edit', $feed) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.rss-feeds.destroy', $feed) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this RSS feed?')" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($feeds->hasPages())
                    <div style="margin-top: 1.5rem;">
                        {{ $feeds->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">About RSS Feeds</h2>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">
                RSS feeds automatically import gaming news articles into your site. Configure feeds from popular gaming news sites to keep your content fresh and up-to-date.
            </p>
            <ul style="color: var(--color-text-secondary); margin-left: 1.5rem;">
                <li>Feeds are checked based on their configured fetch interval (default: 1 hour)</li>
                <li>Only new articles not already in your database will be imported</li>
                <li>Images are automatically extracted from RSS feed content</li>
                <li>Inactive feeds will not be automatically checked</li>
            </ul>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle confirmation dialogs for forms with data-confirm attribute
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('button[data-confirm]').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm(this.getAttribute('data-confirm'))) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    @endpush
</x-admin.layouts.app>
