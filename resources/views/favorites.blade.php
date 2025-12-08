@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
    <div class="favorites-page">
        <div class="container">
            <div class="page-header">
                <h1>
                    <i class="fas fa-heart"></i>
                    My Favorite Songs
                </h1>
                <p class="page-description">
                    Your personal collection of favorite tracks from Los Santos Radio
                </p>
            </div>

            <div x-data="favoritesPage()" x-init="init()">
                {{-- Controls --}}
                <div class="favorites-controls">
                    <div class="favorites-count">
                        <span x-text="favorites.length"></span> favorite<span x-show="favorites.length !== 1">s</span>
                    </div>
                    <button @click="clearAll()" 
                            x-show="favorites.length > 0"
                            class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        Clear All
                    </button>
                </div>

                {{-- Empty State --}}
                <div x-show="favorites.length === 0" class="empty-state">
                    <i class="fas fa-heart-broken"></i>
                    <h3>No Favorites Yet</h3>
                    <p>Start adding your favorite songs by clicking the heart icon in the player!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-radio"></i>
                        Listen Now
                    </a>
                </div>

                {{-- Favorites Grid --}}
                <div x-show="favorites.length > 0" class="favorites-grid">
                    <template x-for="(favorite, index) in favorites" :key="favorite.id">
                        <div class="favorite-card">
                            <div class="favorite-artwork">
                                <img :src="favorite.artwork || '/images/default-album.png'" 
                                     :alt="favorite.title + ' - ' + favorite.artist"
                                     class="favorite-image">
                                <div class="favorite-overlay">
                                    <button @click="removeFavorite(favorite.id)" 
                                            class="favorite-remove-btn"
                                            title="Remove from favorites">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="favorite-info">
                                <h3 class="favorite-title" x-text="favorite.title"></h3>
                                <p class="favorite-artist" x-text="favorite.artist"></p>
                                <p class="favorite-date" x-text="formatDate(favorite.addedAt)"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <style>
        .favorites-page {
            padding: 2rem 0;
            min-height: calc(100vh - 200px);
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .page-header h1 i {
            color: #ef4444;
        }

        .page-description {
            color: var(--color-text-secondary);
            font-size: 1.125rem;
        }

        .favorites-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding: 1rem;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
        }

        .favorites-count {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 16px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--color-text-muted);
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: var(--color-text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--color-text-secondary);
            margin-bottom: 2rem;
        }

        .favorites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .favorite-card {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .favorite-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .favorite-artwork {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
        }

        .favorite-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .favorite-card:hover .favorite-image {
            transform: scale(1.05);
        }

        .favorite-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .favorite-card:hover .favorite-overlay {
            opacity: 1;
        }

        .favorite-remove-btn {
            background: #ef4444;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .favorite-remove-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .favorite-info {
            padding: 1rem;
        }

        .favorite-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .favorite-artist {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-bottom: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .favorite-date {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .favorites-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .favorites-controls {
                flex-direction: column;
                gap: 1rem;
            }

            .favorites-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <script>
        function favoritesPage() {
            return {
                favorites: [],

                init() {
                    this.loadFavorites();
                    // Listen for favorites updates
                    window.addEventListener('favoritesUpdated', (e) => {
                        this.favorites = e.detail.favorites;
                    });
                },

                loadFavorites() {
                    if (window.getFavorites) {
                        this.favorites = window.getFavorites();
                    }
                },

                removeFavorite(songId) {
                    const song = this.favorites.find(f => f.id === songId);
                    if (song && window.toggleFavorite) {
                        window.toggleFavorite(song);
                        this.loadFavorites();
                    }
                },

                clearAll() {
                    if (confirm('Are you sure you want to clear all favorites? This cannot be undone.')) {
                        localStorage.removeItem('lsr_favorite_songs');
                        window.dispatchEvent(new CustomEvent('favoritesUpdated', {
                            detail: { favorites: [] }
                        }));
                        this.favorites = [];
                    }
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));

                    if (days === 0) return 'Added today';
                    if (days === 1) return 'Added yesterday';
                    if (days < 7) return `Added ${days} days ago`;
                    return `Added ${date.toLocaleDateString()}`;
                }
            };
        }
    </script>
@endsection
