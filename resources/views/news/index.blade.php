<x-layouts.app :title="'News'">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-newspaper" style="color: var(--color-accent);"></i>
                News & Announcements
            </h1>
        </div>
        <div class="card-body">
            @if($news->count() > 0)
                <div class="news-grid" style="display: grid; gap: 1.5rem;">
                    @foreach($news as $article)
                        <article class="news-card" style="background: var(--color-bg-tertiary); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
                            @if($article->image)
                                <div style="height: 200px; overflow: hidden;">
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endif
                            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                    <span class="badge" style="background-color: rgba(88, 166, 255, 0.2); color: var(--color-accent); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                                        {{ $article->source === 'manual' ? 'Article' : ucfirst($article->source) }}
                                    </span>
                                    <time style="color: var(--color-text-muted); font-size: 0.8125rem;">
                                        {{ $article->published_at->format('M d, Y') }}
                                    </time>
                                </div>
                                <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">
                                    <a href="{{ route('news.show', $article->slug) }}" style="color: var(--color-text-primary); transition: color 0.2s;">
                                        {{ $article->title }}
                                    </a>
                                </h2>
                                <p style="color: var(--color-text-secondary); font-size: 0.875rem; flex: 1; margin-bottom: 1rem;">
                                    {{ $article->excerpt_text }}
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                                    @if($article->author)
                                        <span style="color: var(--color-text-muted); font-size: 0.8125rem;">
                                            <i class="fas fa-user"></i> {{ $article->author->name }}
                                        </span>
                                    @else
                                        <span></span>
                                    @endif
                                    <a href="{{ route('news.show', $article->slug) }}" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.8125rem;">
                                        Read More <i class="fas fa-arrow-right" style="margin-left: 0.25rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($news->hasPages())
                    <div style="margin-top: 2rem; display: flex; justify-content: center;">
                        {{ $news->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-newspaper" style="font-size: 3rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                    <p style="color: var(--color-text-muted);">No news articles available yet. Check back soon!</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }
        .news-card a:hover {
            color: var(--color-accent) !important;
            text-decoration: none;
        }
        @media (min-width: 768px) {
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</x-layouts.app>
