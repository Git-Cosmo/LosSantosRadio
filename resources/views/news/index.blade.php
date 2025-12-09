<x-layouts.app :title="'News'">
    <!-- News Hero Section -->
    <div class="news-hero">
        <div class="news-hero-content">
            <div class="news-hero-icon">
                <i class="fas fa-newspaper" aria-hidden="true"></i>
            </div>
            <h1 class="news-hero-title">News & Announcements</h1>
            <p class="news-hero-subtitle">Stay updated with the latest from Los Santos Radio</p>
        </div>
    </div>

    <!-- News Content with unified Alpine.js scope -->
    <div class="card" x-data="newsFilters()" x-init="init()">
        <!-- Filter & Sort Controls -->
        <div class="card-header">
            <div class="news-controls">
                <div class="news-controls-left">
                    <div class="news-search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <label for="news-search" class="sr-only">Search news articles</label>
                        <input type="text"
                               id="news-search"
                               placeholder="Search news..." 
                               x-model="searchQuery" 
                               @input.debounce.300ms="filterArticles()"
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
                        <button @click="switchView('grid')" :class="{ 'active': viewMode === 'grid' }" class="view-btn" title="Grid View" aria-label="Switch to grid view">
                            <i class="fas fa-th-large" aria-hidden="true"></i>
                        </button>
                        <button @click="switchView('list')" :class="{ 'active': viewMode === 'list' }" class="view-btn" title="List View" aria-label="Switch to list view">
                            <i class="fas fa-list" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <p class="news-pagination-note" x-show="isPaginated" x-cloak>
                <i class="fas fa-info-circle" aria-hidden="true"></i> Search and sort apply to the current page only.
            </p>
        </div>
        <div class="card-body">
            @if($news->count() > 0)
                <!-- Grid View -->
                <div class="news-grid" x-show="viewMode === 'grid'" x-cloak aria-live="polite" aria-atomic="false">
                    @foreach($news as $article)
                        <article class="news-card" 
                                 data-title="{{ e(strtolower($article->title)) }}"
                                 data-date="{{ $article->published_at->timestamp }}"
                                 x-bind:class="{ 'hidden': !matchesSearch({{ Js::from(strtolower($article->title)) }}) }">
                            <div class="news-card-image">
                                @if($article->image)
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" loading="lazy">
                                @else
                                    <div class="news-card-placeholder">
                                        <i class="fas fa-newspaper" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div class="news-card-overlay">
                                    <a href="{{ route('news.show', $article->slug) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye" aria-hidden="true"></i> Read Article
                                    </a>
                                </div>
                            </div>
                            <div class="news-card-content">
                                <div class="news-card-meta">
                                    <span class="news-badge">
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        {{ $article->source === 'manual' ? 'Article' : ucfirst($article->source) }}
                                    </span>
                                    <span class="news-date">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
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
                                                <i class="fas fa-user" aria-hidden="true"></i>
                                            </div>
                                            <span>LSR Staff</span>
                                        </div>
                                    @endif
                                    <div class="news-card-actions">
                                        <span class="news-comments">
                                            <i class="fas fa-comments" aria-hidden="true"></i>
                                            {{ $article->comments_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- List View -->
                <div class="news-list" x-show="viewMode === 'list'" x-cloak aria-live="polite" aria-atomic="false">
                    @foreach($news as $article)
                        <article class="news-list-item"
                                 data-title="{{ e(strtolower($article->title)) }}"
                                 data-date="{{ $article->published_at->timestamp }}"
                                 x-bind:class="{ 'hidden': !matchesSearch({{ Js::from(strtolower($article->title)) }}) }">
                            <div class="news-list-image">
                                @if($article->image)
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" loading="lazy">
                                @else
                                    <div class="news-list-placeholder">
                                        <i class="fas fa-newspaper" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="news-list-content">
                                <div class="news-list-header">
                                    <div class="news-card-meta">
                                        <span class="news-badge">
                                            <i class="fas fa-tag" aria-hidden="true"></i>
                                            {{ $article->source === 'manual' ? 'Article' : ucfirst($article->source) }}
                                        </span>
                                        <span class="news-date">
                                            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
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
                                                <i class="fas fa-user" aria-hidden="true"></i>
                                            </div>
                                            <span>LSR Staff</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('news.show', $article->slug) }}" class="btn btn-secondary btn-sm">
                                        Read More <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div x-show="noResults" x-cloak class="news-no-results" role="status" aria-live="polite">
                    <i class="fas fa-search" aria-hidden="true"></i>
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
                        <i class="fas fa-newspaper" aria-hidden="true"></i>
                    </div>
                    <h3>No News Yet</h3>
                    <p>Stay tuned! We'll have exciting updates soon.</p>
                </div>
            @endif
        </div>
    </div>

    

    

    
</x-layouts.app>

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
                isPaginated: {{ $news->hasPages() ? 'true' : 'false' }},
                cards: null,
                init() {
                    // Cache card references for better performance
                    this.cards = document.querySelectorAll('.news-card, .news-list-item');
                    // Watch for view mode changes and persist
                    this.$watch('viewMode', val => localStorage.setItem('newsViewMode', val));
                    // Apply initial sort on page load
                    this.sortAllContainers();
                },
                matchesSearch(title) {
                    if (!this.searchQuery) return true;
                    return title.includes(this.searchQuery.toLowerCase());
                },
                filterArticles() {
                    let visibleCount = 0;
                    
                    this.cards.forEach(card => {
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
                    this.sortAllContainers();
                },
                sortAllContainers() {
                    // Sort both containers to maintain order when switching views
                    const gridContainer = document.querySelector('.news-grid');
                    const listContainer = document.querySelector('.news-list');
                    
                    [gridContainer, listContainer].forEach(container => {
                        if (!container) return;
                        
                        const items = Array.from(container.children);
                        
                        items.sort((a, b) => {
                            if (this.sortBy === 'newest') {
                                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                            } else if (this.sortBy === 'oldest') {
                                return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                            } else if (this.sortBy === 'title') {
                                return (a.dataset.title || '').localeCompare(b.dataset.title || '');
                            }
                            return 0;
                        });
                        
                        items.forEach(item => container.appendChild(item));
                    });
                },
                switchView(mode) {
                    this.viewMode = mode;
                }
            };
        }
    </script>
    @endpush
</x-layouts.app>
