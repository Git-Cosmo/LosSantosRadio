<x-layouts.app>
    <x-slot name="title">Events</x-slot>

    <!-- Enhanced Hero Section with Gradient Background -->
    <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%); padding: 3rem 2rem; margin-bottom: 2rem; border-radius: 12px; position: relative; overflow: hidden;">
        <!-- Animated Background Pattern -->
        <div style="position: absolute; inset: 0; opacity: 0.1; background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
        
        <div style="position: relative; z-index: 1; text-align: center; max-width: 800px; margin: 0 auto;">
            <div style="display: inline-flex; align-items: center; gap: 1rem; margin-bottom: 1rem; padding: 0.5rem 1.5rem; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px); border-radius: 50px;">
                <i class="fas fa-calendar-star" style="font-size: 1.5rem; color: white;"></i>
                <span style="color: white; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">Community Events</span>
            </div>
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: white; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Events & Happenings
            </h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.125rem; line-height: 1.6;">
                Join us for live shows, exciting contests, and unforgettable community events!
            </p>
            
            <!-- Event Stats -->
            <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 2rem; flex-wrap: wrap;">
                @if($ongoingEvents->count() > 0)
                <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: 12px; min-width: 120px;">
                    <div style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">{{ $ongoingEvents->count() }}</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 0.5px;">Live Now</div>
                </div>
                @endif
                <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: 12px; min-width: 120px;">
                    <div style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">{{ $upcomingEvents->count() }}</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 0.5px;">Upcoming</div>
                </div>
                @if($featuredEvents->count() > 0)
                <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1rem 1.5rem; border-radius: 12px; min-width: 120px;">
                    <div style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">{{ $featuredEvents->count() }}</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 0.5px;">Featured</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($featuredEvents->count() > 0)
        <div class="card" style="margin-bottom: 2rem; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-header" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: none;">
                <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-star" style="font-size: 1.25rem;"></i>
                    Featured Events
                </h2>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="grid grid-cols-3" style="gap: 1.5rem;">
                    @foreach($featuredEvents as $event)
                        <div class="event-featured-card">
                            @if($event->image)
                                <div style="width: 100%; height: 180px; background: url('{{ e($event->image) }}') center/cover; position: relative;">
                                    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);"></div>
                                    <span class="badge badge-warning" style="position: absolute; top: 0.75rem; right: 0.75rem; backdrop-filter: blur(10px); font-weight: 600;">
                                        <i class="fas fa-star" style="font-size: 0.75rem;"></i>
                                        Featured
                                    </span>
                                </div>
                            @else
                                <div style="width: 100%; height: 180px; background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center; position: relative;">
                                    <i class="fas fa-calendar-star" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                                    <span class="badge badge-warning" style="position: absolute; top: 0.75rem; right: 0.75rem; backdrop-filter: blur(10px); font-weight: 600;">
                                        <i class="fas fa-star" style="font-size: 0.75rem;"></i>
                                        Featured
                                    </span>
                                </div>
                            @endif
                            <div style="padding: 1.25rem;">
                                <div style="margin-bottom: 0.75rem;">
                                    <span class="badge badge-{{ $event->event_type === 'live_show' ? 'primary' : ($event->event_type === 'contest' ? 'warning' : 'gray') }}" style="font-size: 0.75rem;">
                                        <i class="fas fa-{{ $event->event_type === 'live_show' ? 'microphone' : ($event->event_type === 'contest' ? 'trophy' : 'calendar') }}" style="margin-right: 0.25rem;"></i>
                                        {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                    </span>
                                </div>
                                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; line-height: 1.4;">{{ $event->title }}</h3>
                                <p style="color: var(--color-text-secondary); font-size: 0.875rem; line-height: 1.6; margin-bottom: 1rem;">
                                    {{ Str::limit($event->description, 120) }}
                                </p>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                        <i class="fas fa-clock" style="width: 16px; color: var(--color-accent);"></i>
                                        <span>{{ $event->starts_at->format('M j, Y') }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                        <i class="fas fa-bell" style="width: 16px; color: var(--color-accent);"></i>
                                        <span>{{ $event->starts_at->format('g:i A') }}</span>
                                    </div>
                                    @if($event->location)
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                        <i class="fas fa-map-marker-alt" style="width: 16px; color: var(--color-accent);"></i>
                                        <span>{{ Str::limit($event->location, 30) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary" style="width: 100%; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                    View Details
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            @if($ongoingEvents->count() > 0)
                <div class="card" style="margin-bottom: 1.5rem; border: 2px solid var(--color-success); box-shadow: 0 0 20px rgba(34, 197, 94, 0.2);">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--color-success) 0%, #10b981 100%); border: none;">
                        <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.75rem;">
                            <span class="badge badge-live pulse-animation" style="background: white; color: var(--color-success); font-weight: 700;">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                LIVE
                            </span>
                            Happening Now
                        </h2>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @foreach($ongoingEvents as $event)
                            <div style="margin-bottom: 1rem; padding: 1.25rem; background: linear-gradient(135deg, rgba(34, 197, 94, 0.05) 0%, rgba(16, 185, 129, 0.1) 100%); border: 2px solid rgba(34, 197, 94, 0.3); border-radius: 12px; position: relative; overflow: hidden; transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(8px)'; this.style.boxShadow='0 4px 16px rgba(34, 197, 94, 0.2)'" onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none'">
                                <!-- Animated Pulse Background -->
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); animation: shimmer 2s infinite;"></div>
                                
                                <div style="display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1;">
                                    <div style="flex-shrink: 0;">
                                        <span class="badge badge-live pulse-animation" style="padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 700;">
                                            <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                                            LIVE
                                        </span>
                                    </div>
                                    <div style="flex: 1;">
                                        <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text);">{{ $event->title }}</h4>
                                        <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.875rem; color: var(--color-text-secondary);">
                                            <span>
                                                <i class="fas fa-clock" style="color: var(--color-success); margin-right: 0.25rem;"></i>
                                                @if($event->ends_at)
                                                    Ends {{ $event->ends_at->diffForHumans() }}
                                                @else
                                                    In progress
                                                @endif
                                            </span>
                                            @if($event->location)
                                            <span>
                                                <i class="fas fa-map-marker-alt" style="color: var(--color-success); margin-right: 0.25rem;"></i>
                                                {{ $event->location }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-success" style="flex-shrink: 0; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                        Join Now
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card" style="box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <div class="card-header" style="background: linear-gradient(135deg, var(--color-accent) 0%, #8b5cf6 100%); border: none;">
                    <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-calendar-alt"></i>
                        Upcoming Events
                    </h2>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    @if($upcomingEvents->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event->slug) }}" style="display: flex; align-items: stretch; gap: 1rem; padding: 1rem; background: var(--color-bg-secondary); border: 2px solid transparent; border-radius: 12px; transition: all 0.3s ease; cursor: pointer; text-decoration: none; color: inherit;" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateX(4px)'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateX(0)'; this.style.boxShadow='none'">
                                <!-- Date Badge -->
                                <div style="flex-shrink: 0; width: 70px; height: 70px; background: linear-gradient(135deg, var(--color-accent) 0%, #8b5cf6 100%); border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3);">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: white; line-height: 1;">{{ $event->starts_at->format('j') }}</div>
                                    <div style="font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.5px;">{{ $event->starts_at->format('M') }}</div>
                                </div>
                                
                                <!-- Event Info -->
                                <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <span class="badge badge-{{ $event->event_type === 'live_show' ? 'primary' : ($event->event_type === 'contest' ? 'warning' : 'gray') }}" style="font-size: 0.75rem;">
                                            <i class="fas fa-{{ $event->event_type === 'live_show' ? 'microphone' : ($event->event_type === 'contest' ? 'trophy' : 'calendar') }}" style="font-size: 0.625rem; margin-right: 0.25rem;"></i>
                                            {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                        </span>
                                    </div>
                                    <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.3;">{{ $event->title }}</h4>
                                    <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--color-text-muted); flex-wrap: wrap;">
                                        <span>
                                            <i class="fas fa-clock" style="color: var(--color-accent); margin-right: 0.25rem;"></i>
                                            {{ $event->starts_at->format('g:i A') }}
                                        </span>
                                        @if($event->location)
                                        <span>
                                            <i class="fas fa-map-marker-alt" style="color: var(--color-accent); margin-right: 0.25rem;"></i>
                                            {{ Str::limit($event->location, 25) }}
                                        </span>
                                        @endif
                                        <span style="color: var(--color-text-secondary);">
                                            <i class="fas fa-calendar" style="margin-right: 0.25rem;"></i>
                                            {{ $event->starts_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Arrow Icon -->
                                <div style="flex-shrink: 0; display: flex; align-items: center;">
                                    <i class="fas fa-chevron-right" style="color: var(--color-text-muted); font-size: 1.25rem;"></i>
                                </div>
                            </a>
                        @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 3rem 2rem;">
                            <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, var(--color-bg-secondary) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-times" style="font-size: 2rem; color: var(--color-text-muted);"></i>
                            </div>
                            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text);">No Upcoming Events</h3>
                            <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                                Check back soon for exciting new events!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <!-- Event Types Card -->
            <div class="card" style="box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 1.5rem;">
                <div class="card-header" style="background: linear-gradient(135deg, var(--color-accent) 0%, #8b5cf6 100%); border: none;">
                    <h2 class="card-title" style="color: white; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem;">
                        <i class="fas fa-list-ul"></i>
                        Event Categories
                    </h2>
                </div>
                <div class="card-body" style="padding: 1.25rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="event-category-card" style="background: linear-gradient(135deg, rgba(88, 166, 255, 0.1) 0%, rgba(139, 92, 246, 0.05) 100%); border-left: 3px solid var(--color-accent);">
                            <div style="width: 36px; height: 36px; background: var(--color-accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-microphone" style="color: white; font-size: 0.875rem;"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--color-text);">Live Shows</div>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">DJ sets & broadcasts</div>
                            </div>
                        </div>
                        
                        <div class="event-category-card" style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%); border-left: 3px solid #fbbf24;">
                            <div style="width: 36px; height: 36px; background: #fbbf24; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-trophy" style="color: white; font-size: 0.875rem;"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--color-text);">Contests</div>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">Win prizes & rewards</div>
                            </div>
                        </div>
                        
                        <div class="event-category-card" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border-left: 3px solid var(--color-success);">
                            <div style="width: 36px; height: 36px; background: var(--color-success); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-users" style="color: white; font-size: 0.875rem;"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--color-text);">Meetups</div>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">Community gatherings</div>
                            </div>
                        </div>
                        
                        <div class="event-category-card" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(147, 51, 234, 0.05) 100%); border-left: 3px solid #a855f7;">
                            <div style="width: 36px; height: 36px; background: #a855f7; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-star" style="color: white; font-size: 0.875rem;"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--color-text);">Special Events</div>
                                <div style="font-size: 0.75rem; color: var(--color-text-muted);">Exclusive occasions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discord Card -->
            <div class="card" style="background: linear-gradient(135deg, #5865F2 0%, #4752C4 100%); border: none; box-shadow: 0 8px 24px rgba(88, 101, 242, 0.3);">
                <div class="card-body" style="padding: 1.5rem; text-align: center;">
                    <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fab fa-discord" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: white; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem;">Get Event Notifications</h3>
                    <p style="color: rgba(255,255,255,0.9); font-size: 0.875rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        Join our Discord community to receive instant notifications and never miss an event!
                    </p>
                    <a href="#" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; color: #5865F2; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 16px rgba(255,255,255,0.3)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                        <i class="fab fa-discord" style="font-size: 1.125rem;"></i>
                        Join Discord
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
