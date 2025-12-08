import './bootstrap';

// Import all modules
import './modules/live-clock';
import './modules/search-modal';
import './modules/radio-player';
import './modules/ui-helpers';
import { initializeNowPlaying, cleanup as cleanupWebSocket } from './modules/websocket-player';
import { initializeLyricsModal, showLyrics } from './modules/lyrics-modal';

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
window.initializeNowPlaying = initializeNowPlaying;
window.showLyrics = showLyrics;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize WebSocket for real-time now playing updates
    // Station ID is typically 1, but can be configured
    const stationId = document.querySelector('[data-station-id]')?.dataset.stationId || 1;
    
    // Start WebSocket (with polling fallback) if on a page with radio functionality
    if (document.getElementById('now-playing') || document.getElementById('song-rating')) {
        initializeNowPlaying(stationId);

        // Listen for now playing updates (from both WebSocket and polling)
        document.addEventListener('nowPlayingUpdate', (e) => {
            handleNowPlayingUpdate(e.detail);
        });
        
        window.addEventListener('nowPlayingUpdated', (e) => {
            handleNowPlayingUpdate(e.detail);
        });
    }

    // Initialize lyrics modal
    initializeLyricsModal();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    cleanupWebSocket();
});
