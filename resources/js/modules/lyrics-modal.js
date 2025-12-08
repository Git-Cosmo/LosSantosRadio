/**
 * Lyrics Modal Module
 * Handles displaying lyrics with guest limit enforcement and signup prompts
 */

import { logError } from './logger';

let lyricsModalElement = null;
let guestLimitModalElement = null;
let currentSessionStatus = null;

/**
 * Initialize lyrics modal functionality
 */
export function initializeLyricsModal() {
    // Create modals if they don't exist
    if (!document.getElementById('lyrics-modal')) {
        createLyricsModal();
    }
    if (!document.getElementById('guest-limit-modal')) {
        createGuestLimitModal();
    }

    lyricsModalElement = document.getElementById('lyrics-modal');
    guestLimitModalElement = document.getElementById('guest-limit-modal');

    // Fetch initial session status
    fetchLyricsStatus();

    // Add click handlers to lyrics buttons
    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-lyrics-song-id]')) {
            const btn = e.target.closest('[data-lyrics-song-id]');
            const songId = btn.dataset.lyricsSongId;
            const title = btn.dataset.lyricsTitle || 'Unknown';
            const artist = btn.dataset.lyricsArtist || 'Unknown';
            
            showLyrics(songId, title, artist);
        }
    });
}

/**
 * Fetch current lyrics viewing status
 */
async function fetchLyricsStatus() {
    try {
        const response = await fetch('/api/lyrics/status');
        const data = await response.json();
        currentSessionStatus = data;
        
        // Update UI indicators if they exist
        updateStatusIndicators(data);
    } catch (error) {
        logError('Failed to fetch lyrics status:', error);
    }
}

/**
 * Show lyrics for a song
 */
export async function showLyrics(songId, title, artist) {
    try {
        // Show loading state
        showLoadingModal();

        const response = await fetch(`/api/lyrics/${songId}?title=${encodeURIComponent(title)}&artist=${encodeURIComponent(artist)}`);
        const data = await response.json();

        if (response.status === 403) {
            // Guest limit reached
            hideLoadingModal();
            showGuestLimitModal(data);
            return;
        }

        if (!response.ok || !data.success) {
            hideLoadingModal();
            window.showToast('error', data.message || 'Failed to load lyrics');
            return;
        }

        // Display lyrics
        displayLyrics(data.lyrics, data.remaining);
        
        // Update status
        fetchLyricsStatus();

    } catch (error) {
        hideLoadingModal();
        logError('Error fetching lyrics:', error);
        window.showToast('error', 'An error occurred while loading lyrics');
    }
}

/**
 * Display lyrics in modal
 */
