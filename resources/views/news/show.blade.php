<x-layouts.app :title="$article->title">
    <div class="grid" style="grid-template-columns: 1fr 300px; gap: 1.5rem;">
        <!-- Main Content -->
        <div>
            <article class="card">
                <div class="card-body">
                    <!-- Article Header -->
                    <header style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <span class="badge" style="background-color: rgba(88, 166, 255, 0.2); color: var(--color-accent); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                                {{ $article->source === 'manual' ? 'Article' : ucfirst($article->source) }}
                            </span>
                            <a href="{{ route('news.index') }}" style="color: var(--color-text-secondary); font-size: 0.875rem;">
                                <i class="fas fa-arrow-left"></i> Back to News
                            </a>
                        </div>
                        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-text-primary);">
                            {{ $article->title }}
                        </h1>
                        <div style="display: flex; align-items: center; gap: 1rem; color: var(--color-text-muted); font-size: 0.875rem;">
                            @if($article->author)
                                <span>
                                    <i class="fas fa-user"></i> {{ $article->author->name }}
                                </span>
                            @endif
                            <span>
                                <i class="fas fa-calendar"></i> {{ $article->published_at->format('F d, Y') }}
                            </span>
                            <span>
                                <i class="fas fa-clock"></i> {{ $article->published_at->diffForHumans() }}
                            </span>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if($article->image)
                        <div style="margin-bottom: 1.5rem; border-radius: 8px; overflow: hidden;">
                            <img src="{{ $article->image }}" alt="{{ $article->title }}" style="width: 100%; max-height: 400px; object-fit: cover;">
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="article-content" style="color: var(--color-text-secondary); line-height: 1.8; font-size: 1rem;">
                        {!! $article->content !!}
                    </div>

                    <!-- Source Attribution -->
                    @if($article->isExternal() && $article->source_url)
                        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                            <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                                <i class="fas fa-link"></i> Original source:
                                <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer">
                                    {{ parse_url($article->source_url, PHP_URL_HOST) }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <aside>
            <!-- Related News -->
            @if($relatedNews->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-newspaper" style="color: var(--color-accent);"></i>
                            Related News
                        </h2>
                    </div>
                    <div class="card-body" style="padding: 0.5rem;">
                        @foreach($relatedNews as $related)
                            <a href="{{ route('news.show', $related->slug) }}" class="news-item" style="display: block; padding: 0.75rem; border-radius: 6px; transition: all 0.2s; text-decoration: none;">
                                <h3 style="font-size: 0.875rem; font-weight: 500; color: var(--color-text-primary); margin-bottom: 0.25rem;">
                                    {{ Str::limit($related->title, 50) }}
                                </h3>
                                <p style="font-size: 0.75rem; color: var(--color-text-muted);">
                                    {{ $related->published_at->format('M d, Y') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Share Options -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-share-alt" style="color: var(--color-accent);"></i>
                        Share
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; gap: 0.75rem;">
                        <button class="btn btn-secondary" style="flex: 1;" onclick="shareTwitter()">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="btn btn-secondary" style="flex: 1;" onclick="shareFacebook()">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="btn btn-secondary" style="flex: 1;" onclick="copyLink()">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <style>
        .article-content h2, .article-content h3 {
            color: var(--color-text-primary);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .article-content p {
            margin-bottom: 1rem;
        }
        .article-content a {
            color: var(--color-accent);
        }
        .article-content img {
            max-width: 100%;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .article-content ul, .article-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .article-content blockquote {
            border-left: 3px solid var(--color-accent);
            padding-left: 1rem;
            margin: 1rem 0;
            color: var(--color-text-muted);
            font-style: italic;
        }
        .news-item:hover {
            background-color: var(--color-bg-hover);
        }
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    @push('scripts')
    <script>
        function shareTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent('{{ $article->title }}');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }

        function shareFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    </script>
    @endpush
</x-layouts.app>
