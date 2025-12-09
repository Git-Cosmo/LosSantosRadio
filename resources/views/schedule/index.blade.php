<x-layouts.app :title="'Station Schedule'">
    @php
        // Define color and icon mappings once at the top to avoid duplication
        $dayColors = [
            'Sunday' => '#ef4444',
            'Monday' => '#3b82f6',
            'Tuesday' => '#10b981',
            'Wednesday' => '#f59e0b',
            'Thursday' => '#8b5cf6',
            'Friday' => '#ec4899',
            'Saturday' => '#06b6d4',
        ];
        
        $iconMap = [
            'default' => 'music',
            'scheduled' => 'clock',
            'once_per_day' => 'star',
            'once_per_x_songs' => 'random',
            'once_per_x_minutes' => 'stopwatch',
            'once_per_hour' => 'hourglass-half',
        ];
    @endphp

    @if($error)
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <div style="width: 4px; height: 48px; background: linear-gradient(180deg, var(--color-accent), #a855f7); border-radius: 2px;"></div>
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, var(--color-accent), #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Los Santos Radio Schedule
                </h1>
                <p style="color: var(--color-text-muted); margin: 0.25rem 0 0 0; font-size: 0.9375rem;">
                    <i class="fas fa-calendar-alt"></i> Your 24/7 Music Destination
                </p>
            </div>
        </div>
    </div>

    <!-- Current Show Banner -->
    @if($nowPlaying)
        <div style="background: linear-gradient(135deg, rgba(88, 166, 255, 0.15) 0%, rgba(168, 85, 247, 0.15) 100%); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; border: 2px solid var(--color-accent); position: relative; overflow: hidden;">
            <!-- Animated background -->
            <div style="position: absolute; inset: 0; opacity: 0.05; background-image: radial-gradient(circle, var(--color-accent) 1px, transparent 1px); background-size: 24px 24px;"></div>
            
            <div style="position: relative; z-index: 1;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="badge badge-live pulse-animation" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.5rem;"></i>
                            ON AIR NOW
                        </span>
                        <span style="color: var(--color-text-muted); font-size: 0.875rem;">
                            <i class="fas fa-headphones"></i> {{ $nowPlaying->listeners }} listening
                        </span>
                    </div>
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: var(--color-bg-secondary); border-radius: 8px; font-size: 0.875rem;">
                        <i class="fas fa-{{ $nowPlaying->isLive ? 'microphone' : 'robot' }}" style="color: var(--color-accent);"></i>
                        {{ $nowPlaying->isLive ? 'Live DJ' : 'AutoDJ' }}
                    </span>
                </div>
                
                <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
                    <img src="{{ $nowPlaying->currentSong->art ?? '' }}"
                         alt="{{ $nowPlaying->currentSong->title }} by {{ $nowPlaying->currentSong->artist }} - Album Art"
                         style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover; background: var(--color-bg-tertiary); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                    <div style="flex: 1; min-width: 0;">
                        <h2 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 0.5rem 0; line-height: 1.2;">
                            {{ $nowPlaying->currentSong->title }}
                        </h2>
                        <p style="font-size: 1.125rem; color: var(--color-text-secondary); margin: 0;">
                            {{ $nowPlaying->currentSong->artist }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Weekly Schedule Grid -->
    @if(count($schedule) > 0)
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h2 class="card-title" style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-calendar-week" style="color: var(--color-accent);"></i>
                    Weekly Programming Schedule
                </h2>
            </div>
            <div class="card-body" style="padding: 0;">
                @php
                    $groupedSchedule = collect($schedule)->groupBy('day');
                @endphp
                
                <div style="display: grid; gap: 0;">
                    @foreach($groupedSchedule as $day => $items)
                        <div style="border-bottom: 1px solid var(--color-border);">
                            <!-- Day Header -->
                            <div style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; background: var(--color-bg-secondary);">
                                <div style="width: 4px; height: 32px; background: {{ $dayColors[$day] ?? 'var(--color-accent)' }}; border-radius: 2px;"></div>
                                <h3 style="font-size: 1.125rem; font-weight: 700; margin: 0; flex: 1;">
                                    {{ $day }}
                                </h3>
                                <span style="font-size: 0.875rem; color: var(--color-text-muted);">
                                    {{ count($items) }} {{ Str::plural('show', count($items)) }}
                                </span>
                            </div>
                            
                            <!-- Schedule Items -->
                            <div style="padding: 0.75rem;">
                                @foreach($items as $item)
                                    <div class="schedule-item" style="display: flex; align-items: stretch; gap: 1rem; padding: 1rem; margin-bottom: 0.5rem; background: {{ $item['is_current'] ? 'linear-gradient(135deg, rgba(88, 166, 255, 0.1), rgba(168, 85, 247, 0.1))' : 'var(--color-bg-tertiary)' }}; border-radius: 10px; border: 1px solid {{ $item['is_current'] ? 'var(--color-accent)' : 'var(--color-border)' }}; transition: all 0.2s ease;">
                                        <!-- Time Badge -->
                                        @php
                                            $startTime = '';
                                            $endTime = '';
                                            if (is_string($item['time']) && str_contains($item['time'], ' - ')) {
                                                [$startTime, $endTime] = explode(' - ', $item['time'], 2);
                                            } elseif (is_string($item['time'])) {
                                                $startTime = $item['time'];
                                            }
                                        @endphp
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 80px; padding: 0.75rem; background: linear-gradient(135deg, {{ $dayColors[$day] ?? 'var(--color-accent)' }}, {{ $dayColors[$day] ?? '#a855f7' }}); border-radius: 8px; color: white; text-align: center; flex-shrink: 0;">
                                            <div style="font-size: 1.125rem; font-weight: 700; line-height: 1;">
                                                {{ $startTime }}
                                            </div>
                                            <div style="font-size: 0.625rem; opacity: 0.9; margin: 0.25rem 0;">to</div>
                                            <div style="font-size: 0.875rem; font-weight: 600; line-height: 1;">
                                                {{ $endTime }}
                                            </div>
                                        </div>
                                        
                                        <!-- Show Info -->
                                        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                                                <h4 style="font-size: 1.0625rem; font-weight: 600; margin: 0;">
                                                    {{ $item['title'] }}
                                                </h4>
                                                @if($item['is_current'])
                                                    <span class="badge badge-live pulse-animation" style="font-size: 0.625rem;">
                                                        <i class="fas fa-circle"></i> LIVE
                                                    </span>
                                                @endif
                                            </div>
                                            @if(!empty($item['description']))
                                                <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">
                                                    {{ $item['description'] }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <!-- Show Type Icon -->
                                        <div style="display: flex; align-items: center; justify-content: center; width: 40px; flex-shrink: 0;">
                                            @php
                                                $icon = $iconMap[$item['type'] ?? ''] ?? 'music';
                                            @endphp
                                            <i class="fas fa-{{ $icon }}" style="font-size: 1.25rem; color: {{ $dayColors[$day] ?? 'var(--color-accent)' }}; opacity: 0.5;"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- No Schedule - AutoDJ -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-body" style="text-align: center; padding: 4rem 2rem;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 120px; height: 120px; background: linear-gradient(135deg, rgba(88, 166, 255, 0.1), rgba(168, 85, 247, 0.1)); border-radius: 50%; margin-bottom: 2rem;">
                    <i class="fas fa-robot" style="font-size: 4rem; color: var(--color-accent);"></i>
                </div>
                <h3 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 1rem;">
                    24/7 AutoDJ Broadcasting
                </h3>
                <p style="color: var(--color-text-secondary); max-width: 500px; margin: 0 auto 2rem; font-size: 1.0625rem; line-height: 1.6;">
                    Our intelligent AutoDJ plays the best music around the clock! While we don't have scheduled shows at the moment, you'll always find great tunes streaming live.
                </p>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1.0625rem;">
                        <i class="fas fa-play"></i> Listen Now
                    </a>
                    <a href="{{ route('requests.index') }}" class="btn btn-secondary" style="padding: 0.75rem 2rem; font-size: 1.0625rem;">
                        <i class="fas fa-music"></i> Request a Song
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Active Playlists Overview -->
    @if(isset($playlists) && $playlists->count() > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-list-music" style="color: var(--color-accent);"></i>
                    Active Playlists & Rotations
                </h2>
            </div>
            <div class="card-body">
                <div class="playlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                    @foreach($playlists->filter(fn($p) => $p->isEnabled && !$p->isJingle) as $playlist)
                        @php
                            $icon = $iconMap[$playlist->type] ?? 'music';
                            $scheduleItems = $playlist->getFormattedSchedule();
                            $isActive = $playlist->isCurrentlyActive();
                        @endphp
                        <div class="playlist-card" style="background: {{ $isActive ? 'linear-gradient(135deg, rgba(88, 166, 255, 0.08), rgba(168, 85, 247, 0.08))' : 'var(--color-bg-tertiary)' }}; border-radius: 12px; padding: 1.25rem; border: 2px solid {{ $isActive ? 'var(--color-accent)' : 'var(--color-border)' }}; transition: all 0.2s ease; position: relative; overflow: hidden;">
                            <!-- Active indicator -->
                            @if($isActive)
                                <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-accent), #a855f7); opacity: 0.1; border-radius: 0 0 0 100%;"></div>
                            @endif
                            
                            <div style="position: relative; z-index: 1;">
                                <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-{{ e($icon) }}" style="color: white; font-size: 1.25rem;"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <h3 style="font-weight: 700; font-size: 1.0625rem; margin: 0 0 0.25rem 0; line-height: 1.3;">
                                            {{ $playlist->name }}
                                        </h3>
                                        <p style="font-size: 0.8125rem; color: var(--color-text-muted); text-transform: capitalize; margin: 0;">
                                            {{ str_replace('_', ' ', $playlist->type) }}
                                        </p>
                                    </div>
                                    @if($isActive)
                                        <span class="badge badge-live pulse-animation" style="font-size: 0.625rem;">
                                            <i class="fas fa-circle"></i> LIVE
                                        </span>
                                    @endif
                                </div>
                                
                                @if(count($scheduleItems) > 0)
                                    <div style="background: var(--color-bg-secondary); border-radius: 8px; padding: 0.75rem; font-size: 0.8125rem;">
                                        @foreach(array_slice($scheduleItems, 0, 3) as $item)
                                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.375rem 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--color-border);' : '' }}">
                                                <span style="font-weight: 600;">{{ $item['day'] }}</span>
                                                <span style="color: var(--color-text-muted); font-family: monospace;">{{ $item['start_time'] }} - {{ $item['end_time'] }}</span>
                                            </div>
                                        @endforeach
                                        @if(count($scheduleItems) > 3)
                                            <p style="color: var(--color-text-muted); font-size: 0.75rem; margin: 0.5rem 0 0 0; text-align: center;">
                                                +{{ count($scheduleItems) - 3 }} more time {{ Str::plural('slot', count($scheduleItems) - 3) }}
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div style="background: var(--color-bg-secondary); border-radius: 8px; padding: 0.75rem; text-align: center; font-size: 0.8125rem; color: var(--color-text-muted);">
                                        <i class="fas fa-infinity" style="margin-right: 0.5rem; color: var(--color-accent);"></i>
                                        Always in rotation
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Bottom Info Section -->
    <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
        <!-- Timezone Info -->
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-globe" style="font-size: 2rem; color: var(--color-accent); margin-bottom: 0.75rem;"></i>
                <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin: 0; line-height: 1.5;">
                    All times displayed in your local timezone<br>
                    <span style="font-weight: 600;">Server: {{ now()->format('g:i A T') }}</span>
                </p>
            </div>
        </div>

        <!-- DJ Applications -->
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <i class="fas fa-microphone" style="font-size: 2rem; color: var(--color-accent); margin-bottom: 0.75rem;"></i>
                <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin: 0 0 0.75rem 0;">
                    Want to host your own show?
                </p>
                <a href="#" class="btn btn-primary btn-sm">
                    <i class="fab fa-discord"></i> Apply on Discord
                </a>
            </div>
        </div>
    </div></x-layouts.app>
