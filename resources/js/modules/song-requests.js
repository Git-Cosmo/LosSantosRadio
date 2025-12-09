document.addEventListener('DOMContentLoaded', function() {
const requestRoute = document.querySelector('[data-request-route]')?.dataset.requestRoute || '/requests';
function requestSong(event, songId, title, artist) {
            const btn = event.target.closest('button');
            if (!btn) return;
            
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Requesting...';

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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Requested';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-secondary');

                    // Show success message
                    showToast('success', data.message);
                    
                    // Reload page after 2 seconds to update queue
                    setTimeout(() => location.reload(), 2000);
                } else {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    showToast('error', data.error || 'Request failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                showToast('error', 'An error occurred. Please try again.');
            });
        }

        function showToast(type, message) {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'linear-gradient(135deg, #43b581, #38a169)' : 'linear-gradient(135deg, #f04747, #dc2626)';
            toast.style.cssText = `
                position: fixed; 
                bottom: 24px; 
                right: 24px; 
                z-index: 10000; 
                max-width: 400px; 
                padding: 1rem 1.5rem;
                background: ${bgColor};
                color: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                font-weight: 600;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            `;
            const icon = type === 'success' ? '<i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>' : '<i class="fas fa-exclamation-circle" style="font-size: 1.25rem;"></i>';
            toast.innerHTML = icon + '<span>' + message + '</span>';

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
});
