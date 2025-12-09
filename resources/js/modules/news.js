document.addEventListener('DOMContentLoaded', function() {
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
});
