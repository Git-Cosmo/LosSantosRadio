/**
 * Favorites Module
 * Manages favorite songs using localStorage
 */

const FAVORITES_KEY = 'lsr_favorite_songs';

export function initializeFavorites() {
    console.log('❤️ Favorites system initialized');
}

export function getFavorites() {
    try {
        const favorites = localStorage.getItem(FAVORITES_KEY);
        return favorites ? JSON.parse(favorites) : [];
    } catch (e) {
        console.error('Error loading favorites:', e);
        return [];
    }
}

export function isFavorite(songId) {
    const favorites = getFavorites();
    return favorites.some(fav => fav.id === songId);
}

export function toggleFavorite(song) {
    const favorites = getFavorites();
    const existingIndex = favorites.findIndex(fav => fav.id === song.id);

    if (existingIndex >= 0) {
        // Remove from favorites
        favorites.splice(existingIndex, 1);
        saveFavorites(favorites);
        if (window.showToast) {
            window.showToast('info', 'Removed from favorites');
        }
        return false;
    } else {
        // Add to favorites
        favorites.unshift({
            id: song.id,
            title: song.title,
            artist: song.artist,
            artwork: song.artwork,
            addedAt: new Date().toISOString()
        });
        saveFavorites(favorites);
        if (window.showToast) {
            window.showToast('success', 'Added to favorites ❤️');
        }
        return true;
    }
}

function saveFavorites(favorites) {
    try {
        // Keep only last 100 favorites
        const trimmed = favorites.slice(0, 100);
        localStorage.setItem(FAVORITES_KEY, JSON.stringify(trimmed));
        
        // Dispatch event for UI updates
        window.dispatchEvent(new CustomEvent('favoritesUpdated', {
            detail: { favorites: trimmed }
        }));
    } catch (e) {
        console.error('Error saving favorites:', e);
        if (typeof window.showToast === 'function') {
            window.showToast('error', 'Unable to save favorites. Storage may be full or disabled.');
        }
    }
}

export function clearFavorites() {
    localStorage.removeItem(FAVORITES_KEY);
    window.dispatchEvent(new CustomEvent('favoritesUpdated', {
        detail: { favorites: [] }
    }));
}
