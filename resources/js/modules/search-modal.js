/**
 * Search Modal Alpine.js Component
 * Provides global search functionality across the site
 */

import { logError } from './logger';

export function searchModal() {
    return {
        isOpen: false,
        query: '',
        results: [],
        loading: false,
        searchTimeout: null,

        openModal() {
            this.isOpen = true;
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        clearSearch() {
            this.query = '';
            this.results = [];
            this.$refs.searchInput.focus();
        },

        async search() {
            // Input validation: minimum 2 characters, maximum 100 characters
            if (this.query.length < 2 || this.query.length > 100) {
                this.results = [];
                return;
            }

            this.loading = true;

            try {
                // Use the data attribute for the search URL from Laravel route helper
                const searchUrl = this.$root.dataset.searchUrl || '/api/search';
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();

                if (data.success) {
                    this.results = data.results;
                }
            } catch (error) {
                logError('Search error:', error);
            } finally {
                this.loading = false;
            }
        },

        getResultIcon(type) {
            const icons = {
                'news': 'fas fa-newspaper',
                'event': 'fas fa-calendar-alt',
                'free_game': 'fas fa-gift',
                'deal': 'fas fa-tags',
                'video': 'fas fa-video'
            };
            return icons[type] || 'fas fa-file';
        },

        formatType(type) {
            return type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
    };
}

// Make available globally for Alpine.js
window.searchModal = searchModal;
