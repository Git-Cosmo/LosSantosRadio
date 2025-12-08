<x-layouts.app :title="'Schedule'">
    @if($error)
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                Station Schedule
            </h1>
        </div>
        <div class="card-body">
            <!-- Current Show -->
            @if($nowPlaying)
                <div style="background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, rgba(88, 166, 255, 0.1) 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--color-accent);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 600; color: var(--color-accent);">
                            <i class="fas fa-broadcast-tower" style="margin-right: 0.5rem;"></i>
                            Now Playing
                        </h2>
                        <span class="badge badge-live pulse-animation">
                            <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                            LIVE
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1.5rem;">
                        <img src="{{ $nowPlaying->currentSong->art ?? '' }}"
                             alt="{{ $nowPlaying->currentSong->title }} by {{ $nowPlaying->currentSong->artist }} - Album Art"
                             style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover; background: var(--color-bg-tertiary);"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">
                                {{ $nowPlaying->currentSong->title }}
                            </h3>
                            <p style="color: var(--color-text-secondary);">
                                {{ $nowPlaying->currentSong->artist }}
                            </p>
                            <div style="margin-top: 0.75rem; display: flex; gap: 1rem; color: var(--color-text-muted); font-size: 0.875rem;">
                                <span>
                                    <i class="fas fa-headphones"></i> {{ $nowPlaying->listeners }} listeners
                                </span>
                                <span>
                                    <i class="fas fa-{{ $nowPlaying->isLive ? 'microphone' : 'robot' }}"></i>
                                    {{ $nowPlaying->isLive ? 'Live DJ' : 'AutoDJ' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Playlist Schedule -->
            @if(isset($playlists) && $playlists->count() > 0)
                <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">
                    <i class="fas fa-list-music" style="color: var(--color-accent); margin-right: 0.5rem;"></i>
                    Active Playlists
                </h2>

                <div class="playlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    @foreach($playlists->filter(fn($p) => $p->isEnabled && !$p->isJingle)->take(8) as $playlist)
                        @php
                            $iconMap = [
                                'scheduled' => 'clock',
                                'once_per_day' => 'star',
                                'once_per_x_songs' => 'random',
                                'once_per_x_minutes' => 'stopwatch',
                                'once_per_hour' => 'hourglass-half',
                            ];
                            $icon = $iconMap[$playlist->type] ?? 'music';
                        @endphp
                        <div class="playlist-card" style="background: var(--color-bg-tertiary); border-radius: 8px; padding: 1rem; border: 1px solid {{ $playlist->isCurrentlyActive() ? 'var(--color-accent)' : 'var(--color-border)' }}; {{ $playlist->isCurrentlyActive() ? 'box-shadow: 0 0 15px rgba(88, 166, 255, 0.2);' : '' }}">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-{{ e($icon) }}" style="color: white;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h3 style="font-weight: 600; font-size: 0.9375rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $playlist->name }}
                                    </h3>
                                    <p style="font-size: 0.75rem; color: var(--color-text-muted); text-transform: capitalize;">
                                        {{ str_replace('_', ' ', $playlist->type) }}
                                    </p>
                                </div>
                                @if($playlist->isCurrentlyActive())
                                    <span class="badge badge-live" style="font-size: 0.625rem;">
                                        <i class="fas fa-circle"></i> ACTIVE
                                    </span>
                                @endif
                            </div>
                            @php $scheduleItems = $playlist->getFormattedSchedule(); @endphp
                            @if(count($scheduleItems) > 0)
                                <div style="font-size: 0.8125rem; color: var(--color-text-secondary);">
                                    @foreach(array_slice($scheduleItems, 0, 2) as $item)
                                        <div style="display: flex; justify-content: space-between; padding: 0.25rem 0;">
                                            <span>{{ $item['day'] }}</span>
                                            <span style="color: var(--color-text-muted);">{{ $item['start_time'] }} - {{ $item['end_time'] }}</span>
                                        </div>
                                    @endforeach
                                    @if(count($scheduleItems) > 2)
                                        <p style="color: var(--color-text-muted); font-size: 0.75rem; margin-top: 0.25rem;">
                                            +{{ count($scheduleItems) - 2 }} more time slots
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p style="font-size: 0.8125rem; color: var(--color-text-muted);">
                                    <i class="fas fa-infinity" style="margin-right: 0.25rem;"></i>
                                    Always in rotation
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Schedule Grid -->
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">
                <i class="fas fa-clock" style="color: var(--color-accent); margin-right: 0.5rem;"></i>
                Weekly Schedule
            </h2>

            @if(count($schedule) > 0)
                <div class="schedule-grid" style="display: grid; gap: 0.75rem;">
                    @php
                        $groupedSchedule = collect($schedule)->groupBy('day');
                    @endphp
                    @foreach($groupedSchedule as $day => $items)
                        <div style="margin-bottom: 1rem;">
                            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-accent);">
                                {{ $day }}
                            </h3>
                            @foreach($items as $item)
                                <div class="schedule-item {{ $item['is_current'] ?? false ? 'active' : '' }}" style="margin-bottom: 0.5rem;">
                                    <div class="schedule-time">
                                        <span class="schedule-hour">{{ $item['time'] ?? '' }}</span>
                                    </div>
                                    <div class="schedule-info">
                                        <h4 class="schedule-title">{{ $item['title'] }}</h4>
                                        <p class="schedule-desc">{{ $item['description'] ?? '' }}</p>
                                    </div>
                                    @if($item['is_current'] ?? false)
                                        <span class="badge badge-live">ON AIR</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Default AutoDJ Schedule -->
                <div style="background: var(--color-bg-tertiary); border-radius: 8px; padding: 2rem; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">
                        <i class="fas fa-robot" style="color: var(--color-accent);"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">
                        AutoDJ - 24/7 Music
                    </h3>
                    <p style="color: var(--color-text-secondary); max-width: 500px; margin: 0 auto 1.5rem;">
                        Our AutoDJ plays the best music around the clock! No scheduled shows at the moment,
                        but you can always tune in for great tunes.
                    </p>
                    <div style="display: flex; justify-content: center; gap: 1rem;">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-play"></i> Listen Now
                        </a>
                        <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-music"></i> Request a Song
                        </a>
                    </div>
                </div>

                <!-- Timezone Info -->
                <div style="margin-top: 2rem; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                    <p style="color: var(--color-text-muted); font-size: 0.875rem; text-align: center;">
                        <i class="fas fa-globe" style="margin-right: 0.5rem;"></i>
                        All times are shown in your local timezone. Current server time: {{ now()->format('g:i A T') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- DJ Applications Card -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-microphone" style="color: var(--color-accent);"></i>
                Become a DJ
            </h2>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">
                Want to host your own show on Los Santos Radio? We're always looking for passionate DJs
                to join our team!
            </p>
            <a href="#" class="btn btn-primary">
                <i class="fab fa-discord"></i> Apply on Discord
            </a>
        </div>
    </div>

    <style>
        .playlist-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .playlist-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .playlist-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</x-layouts.app>
