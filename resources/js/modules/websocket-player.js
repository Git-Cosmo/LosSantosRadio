/**
 * WebSocket Player Module
 * Handles real-time now playing updates via Laravel Reverb
 * Falls back to polling if WebSocket is unavailable
 */

import { logInfo, logWarn, logError, logDebug } from './logger';

const POLLING_INTERVAL = 15000; // 15 seconds fallback polling
let pollingTimer = null;
let isWebSocketConnected = false;

/**
 * Initialize real-time now playing updates
 * @param {number} stationId - The radio station ID
 */
export function initializeNowPlaying(stationId) {
    if (!stationId) {
        logWarn('Station ID not provided for now playing updates');
        return;
    }

    // Try WebSocket first if Echo is available
    if (window.Echo) {
        setupWebSocket(stationId);
    } else {
        logInfo('Laravel Echo not available, using polling fallback');
        startPolling(stationId);
    }
}

/**
 * Setup WebSocket connection for real-time updates
 * @param {number} stationId
 */
function setupWebSocket(stationId) {
    try {
        const channel = window.Echo.channel(`radio.station.${stationId}`);

        channel.listen('.now-playing.updated', (data) => {
            logDebug('Received now playing update via WebSocket', data);
            isWebSocketConnected = true;
            
            // Stop polling if it was running
            if (pollingTimer) {
                clearInterval(pollingTimer);
                pollingTimer = null;
            }

            updateNowPlayingUI(data.now_playing);
        });

        // Listen for connection errors
        window.Echo.connector.pusher.connection.bind('error', (err) => {
            logWarn('WebSocket connection error, falling back to polling', err);
            isWebSocketConnected = false;
            startPolling(stationId);
        });

        window.Echo.connector.pusher.connection.bind('unavailable', () => {
            logWarn('WebSocket unavailable, falling back to polling');
            isWebSocketConnected = false;
            startPolling(stationId);
        });

        // Check connection state after a few seconds
        setTimeout(() => {
            if (!isWebSocketConnected) {
                logInfo('WebSocket did not connect, using polling');
                startPolling(stationId);
            }
        }, 3000);
    } catch (error) {
        logError('Failed to setup WebSocket:', error);
        startPolling(stationId);
    }
}

/**
 * Start polling for now playing updates (fallback)
 * @param {number} stationId
 */
function startPolling(stationId) {
    // Don't start polling if already running
    if (pollingTimer) return;

    logInfo('Starting now playing polling');
    
    // Initial fetch
    fetchNowPlaying(stationId);

    // Poll every 15 seconds
    pollingTimer = setInterval(() => {
        fetchNowPlaying(stationId);
    }, POLLING_INTERVAL);
}

/**
 * Fetch now playing data from API
 * @param {number} stationId
 */
async function fetchNowPlaying(stationId) {
    try {
        const response = await fetch(`/api/nowplaying`);
        if (!response.ok) throw new Error('Failed to fetch now playing');
        
        const data = await response.json();
        updateNowPlayingUI(data);
    } catch (error) {
        logError('Error fetching now playing:', error);
    }
}

/**
 * Update the now playing UI with new data
 * @param {Object} data - Now playing data
 */
function updateNowPlayingUI(data) {
    if (!data || !data.current_song) return;

    const song = data.current_song;

    // Update song title
    const titleEl = document.querySelector('[data-song-title]');
    if (titleEl && song.title) {
        titleEl.textContent = song.title;
    }

    // Update artist
    const artistEl = document.querySelector('[data-song-artist]');
    if (artistEl && song.artist) {
        artistEl.textContent = song.artist;
    }

    // Update album artwork
    const artworkEl = document.querySelector('.now-playing-art');
    if (artworkEl && song.art) {
        artworkEl.src = song.art;
        artworkEl.alt = `${song.title} by ${song.artist}`;
    }

    // Update progress bar if available
    if (data.elapsed && data.duration) {
        const progressEl = document.querySelector('.song-progress');
        if (progressEl) {
            const percentage = (data.elapsed / data.duration) * 100;
            progressEl.style.width = `${percentage}%`;
        }
    }

    // Update listener count
    if (data.listeners !== undefined) {
        const listenersEl = document.querySelector('[data-listeners]');
        if (listenersEl) {
            listenersEl.textContent = data.listeners;
        }
    }

    // Update next song if available
    if (data.next_song) {
        const nextTitleEl = document.querySelector('[data-next-song-title]');
        const nextArtistEl = document.querySelector('[data-next-song-artist]');
        
        if (nextTitleEl && data.next_song.title) {
            nextTitleEl.textContent = data.next_song.title;
        }
        if (nextArtistEl && data.next_song.artist) {
            nextArtistEl.textContent = data.next_song.artist;
        }
    }

    // Dispatch custom event for other components
    window.dispatchEvent(new CustomEvent('nowPlayingUpdated', {
        detail: data
    }));
}

/**
 * Cleanup function to stop updates
 */
export function cleanup() {
    if (pollingTimer) {
        clearInterval(pollingTimer);
        pollingTimer = null;
    }

    if (window.Echo && isWebSocketConnected) {
        window.Echo.leaveChannel(`radio.station.*`);
    }
}
