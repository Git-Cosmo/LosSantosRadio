document.addEventListener('DOMContentLoaded', function() {
// Event Like Functionality
        const likeBtn = document.getElementById('event-like-btn');
        const likeIcon = document.getElementById('like-icon');
        const likeText = document.getElementById('like-text');
        const likeCount = document.getElementById('like-count');

        if (likeBtn && likeIcon && likeText && likeCount) {
            const eventId = likeBtn.dataset.eventId;

            // Load initial like status
            fetch(`/events/${eventId}/like/status`)
                .then(response => response.json())
                .then(data => {
                    updateLikeUI(data.liked, data.likes_count);
                })
                .catch(error => console.error('Error loading like status:', error));

            likeBtn.addEventListener('click', function() {
                fetch(`/events/${eventId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateLikeUI(data.liked, data.likes_count);
                    }
                })
                .catch(error => {
                    console.error('Error toggling like:', error);
                    showNotification('Failed to update like status. Please try again.', 'error');
                });
            });

            function updateLikeUI(liked, count) {
                likeCount.textContent = count;
                if (liked) {
                    likeIcon.style.color = '#ef4444';
                    likeIcon.classList.remove('far');
                    likeIcon.classList.add('fas');
                    likeText.textContent = 'Liked';
                    likeBtn.style.borderColor = '#ef4444';
                    likeBtn.style.background = 'rgba(239, 68, 68, 0.1)';
                } else {
                    likeIcon.style.color = 'var(--color-text-muted)';
                    likeIcon.classList.remove('fas');
                    likeIcon.classList.add('far');
                    likeText.textContent = 'Like';
                    likeBtn.style.borderColor = 'transparent';
                    likeBtn.style.background = 'var(--color-bg-tertiary)';
                }
            }
        }

        @auth
        // Event Reminder Functionality
        const reminderBtn = document.getElementById('event-reminder-btn');
        const reminderIcon = document.getElementById('reminder-icon');
        const reminderText = document.getElementById('reminder-text');

        if (reminderBtn && reminderIcon && reminderText) {
            const eventId = reminderBtn.dataset.eventId;

            // Load initial reminder status
            fetch(`/events/${eventId}/reminder/status`)
                .then(response => response.json())
                .then(data => {
                    updateReminderUI(data.subscribed);
                })
                .catch(error => console.error('Error loading reminder status:', error));

            reminderBtn.addEventListener('click', function() {
                const isSubscribed = reminderBtn.dataset.subscribed === 'true';
                const method = isSubscribed ? 'DELETE' : 'POST';
                const userEmail = '{{ auth()->user()->email ?? "" }}';

                fetch(`/events/${eventId}/reminder`, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: method === 'POST' ? JSON.stringify({ email: userEmail }) : undefined
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateReminderUI(data.subscribed);
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error toggling reminder:', error);
                    showNotification('Failed to update reminder status. Please try again.', 'error');
                });
            });

            function updateReminderUI(subscribed) {
                reminderBtn.dataset.subscribed = subscribed;
                if (subscribed) {
                    reminderIcon.style.color = '#3fb950';
                    reminderIcon.classList.remove('far');
                    reminderIcon.classList.add('fas');
                    reminderText.textContent = 'Reminder Set';
                    reminderBtn.style.borderColor = '#3fb950';
                    reminderBtn.style.background = 'rgba(63, 185, 80, 0.1)';
                } else {
                    reminderIcon.style.color = 'var(--color-text-muted)';
                    reminderIcon.classList.remove('fas');
                    reminderIcon.classList.add('far');
                    reminderText.textContent = 'Set Reminder';
                    reminderBtn.style.borderColor = 'transparent';
                    reminderBtn.style.background = 'var(--color-bg-tertiary)';
                }
            }
        }
        @endauth

        // Simple notification function (can be replaced with a toast library)
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#3fb950' : '#ef4444'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.3s ease-out, transform 0.3s ease-out;
            `;
            document.body.appendChild(notification);
            
            // Trigger animation
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-20px)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
});
