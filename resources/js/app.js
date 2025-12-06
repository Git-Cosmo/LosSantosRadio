import './bootstrap';

// Import all modules
import './modules/live-clock';
import './modules/search-modal';
import './modules/radio-player';
import './modules/ui-helpers';

// Import module functions for direct use
import { liveClock } from './modules/live-clock';
import { searchModal } from './modules/search-modal';
import {
    togglePlayback,
    rateSong,
    loadSongRating,
    loadTrendingSongs,
    formatTime,
    handleNowPlayingUpdate,
    startNowPlayingRefresh
} from './modules/radio-player';
import {
    toggleMobileMenu,
    createScrollToTop,
    addEntranceAnimations,
    showToast
} from './modules/ui-helpers';

// Make functions available globally for Alpine.js and inline scripts
window.liveClock = liveClock;
window.searchModal = searchModal;
window.togglePlayback = togglePlayback;
window.rateSong = rateSong;
window.loadSongRating = loadSongRating;
window.loadTrendingSongs = loadTrendingSongs;
window.formatTime = formatTime;
window.handleNowPlayingUpdate = handleNowPlayingUpdate;
window.startNowPlayingRefresh = startNowPlayingRefresh;
window.toggleMobileMenu = toggleMobileMenu;
window.createScrollToTop = createScrollToTop;
window.addEntranceAnimations = addEntranceAnimations;
window.showToast = showToast;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Start now playing auto-refresh if on a page with radio functionality
    if (document.getElementById('now-playing') || document.getElementById('song-rating')) {
        startNowPlayingRefresh();

        // Listen for now playing updates
        document.addEventListener('nowPlayingUpdate', (e) => {
            handleNowPlayingUpdate(e.detail);
        });
    }
});
