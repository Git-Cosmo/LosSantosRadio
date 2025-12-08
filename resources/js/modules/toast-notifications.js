/**
 * Toast Notifications Module
 * Shows non-intrusive notifications for song changes and events
 */

const TOAST_ENABLED_KEY = 'lsr_toast_enabled';
let lastNotifiedSong = null;

export function initializeToastNotifications() {
    // Listen for now playing updates
    document.addEventListener('nowPlayingUpdate', (e) => {
        if (!isToastEnabled()) return;

        const data = e.detail;
        if (data.current_song) {
            const songKey = `${data.current_song.title}-${data.current_song.artist}`;
            
            // Only notify if song changed
            if (lastNotifiedSong !== songKey) {
                lastNotifiedSong = songKey;
                showNowPlayingToast(data.current_song);
            }
        }
    });

    console.log('🔔 Toast notifications initialized');
}

function showNowPlayingToast(song) {
    const title = song.title || 'Unknown Title';
    const artist = song.artist || 'Unknown Artist';
    
    if (window.showToast) {
        window.showToast('info', `Now Playing: ${title} - ${artist}`, 5000);
    }
}

export function isToastEnabled() {
    const enabled = localStorage.getItem(TOAST_ENABLED_KEY);
    return enabled === null ? true : enabled === 'true';
}

export function setToastEnabled(enabled) {
    localStorage.setItem(TOAST_ENABLED_KEY, enabled.toString());
    if (window.showToast) {
        window.showToast('success', `Toast notifications ${enabled ? 'enabled' : 'disabled'}`);
    }
}

export function toggleToastNotifications() {
    const currentState = isToastEnabled();
    setToastEnabled(!currentState);
    return !currentState;
}
