@php
$newsStructuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $article->title,
    'description' => Str::limit(strip_tags($article->excerpt ?? $article->content), 160),
    'image' => $article->image ?? asset('images/icons/icon-512x512.png'),
    'datePublished' => $article->published_at->format('c'),
    'dateModified' => $article->updated_at->format('c'),
    'author' => [
        '@type' => 'Person',
        'name' => $article->author->name ?? 'Los Santos Radio'
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Los Santos Radio',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/icons/icon-512x512.png')
        ]
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('news.show', $article->slug)
    ]
];
@endphp
<x-layouts.app 
    :title="$article->title"
    :metaDescription="Str::limit(strip_tags($article->excerpt ?? $article->content), 160)"
    ogType="article"
    :ogImage="$article->image"
    :ogImageAlt="$article->title"
    :canonicalUrl="route('news.show', $article->slug)"
    :structuredData="$newsStructuredData"
>
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
                                <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to News
                            </a>
                        </div>
                        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-text-primary);">
                            {{ $article->title }}
                        </h1>
                        <div style="display: flex; align-items: center; gap: 1rem; color: var(--color-text-muted); font-size: 0.875rem;">
                            @if($article->author)
                                <span>
                                    <i class="fas fa-user" aria-hidden="true"></i> {{ $article->author->name }}
                                </span>
                            @endif
                            <span>
                                <i class="fas fa-calendar" aria-hidden="true"></i> 
                                <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->format('F d, Y') }}</time>
                            </span>
                            <span>
                                <i class="fas fa-clock" aria-hidden="true"></i> {{ $article->published_at->diffForHumans() }}
                            </span>
                            <span>
                                <i class="fas fa-comments" aria-hidden="true"></i> {{ $comments->count() }} {{ Str::plural('comment', $comments->count()) }}
                            </span>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if($article->image)
                        <div style="margin-bottom: 1.5rem; border-radius: 8px; overflow: hidden;">
                            <img src="{{ $article->image }}" alt="Featured image for {{ $article->title }}" style="width: 100%; max-height: 400px; object-fit: cover;">
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

            <!-- Comments Section -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-comments" style="color: var(--color-accent);"></i>
                        Comments ({{ $comments->count() }})
                    </h2>
                </div>
                <div class="card-body">
                    <!-- Comment Form -->
                    @auth
                        <form action="{{ route('comments.store', $article->slug) }}" method="POST" style="margin-bottom: 2rem;">
                            @csrf
                            <div style="margin-bottom: 1rem;">
                                <textarea name="body"
                                          class="form-input"
                                          rows="3"
                                          placeholder="Write a comment..."
                                          required
                                          style="resize: vertical;">{{ old('body') }}</textarea>
                                @error('body')
                                    <p style="color: var(--color-danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Post Comment
                            </button>
                        </form>
                    @else
                        <div style="background: var(--color-bg-tertiary); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
                            <p style="color: var(--color-text-muted); margin-bottom: 0.5rem;">
                                <i class="fas fa-lock"></i> Please sign in to leave a comment.
                            </p>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                Sign In
                            </a>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @if($comments->count() > 0)
                        <div class="comments-list" style="display: flex; flex-direction: column; gap: 1.25rem;">
                            @foreach($comments as $comment)
                                <div class="comment" style="border-bottom: 1px solid var(--color-border); padding-bottom: 1.25rem;">
                                    <div style="display: flex; gap: 0.75rem;">
                                        <img src="{{ $comment->user->avatar_url }}"
                                             alt="{{ $comment->user->name }}"
                                             style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">
                                        <div style="flex: 1;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                                <span style="font-weight: 500; color: var(--color-text-primary);">
                                                    {{ $comment->user->name }}
                                                </span>
                                                <span style="color: var(--color-text-muted); font-size: 0.75rem;">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p style="color: var(--color-text-secondary); margin-bottom: 0.5rem; line-height: 1.5;">
                                                {{ $comment->body }}
                                            </p>
                                            <div style="display: flex; gap: 1rem; font-size: 0.8125rem;">
                                                @auth
                                                    @if(auth()->id() === $comment->user_id || auth()->user()?->hasRole('admin'))
                                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" style="background: none; border: none; color: var(--color-danger); cursor: pointer; font-size: 0.8125rem;">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>

                                            <!-- Replies -->
                                            @if($comment->replies->count() > 0)
                                                <div style="margin-top: 1rem; padding-left: 1rem; border-left: 2px solid var(--color-border);">
                                                    @foreach($comment->replies as $reply)
                                                        <div style="margin-bottom: 1rem;">
                                                            <div style="display: flex; gap: 0.5rem;">
                                                                <img src="{{ $reply->user->avatar_url }}"
                                                                     alt="{{ $reply->user->name }}"
                                                                     style="width: 32px; height: 32px; border-radius: 50%;">
                                                                <div>
                                                                    <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.25rem;">
                                                                        <span style="font-weight: 500; font-size: 0.875rem; color: var(--color-text-primary);">
                                                                            {{ $reply->user->name }}
                                                                        </span>
                                                                        <span style="color: var(--color-text-muted); font-size: 0.75rem;">
                                                                            {{ $reply->created_at->diffForHumans() }}
                                                                        </span>
                                                                    </div>
                                                                    <p style="color: var(--color-text-secondary); font-size: 0.875rem; line-height: 1.5;">
                                                                        {{ $reply->body }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--color-text-muted); text-align: center; padding: 2rem;">
                            No comments yet. Be the first to share your thoughts!
                        </p>
                    @endif
                </div>
            </div>
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
                showToast('success', 'Link copied to clipboard!');
            });
        }
    </script>
    @endpush
</x-layouts.app>
