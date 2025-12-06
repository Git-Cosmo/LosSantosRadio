/**
 * Radio Player Module
 * Handles audio playback and now playing updates for the radio stream
 */

// Configuration constants
const NOW_PLAYING_REFRESH_INTERVAL = 10000; // 10 seconds

let audioPlayer = null;
let isPlaying = false;

/**
 * Toggle playback of the radio stream
 * @param {string} streamUrl - The URL of the audio stream
 */
export function togglePlayback(streamUrl) {
    if (!streamUrl) {
        window.showToast?.('error', 'Stream URL not available');
        return;
    }

    if (!audioPlayer) {
        audioPlayer = new Audio(streamUrl);
        audioPlayer.addEventListener('playing', updatePlayState);
        audioPlayer.addEventListener('pause', updatePauseState);
        audioPlayer.addEventListener('ended', updatePauseState);
    }

    if (isPlaying) {
        audioPlayer.pause();
    } else {
        audioPlayer.play();
    }
}

/**
 * Update UI to playing state
 */
function updatePlayState() {
    isPlaying = true;
    const btn = document.getElementById('play-btn');
    const nowPlayingEl = document.getElementById('now-playing');

    if (btn) btn.innerHTML = '<i class="fas fa-pause"></i> Stop Listening';
    if (nowPlayingEl) nowPlayingEl.classList.add('is-playing');
}

/**
 * Update UI to paused state
 */
function updatePauseState() {
    isPlaying = false;
    const btn = document.getElementById('play-btn');
    const nowPlayingEl = document.getElementById('now-playing');

    if (btn) btn.innerHTML = '<i class="fas fa-play"></i> Listen Live';
    if (nowPlayingEl) nowPlayingEl.classList.remove('is-playing');
}

/**
 * Rate a song (upvote/downvote)
 * @param {number} rating - 1 for upvote, -1 for downvote
 */
export function rateSong(rating) {
    const ratingEl = document.getElementById('song-rating');
    if (!ratingEl) return;

    const songId = ratingEl.dataset.songId;
    const songTitle = ratingEl.dataset.songTitle;
    const songArtist = ratingEl.dataset.songArtist;

    fetch('/api/ratings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            song_id: songId,
            song_title: songTitle,
            song_artist: songArtist,
            rating: rating
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('upvote-count').textContent = data.data.upvotes;
            document.getElementById('downvote-count').textContent = data.data.downvotes;

            // Update button states
            const upvoteBtn = document.querySelector('.rating-btn.upvote');
            const downvoteBtn = document.querySelector('.rating-btn.downvote');

            upvoteBtn.classList.remove('active');
            downvoteBtn.classList.remove('active');

            if (data.action !== 'removed') {
                if (rating === 1) upvoteBtn.classList.add('active');
                if (rating === -1) downvoteBtn.classList.add('active');
            }

            // Show toast notification
            if (data.action === 'removed') {
                window.showToast?.('info', 'Rating removed');
            } else if (data.action === 'created' || data.action === 'updated') {
                window.showToast?.('success', rating === 1 ? 'Song liked!' : 'Song disliked');
            }
        }
    })
    .catch(err => {
        console.error(err);
        window.showToast?.('error', 'Failed to rate song. Please try again.');
    });
}

/**
 * Load rating data for current song
 */
export function loadSongRating() {
    const ratingEl = document.getElementById('song-rating');
    if (!ratingEl) return;

    const songId = ratingEl.dataset.songId;
    if (!songId) return;

    fetch(`/api/ratings/song/${encodeURIComponent(songId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('upvote-count').textContent = data.data.upvotes;
                document.getElementById('downvote-count').textContent = data.data.downvotes;

                if (data.data.user_rating === 1) {
                    document.querySelector('.rating-btn.upvote').classList.add('active');
                } else if (data.data.user_rating === -1) {
                    document.querySelector('.rating-btn.downvote').classList.add('active');
                }
            }
        })
        .catch((err) => {
            console.error('Failed to load song rating:', err);
        });
}

/**
 * Load trending songs
 */
export function loadTrendingSongs() {
    fetch('/api/ratings/trending')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('trending-songs');
            if (!container) return;

            if (data.success && data.data.length > 0) {
                container.innerHTML = data.data.map((song, index) => `
                    <div class="trending-item">
                        <span class="trending-rank">#${index + 1}</span>
                        <div class="trending-info">
                            <p class="trending-title">${escapeHtml(song.song_title)}</p>
                            <p class="trending-artist">${escapeHtml(song.song_artist)}</p>
                        </div>
                        <span class="trending-score">
                            <i class="fas fa-heart" style="color: #ef4444;"></i>
                            ${song.score}
                        </span>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">No trending songs yet. Rate songs to see them here!</p>';
            }
        })
        .catch(() => {
            const container = document.getElementById('trending-songs');
            if (container) {
                container.innerHTML = '<p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">Unable to load trending songs.</p>';
            }
        });
}

/**
 * Format time in mm:ss format
 * @param {number} seconds - Time in seconds
 * @returns {string} Formatted time string
 */
export function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
}

/**
 * Escape HTML to prevent XSS
 * @param {string} text - Text to escape
 * @returns {string} Escaped HTML string
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Handle now playing updates from the custom event
 */
export function handleNowPlayingUpdate(data) {
    // Update song info
    const songTitle = document.getElementById('song-title');
    const songArtist = document.getElementById('song-artist');
    if (songTitle) songTitle.textContent = data.current_song.title;
    if (songArtist) songArtist.textContent = data.current_song.artist;

    // Update listener count
    const listenerCount = document.querySelector('.listeners-count');
    if (listenerCount && data.listeners !== undefined) {
        listenerCount.innerHTML = '<i class="fas fa-headphones"></i> ' + data.listeners + ' listeners';
    }

    // Update rating data attributes and reload
    const ratingEl = document.getElementById('song-rating');
    if (ratingEl && ratingEl.dataset.songId !== data.current_song.id) {
        ratingEl.dataset.songId = data.current_song.id;
        ratingEl.dataset.songTitle = data.current_song.title;
        ratingEl.dataset.songArtist = data.current_song.artist;
        loadSongRating();
    }

    // Update progress
    const progressFill = document.getElementById('progress-fill');
    if (progressFill) {
        const progress = data.duration > 0 ? (data.elapsed / data.duration) * 100 : 0;
        progressFill.style.width = progress + '%';
    }

    // Update times
    const elapsedTime = document.getElementById('elapsed-time');
    const totalTime = document.getElementById('total-time');
    if (elapsedTime) elapsedTime.textContent = formatTime(data.elapsed);
    if (totalTime) totalTime.textContent = formatTime(data.duration);
}

/**
 * Auto-refresh now playing data
 */
export function startNowPlayingRefresh() {
    // Initial update
    updateNowPlaying();
    // Refresh at configured interval
    setInterval(updateNowPlaying, NOW_PLAYING_REFRESH_INTERVAL);
}

/**
 * Fetch and dispatch now playing update
 */
function updateNowPlaying() {
    fetch('/api/radio/now-playing')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const event = new CustomEvent('nowPlayingUpdate', { detail: data.data });
                document.dispatchEvent(event);
            }
        })
        .catch((err) => {
            console.error('Failed to update now playing:', err);
        });
}

// Make functions available globally
window.togglePlayback = togglePlayback;
window.rateSong = rateSong;
window.loadSongRating = loadSongRating;
window.loadTrendingSongs = loadTrendingSongs;
window.formatTime = formatTime;
