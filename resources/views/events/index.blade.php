<x-layouts.app>
    <x-slot name="title">Events</x-slot>

    <div class="hero-section" style="padding: 2rem; margin-bottom: 2rem;">
        <div class="hero-content">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">
                <i class="fas fa-calendar-star" style="color: var(--color-accent);"></i>
                Events & Happenings
            </h1>
            <p style="color: var(--color-text-secondary);">
                Stay tuned for live shows, contests, and community events!
            </p>
        </div>
    </div>

    @if($featuredEvents->count() > 0)
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                    Featured Events
                </h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-3">
                    @foreach($featuredEvents as $event)
                        <div class="card" style="border-color: var(--color-accent);">
                            <div class="card-body">
                                <span class="badge badge-warning" style="margin-bottom: 0.5rem;">
                                    {{ ucfirst($event->event_type) }}
                                </span>
                                <h3 style="font-size: 1.125rem; margin-bottom: 0.5rem;">{{ $event->title }}</h3>
                                <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                                    {{ Str::limit($event->description, 100) }}
                                </p>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                    <i class="fas fa-clock"></i>
                                    {{ $event->starts_at->format('M j, Y \a\t g:i A') }}
                                </div>
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="grid" style="grid-template-columns: 2fr 1fr;">
        <div>
            @if($ongoingEvents->count() > 0)
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-play-circle" style="color: var(--color-success);"></i>
                            Happening Now
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach($ongoingEvents as $event)
                            <div class="schedule-item active" style="margin-bottom: 0.75rem;">
                                <div class="schedule-time">
                                    <span class="badge badge-live">LIVE</span>
                                </div>
                                <div class="schedule-info">
                                    <h4 class="schedule-title">{{ $event->title }}</h4>
                                    <p class="schedule-desc">
                                        @if($event->ends_at)
                                            Ends {{ $event->ends_at->diffForHumans() }}
                                        @else
                                            In progress
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                        Upcoming Events
                    </h2>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        @foreach($upcomingEvents as $event)
                            <div class="schedule-item" style="margin-bottom: 0.75rem;">
                                <div class="schedule-time">
                                    <span class="schedule-hour">{{ $event->starts_at->format('M j') }}</span>
                                </div>
                                <div class="schedule-info">
                                    <h4 class="schedule-title">{{ $event->title }}</h4>
                                    <p class="schedule-desc">
                                        {{ $event->starts_at->format('g:i A') }}
                                        @if($event->location)
                                            · {{ $event->location }}
                                        @endif
                                    </p>
                                </div>
                                <span class="badge badge-{{ $event->event_type === 'live_show' ? 'primary' : 'gray' }}">
                                    {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                </span>
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem;">
                            <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            No upcoming events at the moment. Check back soon!
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                        Event Types
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <i class="fas fa-microphone" style="color: var(--color-accent); width: 20px;"></i>
                            <span style="font-size: 0.875rem;">Live Shows</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <i class="fas fa-trophy" style="color: #fbbf24; width: 20px;"></i>
                            <span style="font-size: 0.875rem;">Contests & Giveaways</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <i class="fas fa-users" style="color: var(--color-success); width: 20px;"></i>
                            <span style="font-size: 0.875rem;">Community Meetups</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <i class="fas fa-star" style="color: #a855f7; width: 20px;"></i>
                            <span style="font-size: 0.875rem;">Special Events</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fab fa-discord" style="color: #5865F2;"></i>
                        Get Notified
                    </h2>
                </div>
                <div class="card-body">
                    <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                        Join our Discord to get event notifications and reminders!
                    </p>
                    <a href="#" class="btn btn-discord" style="width: 100%;">
                        <i class="fab fa-discord"></i> Join Discord
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