function displayLyrics(lyrics, remaining) {
    const modal = lyricsModalElement;
    const titleEl = modal.querySelector('#lyrics-title');
    const artistEl = modal.querySelector('#lyrics-artist');
    const contentEl = modal.querySelector('#lyrics-content');
    const statusEl = modal.querySelector('#lyrics-status');

    titleEl.textContent = lyrics.title;
    artistEl.textContent = lyrics.artist;

    // Format lyrics
    if (lyrics.lyrics && lyrics.lyrics.length > 0) {
        contentEl.innerHTML = lyrics.lyrics.map(line => `<p class="lyrics-line">${escapeHtml(line)}</p>`).join('');
    } else {
        contentEl.innerHTML = '<p class="text-muted">Lyrics not available for this song.</p>';
    }

    // Update status message for guests
    if (remaining !== null && remaining !== undefined) {
        statusEl.innerHTML = '';
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-info';
        alertDiv.innerHTML = '<i class="fas fa-info-circle"></i> ';
        
        const textSpan = document.createElement('span');
        textSpan.textContent = `You have ${remaining} free lyrics view${remaining !== 1 ? 's' : ''} remaining. `;
        alertDiv.appendChild(textSpan);
        
        const signInLink = document.createElement('a');
        signInLink.href = '/auth/discord';
        signInLink.className = 'alert-link';
        signInLink.textContent = 'Sign in';
        alertDiv.appendChild(signInLink);
        
        const unlimitedText = document.createTextNode(' for unlimited access!');
        alertDiv.appendChild(unlimitedText);
        
        statusEl.appendChild(alertDiv);
        statusEl.style.display = 'block';
    } else {
        statusEl.style.display = 'none';
    }

    // Show source attribution if available
    if (lyrics.source && lyrics.source_url) {
        const attribution = modal.querySelector('#lyrics-attribution');
        attribution.innerHTML = '';
        const small = document.createElement('small');
        small.className = 'text-muted';
        small.textContent = 'Lyrics from ';
        const link = document.createElement('a');
        link.href = escapeHtml(lyrics.source_url);
        link.target = '_blank';
        link.rel = 'noopener';
        link.textContent = escapeHtml(lyrics.source);
        small.appendChild(link);
        attribution.appendChild(small);
        attribution.style.display = 'block';
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/**
 * Show guest limit modal with signup messaging
 */
function showGuestLimitModal(data) {
    const modal = guestLimitModalElement;
    const messageEl = modal.querySelector('#guest-limit-message');
    
    // Customize message based on why they're limited
    let message = `
        <h2 class="modal-title"><i class="fas fa-music"></i> Love the Lyrics?</h2>
        <p class="lead">You've reached the free limit of 4 song lyrics per session.</p>
        <p>But here's the good news...</p>
    `;

    messageEl.innerHTML = message;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/**
 * Unlock lyrics for guest (after watching ad or waiting)
 */
export async function unlockGuestLyrics() {
    try {
        const response = await fetch('/api/lyrics/unlock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            window.showToast('success', data.message);
            closeGuestLimitModal();
            fetchLyricsStatus();
        } else {
            window.showToast('error', data.message);
        }
    } catch (error) {
        logError('Error unlocking lyrics:', error);
        window.showToast('error', 'Failed to unlock lyrics');
    }
}

/**
 * Create lyrics modal HTML
 */
function createLyricsModal() {
    const modal = document.createElement('div');
    modal.id = 'lyrics-modal';
    modal.className = 'modal lyrics-modal';
    modal.innerHTML = `
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h3 id="lyrics-title">Song Title</h3>
                    <p id="lyrics-artist" class="text-muted">Artist</p>
                </div>
                <button class="modal-close lyrics-close-btn" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="lyrics-status" style="display: none;"></div>
            <div class="modal-body">
                <div id="lyrics-content" class="lyrics-content"></div>
                <div id="lyrics-attribution" style="display: none; margin-top: 1rem;"></div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    // Add event listeners
    modal.querySelector('.modal-overlay').addEventListener('click', closeLyricsModal);
    modal.querySelector('.lyrics-close-btn').addEventListener('click', closeLyricsModal);
}

/**
 * Create guest limit modal HTML
 */
function createGuestLimitModal() {
    const modal = document.createElement('div');
    modal.id = 'guest-limit-modal';
    modal.className = 'modal guest-limit-modal';
    modal.innerHTML = `
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <button class="modal-close guest-limit-close-btn" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-body text-center">
                <div id="guest-limit-message"></div>
                
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <i class="fas fa-infinity"></i>
                        <h4>Unlimited Lyrics</h4>
                        <p>View lyrics for all your favorite songs</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-heart"></i>
                        <h4>Save Favorites</h4>
                        <p>Bookmark songs and create playlists</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-trophy"></i>
                        <h4>Earn Rewards</h4>
                        <p>Level up and unlock achievements</p>
                    </div>
                </div>

                <div class="signup-options">
                    <h3>Join us — it's 100% free!</h3>
                    <p class="text-muted">Choose your preferred login method:</p>
                    
                    <div class="auth-buttons">
                        <a href="/auth/discord" class="btn btn-discord">
                            <i class="fab fa-discord"></i> Continue with Discord
                        </a>
                        <a href="/auth/twitch" class="btn btn-twitch">
                            <i class="fab fa-twitch"></i> Continue with Twitch
                        </a>
                        <a href="/auth/steam" class="btn btn-steam">
                            <i class="fab fa-steam"></i> Continue with Steam
                        </a>
                        <a href="/auth/battlenet" class="btn btn-battlenet">
                            <i class="fas fa-gamepad"></i> Continue with Battle.net
                        </a>
                    </div>

                    <div class="or-divider">
                        <span>or</span>
                    </div>

                    <button class="btn btn-outline unlock-lyrics-btn">
                        <i class="fas fa-clock"></i> Wait 10 minutes for temporary unlock
                    </button>

                    <p class="small-text text-muted">
                        No credit card required. We respect your privacy and never spam.
                    </p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    // Add event listeners
    modal.querySelector('.modal-overlay').addEventListener('click', closeGuestLimitModal);
    modal.querySelector('.guest-limit-close-btn').addEventListener('click', closeGuestLimitModal);
    modal.querySelector('.unlock-lyrics-btn').addEventListener('click', unlockGuestLyrics);
}

/**
 * Show loading modal
 */
function showLoadingModal() {
    const loading = document.createElement('div');
    loading.id = 'lyrics-loading';
    loading.className = 'modal active';
    loading.innerHTML = `
        <div class="modal-overlay"></div>
        <div class="modal-container text-center" style="max-width: 300px;">
            <div class="spinner-border" role="status">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
            </div>
            <p class="mt-3">Loading lyrics...</p>
        </div>
    `;
    document.body.appendChild(loading);
}

/**
 * Hide loading modal
 */
function hideLoadingModal() {
    const loading = document.getElementById('lyrics-loading');
    if (loading) {
        loading.remove();
    }
}

/**
 * Close lyrics modal
 */
function closeLyricsModal() {
    if (lyricsModalElement) {
        lyricsModalElement.classList.remove('active');
        document.body.style.overflow = '';
    }
}

/**
 * Close guest limit modal
 */
function closeGuestLimitModal() {
    if (guestLimitModalElement) {
        guestLimitModalElement.classList.remove('active');
        document.body.style.overflow = '';
    }
}

/**
 * Update status indicators throughout the page
 */
function updateStatusIndicators(status) {
    // Update any status displays on the page
    const indicators = document.querySelectorAll('[data-lyrics-status]');
    indicators.forEach(el => {
        if (status.is_registered) {
            el.textContent = 'Unlimited';
            el.className = 'badge badge-success';
        } else if (status.is_unlocked) {
            el.textContent = 'Unlocked';
            el.className = 'badge badge-success';
        } else if (status.remaining !== undefined) {
            el.textContent = `${status.remaining} left`;
            el.className = status.remaining > 0 ? 'badge badge-warning' : 'badge badge-danger';
        }
    });
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
