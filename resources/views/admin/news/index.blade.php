<x-admin.layouts.app title="News">
    <div class="admin-header">
        <h1>News Articles</h1>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.rss-feeds.index') }}" class="btn btn-secondary">
                <i class="fas fa-rss"></i> Manage RSS Feeds
            </a>
            <form action="{{ route('admin.rss-feeds.import-all') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary" onclick="return confirm('Import articles from all active RSS feeds?');">
                    <i class="fas fa-sync"></i> Sync RSS Feeds
                </button>
            </form>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Article
            </a>
        </div>
    </div>

    <div class="filters">
        <form method="GET" class="filter-group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..." class="form-input" style="width: 200px;">
            <select name="published" class="form-select" style="width: 150px;">
                <option value="">All Status</option>
                <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>Published</option>
                <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $article)
                        <tr>
                            <td>{{ Str::limit($article->title, 50) }}</td>
                            <td>{{ $article->author?->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $sourceColors = [
                                        'manual' => 'success',
                                        'rss' => 'primary',
                                        'api' => 'warning',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $sourceColors[$article->source] ?? 'gray' }}">
                                    {{ ucfirst($article->source) }}
                                </span>
                            </td>
                            <td>
                                @if($article->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-gray">Draft</span>
                                @endif
                            </td>
                            <td>{{ $article->published_at?->format('M j, Y') ?? '-' }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--color-text-muted);">No news articles found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $news->links() }}
    </div>
</x-admin.layouts.app>
