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
