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
</x-layouts.app>
