/**
 * Keyboard Shortcuts Module
 * Provides keyboard controls for the audio player
 */

export function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Don't trigger shortcuts when typing in input fields
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) {
            return;
        }

        const playerComponent = document.querySelector('[x-data]');
        if (!playerComponent || !playerComponent.__x) return;

        const player = playerComponent.__x.$data;

        switch(e.key.toLowerCase()) {
            case ' ':
            case 'k':
                // Space or K to play/pause
                e.preventDefault();
                if (player.togglePlayback) {
                    player.togglePlayback();
                }
                break;

            case 'arrowup':
                // Arrow up to increase volume
                e.preventDefault();
                if (player.setVolume) {
                    const newVolume = Math.min(100, player.volume + 5);
                    player.setVolume(newVolume);
                    showShortcutFeedback(`Volume: ${newVolume}%`);
                }
                break;

            case 'arrowdown':
                // Arrow down to decrease volume
                e.preventDefault();
                if (player.setVolume) {
                    const newVolume = Math.max(0, player.volume - 5);
                    player.setVolume(newVolume);
                    showShortcutFeedback(`Volume: ${newVolume}%`);
                }
                break;

            case 'm':
                // M to mute/unmute
                e.preventDefault();
                if (player.toggleMute) {
                    player.toggleMute();
                    showShortcutFeedback(player.isMuted ? 'Muted' : 'Unmuted');
                }
                break;
        }
    });

    console.log('⌨️ Keyboard shortcuts initialized: Space/K (play/pause), ↑↓ (volume), M (mute)');
}

function showShortcutFeedback(message) {
    if (window.showToast) {
        window.showToast('info', message);
    }
}
