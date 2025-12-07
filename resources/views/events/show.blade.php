<x-layouts.app>
    <x-slot name="title">{{ $event->title }}</x-slot>

    <div style="max-width: 900px; margin: 0 auto; padding: 0 1rem;">
        <!-- Enhanced Back Button -->
        <a href="{{ route('events.index') }}" class="event-back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Events</span>
        </a>

        <div class="card" style="border: none; box-shadow: 0 4px 24px rgba(0,0,0,0.1); overflow: hidden;">
            <!-- Enhanced Header Image -->
            @if($event->image)
                <div style="position: relative; width: 100%; height: 400px; background: url('{{ e($event->image) }}') center/cover;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.8) 100%);"></div>
                    <!-- Floating Badges on Image -->
                    <div style="position: absolute; top: 1.5rem; left: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <span class="badge badge-{{ $event->event_type === 'live_show' ? 'primary' : ($event->event_type === 'contest' ? 'warning' : 'gray') }}" style="padding: 0.5rem 1rem; font-size: 0.875rem; backdrop-filter: blur(10px); font-weight: 600;">
                            <i class="fas fa-{{ $event->event_type === 'live_show' ? 'microphone' : ($event->event_type === 'contest' ? 'trophy' : 'calendar') }}" style="margin-right: 0.5rem;"></i>
                            {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                        </span>
                        @if($event->isOngoing())
                            <span class="badge badge-live pulse-animation" style="padding: 0.5rem 1rem; font-size: 0.875rem; backdrop-filter: blur(10px); font-weight: 700;">
                                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.5rem;"></i>
                                LIVE NOW
                            </span>
                        @elseif($event->isUpcoming())
                            <span class="badge badge-success" style="padding: 0.5rem 1rem; font-size: 0.875rem; backdrop-filter: blur(10px); font-weight: 600;">
                                <i class="fas fa-calendar-check" style="margin-right: 0.5rem;"></i>
                                Upcoming
                            </span>
                        @else
                            <span class="badge badge-gray" style="padding: 0.5rem 1rem; font-size: 0.875rem; backdrop-filter: blur(10px);">
                                <i class="fas fa-history" style="margin-right: 0.5rem;"></i>
                                Past Event
                            </span>
                        @endif
                    </div>
                    <!-- Title on Image -->
                    <div style="position: absolute; bottom: 2rem; left: 2rem; right: 2rem;">
                        <h1 style="font-size: 2.5rem; font-weight: 700; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.5); margin: 0; line-height: 1.2;">{{ $event->title }}</h1>
                    </div>
                </div>
            @else
                <div style="position: relative; width: 100%; height: 300px; background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%);">
                    <!-- Animated Background Pattern -->
                    <div style="position: absolute; inset: 0; opacity: 0.1; background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-star" style="font-size: 6rem; color: rgba(255,255,255,0.2);"></i>
                    </div>
                    <!-- Floating Badges -->
                    <div style="position: absolute; top: 1.5rem; left: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <span class="badge" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); color: white; font-weight: 600;">
                            <i class="fas fa-{{ $event->event_type === 'live_show' ? 'microphone' : ($event->event_type === 'contest' ? 'trophy' : 'calendar') }}" style="margin-right: 0.5rem;"></i>
                            {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                        </span>
                        @if($event->isOngoing())
                            <span class="badge pulse-animation" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); color: white; font-weight: 700;">
                                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.5rem;"></i>
                                LIVE NOW
                            </span>
                        @elseif($event->isUpcoming())
                            <span class="badge" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); color: white; font-weight: 600;">
                                <i class="fas fa-calendar-check" style="margin-right: 0.5rem;"></i>
                                Upcoming
                            </span>
                        @endif
                    </div>
                    <!-- Title -->
                    <div style="position: absolute; bottom: 2rem; left: 2rem; right: 2rem;">
                        <h1 style="font-size: 2.5rem; font-weight: 700; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3); margin: 0; line-height: 1.2;">{{ $event->title }}</h1>
                    </div>
                </div>
            @endif

            <div class="card-body" style="padding: 2rem;">
                <!-- Enhanced Event Details Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Start Time Card -->
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, rgba(88, 166, 255, 0.1) 0%, rgba(139, 92, 246, 0.05) 100%); border-radius: 12px; border-left: 4px solid var(--color-accent);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="width: 40px; height: 40px; background: var(--color-accent); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: white; font-size: 1.125rem;"></i>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Start Time</span>
                        </div>
                        <div style="font-size: 1.125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.25rem;">{{ $event->starts_at->format('l, F j, Y') }}</div>
                        <div style="font-size: 1rem; color: var(--color-accent); font-weight: 500;">{{ $event->starts_at->format('g:i A T') }}</div>
                        <div style="font-size: 0.875rem; color: var(--color-text-secondary); margin-top: 0.5rem;">
                            <i class="fas fa-hourglass-start" style="margin-right: 0.25rem;"></i>
                            {{ $event->starts_at->diffForHumans() }}
                        </div>
                    </div>

                    @if($event->ends_at)
                    <!-- End Time Card -->
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border-radius: 12px; border-left: 4px solid var(--color-success);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="width: 40px; height: 40px; background: var(--color-success); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-flag-checkered" style="color: white; font-size: 1.125rem;"></i>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">End Time</span>
                        </div>
                        <div style="font-size: 1.125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.25rem;">{{ $event->ends_at->format('l, F j, Y') }}</div>
                        <div style="font-size: 1rem; color: var(--color-success); font-weight: 500;">{{ $event->ends_at->format('g:i A T') }}</div>
                        <div style="font-size: 0.875rem; color: var(--color-text-secondary); margin-top: 0.5rem;">
                            <i class="fas fa-stopwatch" style="margin-right: 0.25rem;"></i>
                            Duration: {{ $event->starts_at->diffInHours($event->ends_at) }}h
                        </div>
                    </div>
                    @endif

                    @if($event->location)
                    <!-- Location Card -->
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%); border-radius: 12px; border-left: 4px solid #fbbf24;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="width: 40px; height: 40px; background: #fbbf24; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.125rem;"></i>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Location</span>
                        </div>
                        <div style="font-size: 1.125rem; font-weight: 600; color: var(--color-text); line-height: 1.4;">{{ $event->location }}</div>
                    </div>
                    @endif
                </div>

                <!-- About Section with Better Typography -->
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; color: var(--color-text);">
                        <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                        About This Event
                    </h2>
                    <div style="color: var(--color-text-secondary); line-height: 1.8; font-size: 1rem; padding: 1.5rem; background: var(--color-bg-secondary); border-radius: 12px; border-left: 4px solid var(--color-accent);">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <!-- Enhanced Event Actions -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <!-- Like Button -->
                    <button id="event-like-btn" data-event-id="{{ $event->id }}" class="event-like-btn">
                        <i class="far fa-heart" id="like-icon" style="color: var(--color-text-muted); font-size: 1.25rem; transition: all 0.3s ease;"></i>
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <span id="like-text" style="font-size: 0.875rem; color: var(--color-text);">Like Event</span>
                            <span id="like-count" style="font-size: 1.25rem; font-weight: 700; color: var(--color-accent);">0</span>
                        </div>
                    </button>

                    @auth
                    <!-- Reminder Button -->
                    <button id="event-reminder-btn" data-event-id="{{ $event->id }}" class="event-reminder-btn">
                        <i class="far fa-bell" id="reminder-icon" style="color: var(--color-text-muted); font-size: 1.25rem; transition: all 0.3s ease;"></i>
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <span id="reminder-text" style="font-size: 0.875rem; color: var(--color-text);">Set Reminder</span>
                            <span style="font-size: 0.75rem; color: var(--color-text-muted);">Get notified</span>
                        </div>
                    </button>
                    @endauth
                </div>

                @guest
                <div style="padding: 1.5rem; background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, var(--color-bg-secondary) 100%); border-radius: 12px; margin-bottom: 2rem; text-align: center; border: 2px dashed var(--color-border);">
                    <i class="fas fa-user-circle" style="font-size: 2.5rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <p style="color: var(--color-text); font-size: 1rem; margin-bottom: 0.5rem; font-weight: 600;">
                        Want to set reminders for this event?
                    </p>
                    <p style="color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 1rem;">
                        <a href="{{ route('login') }}" style="color: var(--color-accent); text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Log in</a> or <a href="{{ route('register') }}" style="color: var(--color-accent); text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">create an account</a> to get event notifications
                    </p>
                </div>
                @endguest

                @if($event->isUpcoming())
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, rgba(88, 166, 255, 0.15) 0%, rgba(139, 92, 246, 0.1) 100%); border: 2px solid var(--color-accent); border-radius: 12px; text-align: center; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); animation: shimmer 2s infinite;"></div>
                        <div style="position: relative; z-index: 1;">
                            <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: var(--color-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bell" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <p style="color: var(--color-accent); font-weight: 600; font-size: 1.125rem; margin-bottom: 0.5rem;">
                                Event starts {{ $event->starts_at->diffForHumans() }}
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary);">
                                Don't miss it! Join our Discord community to get instant reminders and updates.
                            </p>
                        </div>
                    </div>
                @elseif($event->isOngoing())
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(16, 185, 129, 0.1) 100%); border: 2px solid var(--color-success); border-radius: 12px; text-align: center; position: relative; overflow: hidden;">
                        <div class="pulse-animation" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle, rgba(34, 197, 94, 0.2) 0%, transparent 70%);"></div>
                        <div style="position: relative; z-index: 1;">
                            <div class="pulse-animation" style="width: 60px; height: 60px; margin: 0 auto 1rem; background: var(--color-success); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-play-circle" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                            <p style="color: var(--color-success); font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">
                                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.5rem;"></i>
                                This event is happening now!
                            </p>
                            @if($event->ends_at)
                                <p style="font-size: 0.875rem; color: var(--color-text-secondary);">
                                    <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                                    Ends {{ $event->ends_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($event->creator)
            <div class="card" style="margin-top: 1.5rem; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <div class="card-body" style="padding: 1.5rem;">
                    <p style="font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-user-circle" style="margin-right: 0.5rem;"></i>
                        Event Organizer
                    </p>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="{{ $event->creator->avatar_url }}" alt="{{ $event->creator->name }}" style="width: 56px; height: 56px; border-radius: 50%; border: 3px solid var(--color-accent); box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3);">
                        <div style="flex: 1;">
                            <p style="font-weight: 600; font-size: 1.125rem; margin-bottom: 0.25rem; color: var(--color-text);">{{ $event->creator->name }}</p>
                            <p style="font-size: 0.875rem; color: var(--color-text-muted); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-calendar-plus" style="color: var(--color-accent);"></i>
                                Posted {{ $event->created_at->format('M j, Y') }}
                            </p>
                        </div>
                        <a href="{{ route('profile.show', $event->creator->username) }}" class="event-creator-link">
                            View Profile
                            <i class="fas fa-arrow-right"></i>
                        </a>
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
    </script>
    @endpush
</x-layouts.app>
