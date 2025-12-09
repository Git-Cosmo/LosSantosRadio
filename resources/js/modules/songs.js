document.addEventListener('DOMContentLoaded', function() {
/**
         * Request a song via the AzuraCast API.
         * 
         * @param {number|string} songId - The song ID to request
         * @param {string} title - The song title (for display)
         * @param {string} artist - The song artist (for display)
         * @param {HTMLElement} btn - The button element that was clicked
         */
        function requestSong(songId, title, artist, btn) {
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const requestRoute = document.querySelector('[data-request-route]')?.dataset.requestRoute || '/requests';
            fetch(requestRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    song_id: songId,
                    song_title: title,
                    song_artist: artist
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed with status ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Requested';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-secondary');
                    showToast('success', data.message || 'Song request submitted!');
                } else {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    showToast('error', data.error || 'Failed to request song');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                showToast('error', 'An error occurred. Please try again.');
            });
        }
});
