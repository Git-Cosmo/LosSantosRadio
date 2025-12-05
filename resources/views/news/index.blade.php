<x-layouts.app :title="'News'">
    <!-- News Hero Section -->
    <div class="news-hero">
        <div class="news-hero-content">
            <div class="news-hero-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <h1 class="news-hero-title">News & Announcements</h1>
            <p class="news-hero-subtitle">Stay updated with the latest from Los Santos Radio</p>
        </div>
    </div>

    <!-- Filter & Sort Controls -->
    <div class="news-controls" x-data="newsFilters()">
        <div class="news-controls-left">
            <div class="news-search">
                <i class="fas fa-search"></i>
                <input type="text" 
                       placeholder="Search news..." 
                       x-model="searchQuery" 
                       @input="filterArticles()"
                       class="form-input">
            </div>
        </div>
        <div class="news-controls-right">
            <div class="news-sort">
                <label for="sort-by" class="sr-only">Sort by</label>
                <select id="sort-by" x-model="sortBy" @change="sortArticles()" class="form-input">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="title">Title A-Z</option>
                </select>
            </div>
            <div class="news-view-toggle">
                <button @click="viewMode = 'grid'" :class="{ 'active': viewMode === 'grid' }" class="view-btn" title="Grid View">
                    <i class="fas fa-th-large"></i>
                </button>
                <button @click="viewMode = 'list'" :class="{ 'active': viewMode === 'list' }" class="view-btn" title="List View">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- News Content -->
    <div class="card" x-data="newsFilters()" x-init="init()">
        <div class="card-body">
            @if($news->count() > 0)
                <!-- Grid View -->
                <div class="news-grid" x-show="viewMode === 'grid'" x-cloak>
                    @foreach($news as $article)
                        <article class="news-card" 
                                 data-title="{{ Js::from(strtolower($article->title)) }}"
                                 data-date="{{ $article->published_at->timestamp }}"
                                 x-bind:class="{ 'hidden': !matchesSearch({{ Js::from(strtolower($article->title)) }}) }">
                            <div class="news-card-image">
                                @if($article->image)
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" loading="lazy">
                                @else
                                    <div class="news-card-placeholder">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                @endif
                                <div class="news-card-overlay">
                                    <a href="{{ route('news.show', $article->slug) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Read Article
                                    </a>
                                </div>
                            </div>
                            <div class="news-card-content">
                                <div class="news-card-meta">
                                    <span class="news-badge">
                                        <i class="fas fa-tag"></i>
                                        {{ $article->source === 'manual' ? 'Article' : ucfirst($article->source) }}
                                    </span>
                                    <span class="news-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $article->published_at->format('M d, Y') }}
                                    </span>
                                </div>
                                <h2 class="news-card-title">
                                    <a href="{{ route('news.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h2>
                                <p class="news-card-excerpt">
                                    {{ $article->excerpt_text }}
                                </p>
                                <div class="news-card-footer">
                                    @if($article->author)
                                        <div class="news-author">
                                            <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" class="news-author-avatar">
                                            <span>{{ $article->author->name }}</span>
                                        </div>
                                    @else
                                        <div class="news-author">
                                            <div class="news-author-avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span>LSR Staff</span>
                                        </div>
                                    @endif
                                    <div class="news-card-actions">
                                        <span class="news-comments">
                                            <i class="fas fa-comments"></i>
                                            {{ $article->comments_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- List View -->
                <div class="news-list" x-show="viewMode === 'list'" x-cloak>
                    @foreach($news as $article)
                        <article class="news-list-item"
                                 data-title="{{ Js::from(strtolower($article->title)) }}"
                                 data-date="{{ $article->published_at->timestamp }}"
                                 x-bind:class="{ 'hidden': !matchesSearch({{ Js::from(strtolower($article->title)) }}) }">
                            <div class="news-list-image">
                                @if($article->image)
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" loading="lazy">
                                @else
                                    <div class="news-list-placeholder">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="news-list-content">
                                <div class="news-list-header">
                                    <div class="news-card-meta">
                                        <span class="news-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $article->source === 'manual' ? 'Article' : ucfirst($article->source) }}
                                        </span>
                                        <span class="news-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $article->published_at->format('M d, Y') }}
                                        </span>
                                        <span class="news-time-ago">
                                            {{ $article->published_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <h2 class="news-list-title">
                                    <a href="{{ route('news.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h2>
                                <p class="news-list-excerpt">
                                    {{ $article->excerpt_text }}
                                </p>
                                <div class="news-list-footer">
                                    @if($article->author)
                                        <div class="news-author">
                                            <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" class="news-author-avatar">
                                            <span>{{ $article->author->name }}</span>
                                        </div>
                                    @else
                                        <div class="news-author">
                                            <div class="news-author-avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span>LSR Staff</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('news.show', $article->slug) }}" class="btn btn-secondary btn-sm">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div x-show="noResults" x-cloak class="news-no-results">
                    <i class="fas fa-search"></i>
                    <p>No articles found matching your search.</p>
                    <button @click="searchQuery = ''; filterArticles()" class="btn btn-secondary">
                        Clear Search
                    </button>
                </div>

                <!-- Pagination -->
                @if($news->hasPages())
                    <div class="news-pagination">
                        {{ $news->links() }}
                    </div>
                @endif
            @else
                <div class="news-empty">
                    <div class="news-empty-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>No News Yet</h3>
                    <p>Stay tuned! We'll have exciting updates soon.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* News Hero Section */
        .news-hero {
            background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-tertiary) 100%);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 2.5rem 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .news-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(88, 166, 255, 0.08) 0%, transparent 50%);
            animation: heroGlow 8s ease-in-out infinite;
        }

        .news-hero-content {
            position: relative;
            z-index: 1;
        }

        .news-hero-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.75rem;
            color: white;
            box-shadow: 0 8px 25px rgba(88, 166, 255, 0.3);
        }

        .news-hero-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--color-accent), #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .news-hero-subtitle {
            color: var(--color-text-secondary);
            font-size: 1rem;
        }

        /* Filter Controls */
        .news-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .news-controls-left,
        .news-controls-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .news-search {
            position: relative;
            min-width: 280px;
        }

        .news-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-muted);
        }

        .news-search .form-input {
            padding-left: 2.5rem;
        }

        .news-sort .form-input {
            min-width: 150px;
        }

        .news-view-toggle {
            display: flex;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 6px;
            overflow: hidden;
        }

        .view-btn {
            padding: 0.5rem 0.75rem;
            background: transparent;
            border: none;
            color: var(--color-text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .view-btn:hover {
            color: var(--color-text-primary);
            background: var(--color-bg-tertiary);
        }

        .view-btn.active {
            color: var(--color-accent);
            background: var(--color-bg-tertiary);
        }

        /* Grid View Styles */
        .news-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 768px) {
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1200px) {
            .news-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .news-card {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
            border-color: var(--color-accent);
        }

        .news-card.hidden {
            display: none;
        }

        .news-card-image {
            height: 200px;
            position: relative;
            overflow: hidden;
            background: var(--color-bg-tertiary);
        }

        .news-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-card:hover .news-card-image img {
            transform: scale(1.05);
        }

        .news-card-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--color-text-muted);
            background: linear-gradient(135deg, var(--color-bg-tertiary), var(--color-bg-secondary));
        }

        .news-card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .news-card:hover .news-card-overlay {
            opacity: 1;
        }

        .news-card-content {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-card-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .news-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: rgba(88, 166, 255, 0.15);
            color: var(--color-accent);
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .news-date {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: var(--color-text-muted);
            font-size: 0.75rem;
        }

        .news-time-ago {
            color: var(--color-text-muted);
            font-size: 0.75rem;
            font-style: italic;
        }

        .news-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .news-card-title a {
            color: var(--color-text-primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .news-card-title a:hover {
            color: var(--color-accent);
        }

        .news-card-excerpt {
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            line-height: 1.6;
            flex: 1;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--color-border);
        }

        .news-author {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }

        .news-author-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .news-author-avatar-placeholder {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--color-bg-tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .news-card-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .news-comments {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            color: var(--color-text-muted);
            font-size: 0.8125rem;
        }

        /* List View Styles */
        .news-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .news-list-item {
            display: flex;
            gap: 1.5rem;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .news-list-item:hover {
            transform: translateX(6px);
            border-color: var(--color-accent);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .news-list-item.hidden {
            display: none;
        }

        .news-list-image {
            width: 200px;
            min-height: 160px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .news-list-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-list-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--color-text-muted);
            background: var(--color-bg-tertiary);
        }

        .news-list-content {
            flex: 1;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .news-list-header {
            margin-bottom: 0.5rem;
        }

        .news-list-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .news-list-title a {
            color: var(--color-text-primary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .news-list-title a:hover {
            color: var(--color-accent);
        }

        .news-list-excerpt {
            color: var(--color-text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
            flex: 1;
            margin-bottom: 1rem;
        }

        .news-list-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .news-list-item {
                flex-direction: column;
            }

            .news-list-image {
                width: 100%;
                height: 180px;
            }

            .news-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .news-controls-left,
            .news-controls-right {
                justify-content: space-between;
            }

            .news-search {
                min-width: 100%;
            }
        }

        /* No Results */
        .news-no-results {
            text-align: center;
            padding: 3rem;
            color: var(--color-text-muted);
        }

        .news-no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .news-no-results p {
            margin-bottom: 1rem;
        }

        /* Empty State */
        .news-empty {
            text-align: center;
            padding: 4rem 2rem;
        }

        .news-empty-icon {
            width: 80px;
            height: 80px;
            background: var(--color-bg-tertiary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--color-text-muted);
        }

        .news-empty h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .news-empty p {
            color: var(--color-text-muted);
        }

        /* Pagination */
        .news-pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        /* Utility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @push('scripts')
    <script>
        function newsFilters() {
            return {
                searchQuery: '',
                sortBy: 'newest',
                viewMode: localStorage.getItem('newsViewMode') || 'grid',
                noResults: false,
                init() {
                    this.$watch('viewMode', val => localStorage.setItem('newsViewMode', val));
                },
                matchesSearch(title) {
                    if (!this.searchQuery) return true;
                    return title.includes(this.searchQuery.toLowerCase());
                },
                filterArticles() {
                    const cards = document.querySelectorAll('.news-card, .news-list-item');
                    let visibleCount = 0;
                    
                    cards.forEach(card => {
                        const title = card.dataset.title || '';
                        const matches = !this.searchQuery || title.includes(this.searchQuery.toLowerCase());
                        
                        if (matches) {
                            card.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    
                    this.noResults = visibleCount === 0 && this.searchQuery.length > 0;
                },
                sortArticles() {
                    const container = this.viewMode === 'grid' 
                        ? document.querySelector('.news-grid')
                        : document.querySelector('.news-list');
                    
                    if (!container) return;
                    
                    const items = Array.from(container.children);
                    
                    items.sort((a, b) => {
                        if (this.sortBy === 'newest') {
                            return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                        } else if (this.sortBy === 'oldest') {
                            return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                        } else if (this.sortBy === 'title') {
                            return a.dataset.title.localeCompare(b.dataset.title);
                        }
                        return 0;
                    });
                    
                    items.forEach(item => container.appendChild(item));
                }
            };
        }
    </script>
    @endpush
</x-layouts.app>
