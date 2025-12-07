<x-layouts.app>
    <x-slot name="title">{{ $event->title }}</x-slot>

    <div style="max-width: 800px; margin: 0 auto;">
        <a href="{{ route('events.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
            <i class="fas fa-arrow-left"></i> Back to Events
        </a>

        <div class="card">
            @if($event->image)
                <img src="{{ $event->image }}" alt="{{ $event->title }}" style="width: 100%; height: 300px; object-fit: cover;">
            @else
                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, var(--color-accent), #a855f7); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-star" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                </div>
            @endif

            <div class="card-body">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <span class="badge badge-{{ $event->event_type === 'live_show' ? 'primary' : ($event->event_type === 'contest' ? 'warning' : 'gray') }}">
                        {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                    </span>
                    @if($event->isOngoing())
                        <span class="badge badge-live">LIVE NOW</span>
                    @elseif($event->isUpcoming())
                        <span class="badge badge-success">Upcoming</span>
                    @else
                        <span class="badge badge-gray">Past Event</span>
                    @endif
                </div>

                <h1 style="font-size: 1.75rem; margin-bottom: 1rem;">{{ $event->title }}</h1>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                    <div>
                        <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
                            <i class="fas fa-clock"></i> Start Time
                        </p>
                        <p style="font-weight: 500;">{{ $event->starts_at->format('l, F j, Y') }}</p>
                        <p style="color: var(--color-accent);">{{ $event->starts_at->format('g:i A T') }}</p>
                    </div>
                    @if($event->ends_at)
                        <div>
                            <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
                                <i class="fas fa-flag-checkered"></i> End Time
                            </p>
                            <p style="font-weight: 500;">{{ $event->ends_at->format('l, F j, Y') }}</p>
                            <p style="color: var(--color-text-secondary);">{{ $event->ends_at->format('g:i A T') }}</p>
                        </div>
                    @endif
                    @if($event->location)
                        <div style="grid-column: span 2;">
                            <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
                                <i class="fas fa-map-marker-alt"></i> Location
                            </p>
                            <p style="font-weight: 500;">{{ $event->location }}</p>
                        </div>
                    @endif
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.125rem; margin-bottom: 0.75rem;">About This Event</h2>
                    <div style="color: var(--color-text-secondary); line-height: 1.7;">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <!-- Event Actions: Like and Reminder -->
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <!-- Like Button -->
                    <button id="event-like-btn" data-event-id="{{ $event->id }}" style="flex: 1; min-width: 200px; padding: 0.875rem 1.5rem; background: var(--color-bg-tertiary); border: 2px solid transparent; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s ease; font-weight: 600;">
                        <i class="far fa-heart" id="like-icon" style="color: var(--color-text-muted);"></i>
                        <span id="like-text">Like</span>
                        <span id="like-count" style="padding: 0.25rem 0.625rem; background: var(--color-bg); border-radius: 12px; font-size: 0.875rem;">0</span>
                    </button>

                    @auth
                    <!-- Reminder Button -->
                    <button id="event-reminder-btn" data-event-id="{{ $event->id }}" style="flex: 1; min-width: 200px; padding: 0.875rem 1.5rem; background: var(--color-bg-tertiary); border: 2px solid transparent; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; transition: all 0.3s ease; font-weight: 600;">
                        <i class="far fa-bell" id="reminder-icon" style="color: var(--color-text-muted);"></i>
                        <span id="reminder-text">Set Reminder</span>
                    </button>
                    @endauth
                </div>

                @guest
                <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
                    <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                        <a href="{{ route('login') }}" style="color: var(--color-accent); text-decoration: none; font-weight: 600;">Log in</a> to set reminders for this event
                    </p>
                </div>
                @endguest

                @if($event->isUpcoming())
                    <div style="padding: 1rem; background: rgba(88, 166, 255, 0.1); border: 1px solid var(--color-accent); border-radius: 8px; text-align: center;">
                        <p style="color: var(--color-accent); font-weight: 500; margin-bottom: 0.5rem;">
                            <i class="fas fa-bell"></i> Event starts {{ $event->starts_at->diffForHumans() }}
                        </p>
                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                            Don't miss it! Join our Discord to get reminders.
                        </p>
                    </div>
                @elseif($event->isOngoing())
                    <div style="padding: 1rem; background: rgba(63, 185, 80, 0.1); border: 1px solid var(--color-success); border-radius: 8px; text-align: center;">
                        <p style="color: var(--color-success); font-weight: 500; margin-bottom: 0.5rem;">
                            <i class="fas fa-play-circle"></i> This event is happening now!
                        </p>
                        @if($event->ends_at)
                            <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                                Ends {{ $event->ends_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if($event->creator)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body">
                    <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.5rem;">Posted by</p>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <img src="{{ $event->creator->avatar_url }}" alt="{{ $event->creator->name }}" style="width: 40px; height: 40px; border-radius: 50%;">
                        <div>
                            <p style="font-weight: 500;">{{ $event->creator->name }}</p>
                            <p style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $event->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
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

        if (reminderBtn && reminderIcon && reminderText && likeBtn) {
            const eventId = likeBtn.dataset.eventId;

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
                animation: slideIn 0.3s ease-out;
            `;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
    @endpush
</x-layouts.app>
