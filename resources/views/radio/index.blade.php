<x-layouts.app>
    

    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr]" style="gap: 1.5rem;">
        <!-- Main Content -->
        <div>
            <!-- Enhanced Now Playing Card -->
            <div class="card now-playing-card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, var(--color-bg-secondary) 0%, rgba(88, 166, 255, 0.08) 100%); overflow: hidden; position: relative; border: 1px solid rgba(88, 166, 255, 0.2);">
                <!-- Animated Background Overlay with Floating Elements -->
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 30% 50%, rgba(88, 166, 255, 0.15), transparent 50%), radial-gradient(circle at 70% 80%, rgba(168, 85, 247, 0.15), transparent 50%); pointer-events: none; opacity: 0.7;">
                    <div style="position: absolute; width: 100px; height: 100px; border-radius: 50%; background: rgba(88, 166, 255, 0.1); top: 10%; left: 15%; animation: float 6s ease-in-out infinite;"></div>
                    <div style="position: absolute; width: 80px; height: 80px; border-radius: 50%; background: rgba(168, 85, 247, 0.1); bottom: 20%; right: 20%; animation: float 8s ease-in-out infinite 2s;"></div>
                </div>
                
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); position: relative; z-index: 1; -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);">
                    <h2 class="card-title" style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                        <i class="fas fa-broadcast-tower" style="color: var(--color-accent); animation: broadcast-pulse 2s ease-in-out infinite;"></i>
                        Now Playing
                    </h2>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        @if(isset($streamStatus) && $streamStatus['is_online'])
                            <span class="badge badge-live pulse-animation" style="font-weight: 600; padding: 0.5rem 1rem; font-size: 0.875rem;">
                                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                                LIVE
                            </span>
                        @endif
                        <span class="listeners-count" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; padding: 0.5rem 1rem; background: var(--color-bg-tertiary); border-radius: 20px;">
                            <i class="fas fa-headphones" style="color: var(--color-accent);"></i>
                            <span id="listener-count">{{ $nowPlaying?->listeners ?? 0 }}</span>
                        </span>
                    </div>
                </div>
                <div class="card-body" style="position: relative; z-index: 1;">
                    @if($nowPlaying)
                        <div class="now-playing" id="now-playing" style="display: flex; gap: 1rem; align-items: center;">
                            <div class="now-playing-album-container" style="position: relative; flex-shrink: 0;">
                                <div style="width: 120px; height: 120px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1); position: relative; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <img src="{{ $nowPlaying->currentSong->art ?? '/images/default-album.svg' }}"
                                         alt="Album art for {{ $nowPlaying->currentSong->title }} by {{ $nowPlaying->currentSong->artist }}"
                                         class="now-playing-art"
                                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                                    
                                    <!-- Animated Visualizer Overlay -->
                                    <div class="now-playing-equalizer" style="position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); display: flex; align-items: flex-end; gap: 2px; height: 18px; background: rgba(0,0,0,0.3); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); padding: 4px 8px; border-radius: 12px;" aria-hidden="true">
                                        <div class="eq-bar" style="width: 2px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate; height: 8px; box-shadow: 0 0 6px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 2px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.1s; height: 14px; box-shadow: 0 0 6px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 2px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.2s; height: 10px; box-shadow: 0 0 6px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 2px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.3s; height: 16px; box-shadow: 0 0 6px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 2px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.4s; height: 9px; box-shadow: 0 0 6px var(--color-accent);"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="now-playing-info" style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 50%; box-shadow: 0 3px 8px rgba(88, 166, 255, 0.4);">
                                        <i class="fas fa-play" aria-hidden="true" style="color: white; font-size: 0.625rem; margin-left: 2px;"></i>
                                    </span>
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); font-weight: 600; margin-bottom: 0.125rem;">Now Playing</p>
                                        <div style="width: 100%; height: 2px; background: linear-gradient(90deg, var(--color-accent), transparent); border-radius: 1px;"></div>
                                    </div>
                                </div>
                                <h3 class="now-playing-title" id="song-title" style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; line-height: 1.2; color: var(--color-text); background: linear-gradient(135deg, #ffffff, var(--color-accent), #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; background-size: 200% auto;">{{ $nowPlaying->currentSong->title }}</h3>
                                <p class="now-playing-artist" id="song-artist" style="font-size: 0.9375rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.375rem;">
                                    <i class="fas fa-user-music" style="font-size: 0.75rem; color: var(--color-accent);" aria-hidden="true"></i>
                                    {{ $nowPlaying->currentSong->artist }}
                                </p>

                                <!-- Enhanced Song Rating UI -->
                                <div class="song-rating" id="song-rating"
                                     data-song-id="{{ $nowPlaying->currentSong->id }}"
                                     data-song-title="{{ $nowPlaying->currentSong->title }}"
                                     data-song-artist="{{ $nowPlaying->currentSong->artist }}"
                                     role="group"
                                     aria-label="Rate this song"
                                     style="margin-bottom: 0.75rem;">
                                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                                        <button class="rating-btn upvote" data-rating="1" title="Like this song" aria-label="Like this song" style="display: flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; background: var(--color-bg-tertiary); border: 2px solid transparent; border-radius: 50px; transition: all 0.3s ease; font-weight: 600; cursor: pointer; color: var(--color-text); font-size: 0.875rem;">
                                            <i class="fas fa-thumbs-up" aria-hidden="true" style="color: #43b581;"></i>
                                            <span id="upvote-count">0</span>
                                        </button>
                                        <button class="rating-btn downvote" data-rating="-1" title="Dislike this song" aria-label="Dislike this song" style="display: flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; background: var(--color-bg-tertiary); border: 2px solid transparent; border-radius: 50px; transition: all 0.3s ease; font-weight: 600; cursor: pointer; color: var(--color-text); font-size: 0.875rem;">
                                            <i class="fas fa-thumbs-down" aria-hidden="true" style="color: #f04747;"></i>
                                            <span id="downvote-count">0</span>
                                        </button>
                                        <button class="rating-btn lyrics-btn" 
                                                data-lyrics-song-id="{{ $nowPlaying->currentSong->id }}" 
                                                data-lyrics-title="{{ $nowPlaying->currentSong->title }}" 
                                                data-lyrics-artist="{{ $nowPlaying->currentSong->artist }}" 
                                                title="View Lyrics" 
                                                aria-label="View lyrics for this song" 
                                                style="display: flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; background: linear-gradient(135deg, rgba(88, 166, 255, 0.2), rgba(168, 85, 247, 0.2)); border: 2px solid var(--color-accent); border-radius: 50px; transition: all 0.3s ease; font-weight: 600; cursor: pointer; color: var(--color-text); font-size: 0.875rem;">
                                            <i class="fas fa-music" aria-hidden="true" style="color: var(--color-accent);"></i>
                                            <span>Lyrics</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Enhanced Progress Bar -->
                                <div style="margin-bottom: 0.5rem;">
                                    <div class="progress-bar" role="progressbar" aria-label="Song progress" aria-valuenow="{{ $nowPlaying->elapsed }}" aria-valuemin="0" aria-valuemax="{{ $nowPlaying->duration }}" style="height: 4px; background: var(--color-bg-tertiary); border-radius: 6px; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);">
                                        <div class="progress-fill" id="progress-fill" style="width: {{ $nowPlaying->progressPercentage() }}%; height: 100%; background: linear-gradient(90deg, var(--color-accent), #a855f7); border-radius: 6px; transition: width 0.3s ease; box-shadow: 0 0 6px var(--color-accent);"></div>
                                    </div>
                                    <div class="time-info" style="display: flex; justify-content: space-between; margin-top: 0.25rem; font-size: 0.75rem; color: var(--color-text-muted); font-weight: 500;">
                                        <span id="elapsed-time">{{ gmdate('i:s', $nowPlaying->elapsed) }}</span>
                                        <span id="total-time">{{ gmdate('i:s', $nowPlaying->duration) }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Enhanced Audio Player Controls -->
                        <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                            <button id="play-btn" class="btn btn-primary" style="flex: 1; padding: 0.75rem 1.25rem; font-size: 0.9375rem; font-weight: 600; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3); transition: all 0.3s ease;">
                                <i class="fas fa-play" style="font-size: 1rem;"></i> 
                                <span>Listen Live</span>
                            </button>
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary" style="flex: 1; padding: 0.75rem 1.25rem; font-size: 0.9375rem; font-weight: 600; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease;">
                                <i class="fas fa-music" style="font-size: 1rem;"></i> 
                                <span>Request a Song</span>
                            </a>
                        </div>

                        <!-- Enhanced DJ/Host Info -->
                        <div style="margin-top: 1rem; padding: 1rem; background: linear-gradient(135deg, var(--color-bg-tertiary), rgba(88, 166, 255, 0.05)); border-radius: 10px; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--color-border); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);">
                            <div class="dj-avatar" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.4), 0 0 20px rgba(88, 166, 255, 0.2);">
                                <i class="fas fa-{{ $nowPlaying->isLive ? 'microphone' : 'robot' }}" style="color: white; font-size: 1rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-weight: 700; font-size: 0.9375rem; margin-bottom: 0.125rem;">{{ $nowPlaying->isLive ? 'Live DJ' : 'AutoDJ' }}</p>
                                <p style="color: var(--color-text-muted); font-size: 0.8125rem;">{{ $nowPlaying->isLive ? 'Broadcasting live!' : 'Playing 24/7' }}</p>
                            </div>
                            @if(isset($streamStatus))
                                <div style="text-align: right; padding: 0.5rem 0.75rem; background: var(--color-bg); border-radius: 8px;">
                                    <p style="font-size: 0.625rem; color: var(--color-text-muted); margin-bottom: 0.125rem; text-transform: uppercase; letter-spacing: 0.05em;">Peak Today</p>
                                    <p style="font-weight: 700; color: var(--color-accent); font-size: 1.125rem;">{{ $streamStatus['peak_listeners'] ?? 0 }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <p style="color: var(--color-text-muted); text-align: center; padding: 2rem;">
                            <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>
                            Loading now playing information...
                        </p>
                    @endif
                </div>
            </div>

            <!-- Schedule Display -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                        Schedule
                    </h2>
                </div>
                <div class="card-body">
                    <div class="schedule-list" id="schedule-list">
                        <div class="schedule-loading" id="schedule-loading" style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
                            <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>
                            Loading schedule...
                        </div>
                        <div class="schedule-fallback" id="schedule-fallback" style="display: none;">
                            <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                                Schedule data unavailable. AutoDJ is playing your favorite tracks 24/7!
                            </p>
                        </div>
                        <div id="schedule-content"></div>
                    </div>
                </div>
            </div>

            <!-- Trending Songs -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-fire" style="color: #f97316;"></i>
                        Trending Songs
                    </h2>
                </div>
                <div class="card-body">
                    <div id="trending-songs" class="trending-list">
                        <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                            <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>
                            Loading trending songs...
                        </p>
                    </div>
                </div>
            </div>

            <!-- Station Info -->
            @if(isset($station))
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                            About {{ $station->name }}
                        </h2>
                    </div>
                    <div class="card-body">
                        @if($station->description)
                            <p style="color: var(--color-text-secondary);">{{ $station->description }}</p>
                        @else
                            <p style="color: var(--color-text-secondary);">
                                Welcome to Los Santos Radio! Your 24/7 source for the best music. Tune in and enjoy!
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Additional Homepage Content -->
            @if($recentNews->isNotEmpty() || $upcomingEvents->isNotEmpty() || $activePolls->isNotEmpty() || $topGameDeals->isNotEmpty() || $freeGames->isNotEmpty())
            <div style="margin-top: 2rem;">
                <div class="homepage-content-grid">
                    <!-- Recent News Section -->
                    @if($recentNews->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fas fa-newspaper"></i> Latest News</span>
                                <a href="{{ route('news.index') }}" class="text-sm" style="color: var(--color-accent); text-decoration: none;">View All →</a>
                            </h2>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            @foreach($recentNews as $newsItem)
                            <a href="{{ route('news.show', $newsItem->slug) }}" class="news-item" style="display: block; padding: 1rem; border-bottom: 1px solid var(--color-border); text-decoration: none; transition: background 0.2s;">
                                @if($newsItem->getFirstMediaUrl('featured'))
                                <img src="{{ $newsItem->getFirstMediaUrl('featured') }}" alt="{{ $newsItem->title }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 0.75rem;">
                                @endif
                                <h3 style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9375rem;">{{ Str::limit($newsItem->title, 60) }}</h3>
                                <p style="color: var(--color-text-muted); font-size: 0.8125rem; margin-bottom: 0.5rem;">{{ Str::limit($newsItem->excerpt, 100) }}</p>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted);">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $newsItem->published_at?->diffForHumans() ?? $newsItem->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Upcoming Events Section -->
                    @if($upcomingEvents->isNotEmpty())
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--color-accent) 0%, #8b5cf6 100%); border: none;">
                            <h2 class="card-title" style="display: flex; align-items: center; justify-content: space-between; color: white;">
                                <span><i class="fas fa-calendar-alt"></i> Upcoming Events</span>
                                <a href="{{ route('events.index') }}" class="text-sm view-all-link" style="color: rgba(255,255,255,0.9); text-decoration: none; font-weight: 600;">View All →</a>
                            </h2>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            @foreach($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event->slug) }}" class="event-item">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #8b5cf6 100%); color: white; border-radius: 12px; padding: 0.75rem; text-align: center; min-width: 70px; height: 70px; display: flex; flex-direction: column; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3);">
                                        <div style="font-size: 1.5rem; font-weight: 700; line-height: 1;">{{ $event->starts_at->format('d') }}</div>
                                        <div style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.9; font-weight: 600; letter-spacing: 0.5px;">{{ $event->starts_at->format('M') }}</div>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                            <span class="badge badge-{{ $event->event_type === 'live_show' ? 'primary' : ($event->event_type === 'contest' ? 'warning' : 'gray') }}" style="font-size: 0.75rem;">
                                                <i class="fas fa-{{ $event->event_type === 'live_show' ? 'microphone' : ($event->event_type === 'contest' ? 'trophy' : 'calendar') }}" style="font-size: 0.625rem; margin-right: 0.25rem;" aria-hidden="true"></i>
                                                {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                            </span>
                                        </div>
                                        <h3 style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.5rem; font-size: 1rem; line-height: 1.3;">{{ Str::limit($event->title, 60) }}</h3>
                                        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                                <i class="far fa-clock" style="color: var(--color-accent);"></i>
                                                <span>{{ $event->starts_at->format('M j, g:i A') }}</span>
                                            </div>
                                            @if($event->location)
                                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                                <i class="fas fa-map-marker-alt" style="color: var(--color-accent);"></i>
                                                <span>{{ Str::limit($event->location, 30) }}</span>
                                            </div>
                                            @endif
                                            @php($likesCount = $event->likesCount())
                                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                                                <i class="fas fa-heart" style="color: #ef4444;"></i>
                                                <span>{{ $likesCount }} {{ Str::plural('like', $likesCount) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex-shrink: 0; display: flex; align-items: center;">
                                        <i class="fas fa-chevron-right" style="color: var(--color-text-muted); font-size: 1.25rem; transition: all 0.2s;" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Active Polls Section -->
                    @if($activePolls->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fas fa-poll"></i> Community Polls</span>
                                <a href="{{ route('polls.index') }}" class="text-sm" style="color: var(--color-accent); text-decoration: none;">View All →</a>
                            </h2>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            @foreach($activePolls as $poll)
                            <a href="{{ route('polls.show', $poll->slug) }}" class="poll-item" style="display: block; padding: 1rem; border-bottom: 1px solid var(--color-border); text-decoration: none; transition: background 0.2s;">
                                <h3 style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.75rem; font-size: 0.9375rem;">{{ Str::limit($poll->question, 70) }}</h3>
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted);">
                                        <i class="fas fa-users"></i> {{ $poll->totalVotes() }} votes
                                    </span>
                                    @if($poll->ends_at)
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted);">
                                        <i class="far fa-clock"></i> Ends {{ $poll->ends_at->diffForHumans() }}
                                    </span>
                                    @endif
                                </div>
                                <div style="background: var(--color-bg-tertiary); height: 4px; border-radius: 2px; overflow: hidden;">
                                    <div style="background: var(--color-accent); height: 100%; width: {{ $poll->totalVotes() > 0 ? min(100, ($poll->totalVotes() / 100) * 100) : 10 }}%; transition: width 0.3s;"></div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Top Game Deals Section -->
                    @if($topGameDeals->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fas fa-tags" style="color: #f59e0b;" aria-hidden="true"></i> Hot Game Deals</span>
                                <a href="{{ route('games.deals') }}" class="text-sm" style="color: var(--color-accent); text-decoration: none;">View All →</a>
                            </h2>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            @foreach($topGameDeals as $deal)
                            <a href="{{ route('games.deals.show', $deal) }}" class="deal-item" style="display: block; padding: 1rem; border-bottom: 1px solid var(--color-border); text-decoration: none; transition: background 0.2s;">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    @if($deal->thumb)
                                    <img src="{{ $deal->thumb }}" alt="{{ $deal->title }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; background: var(--color-bg); flex-shrink: 0;">
                                    @endif
                                    <div style="flex: 1; min-width: 0;">
                                        <h3 style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9375rem;">{{ Str::limit($deal->title, 50) }}</h3>
                                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                            <span style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                                                -{{ $deal->savings_percent }}%
                                            </span>
                                            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                                                <span style="font-size: 1.125rem; font-weight: 700; color: #22c55e;">${{ number_format($deal->sale_price, 2) }}</span>
                                                <span style="font-size: 0.875rem; text-decoration: line-through; color: var(--color-text-muted);">${{ number_format($deal->normal_price, 2) }}</span>
                                            </div>
                                        </div>
                                        @if($deal->metacritic_score)
                                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted);">
                                            <i class="fas fa-star" style="color: #f59e0b;" aria-hidden="true"></i>
                                            <span>Metacritic: {{ $deal->metacritic_score }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Free Games Section -->
                    @if($freeGames->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="fas fa-gift" style="color: #22c55e;" aria-hidden="true"></i> Free Games</span>
                                <a href="{{ route('games.free') }}" class="text-sm" style="color: var(--color-accent); text-decoration: none;">View All →</a>
                            </h2>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            @foreach($freeGames as $game)
                            <a href="{{ route('games.free.show', $game) }}" class="game-item" style="display: block; padding: 1rem; border-bottom: 1px solid var(--color-border); text-decoration: none; transition: background 0.2s;">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    @if($game->image_url)
                                    <img src="{{ $game->image_url }}" alt="{{ $game->title }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; background: var(--color-bg); flex-shrink: 0;">
                                    @endif
                                    <div style="flex: 1; min-width: 0;">
                                        <h3 style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9375rem;">{{ Str::limit($game->title, 50) }}</h3>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                            <span style="background: linear-gradient(135deg, #22c55e, #16a34a); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                                FREE
                                            </span>
                                            <span style="font-size: 0.75rem; color: var(--color-text-muted); padding: 0.25rem 0.5rem; background: var(--color-bg-tertiary); border-radius: 12px;">
                                                @if($game->store === 'Epic Games')
                                                    <i class="fas fa-gamepad" aria-hidden="true"></i>
                                                @elseif($game->store === 'Steam')
                                                    <i class="fab fa-steam" aria-hidden="true"></i>
                                                @else
                                                    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                                @endif
                                                {{ $game->store }}
                                            </span>
                                        </div>
                                        @if($game->expires_at)
                                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted);">
                                            <i class="far fa-clock" aria-hidden="true"></i>
                                            <span>Ends {{ $game->expires_at->diffForHumans() }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Up Next Section (Moved from main content) -->
            @if($nowPlaying && $nowPlaying->nextSong)
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-forward" style="color: var(--color-accent);" aria-hidden="true"></i>
                        Up Next
                    </h2>
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div class="up-next-card" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 12px; border-left: 4px solid var(--color-accent);">
                        <img src="{{ $nowPlaying->nextSong->art ?? '' }}"
                             alt="Album art for {{ $nowPlaying->nextSong->title }} by {{ $nowPlaying->nextSong->artist }}"
                             style="width: 70px; height: 70px; border-radius: 8px; background: var(--color-bg); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);"
                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem;">{{ $nowPlaying->nextSong->title }}</p>
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary);">{{ $nowPlaying->nextSong->artist }}</p>
                            @if($nowPlaying->nextSong->album)
                                <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.25rem;">
                                    <i class="fas fa-compact-disc" style="font-size: 0.625rem;" aria-hidden="true"></i>
                                    {{ $nowPlaying->nextSong->album }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Song History -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-history" style="color: var(--color-accent);"></i>
                        Recently Played
                    </h2>
                </div>
                <div class="card-body" style="padding: 0.5rem;">
                    @if($history->count() > 0)
                        @foreach($history->take(2) as $item)
                            <div class="history-item">
                                <img src="{{ $item->song->art ?? '' }}"
                                     alt="Album art for {{ $item->song->title }} by {{ $item->song->artist }}"
                                     class="history-art"
                                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2220%22>🎵</text></svg>'">
                                <div class="history-info">
                                    <p class="history-title">{{ $item->song->title }}</p>
                                    <p class="history-artist">{{ $item->song->artist }}</p>
                                </div>
                                <span class="history-time" aria-label="Played {{ $item->playedAt->diffForHumans() }}">{{ $item->playedAt->diffForHumans(null, true, true) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                            No recent history available.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-chart-bar" style="color: var(--color-accent);" aria-hidden="true"></i>
                        Quick Stats
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: grid; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--color-text-secondary);">Current Listeners</span>
                            <span style="font-weight: 600;">{{ $nowPlaying?->listeners ?? 0 }}</span>
                        </div>
                        @if(isset($streamStatus))
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--color-text-secondary);">Peak Listeners</span>
                                <span style="font-weight: 600;">{{ $streamStatus['peak_listeners'] ?? 0 }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--color-text-secondary);">Stream Status</span>
                                <span class="badge {{ $streamStatus['is_online'] ? 'badge-success' : 'badge-warning' }}">
                                    {{ $streamStatus['is_online'] ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- News & Events Section -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-newspaper" style="color: var(--color-accent);"></i>
                        News & Events
                    </h2>
                </div>
                <div class="card-body">
                    <div class="news-list">
                        <div class="news-item">
                            <div class="news-date">
                                <i class="fas fa-bullhorn" style="color: var(--color-accent);"></i>
                            </div>
                            <div class="news-content">
                                <h4 class="news-title">Welcome to Los Santos Radio!</h4>
                                <p class="news-desc">We're broadcasting 24/7 with the best music. Request your favorite songs anytime!</p>
                            </div>
                        </div>
                        <div class="news-item">
                            <div class="news-date">
                                <i class="fas fa-star" style="color: #f59e0b;"></i>
                            </div>
                            <div class="news-content">
                                <h4 class="news-title">New Song Rating Feature</h4>
                                <p class="news-desc">Like or dislike songs to help us curate the best playlist for you!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discord Widget Section -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fab fa-discord" style="color: #5865F2;"></i>
                        Join Our Community
                    </h2>
                </div>
                <div class="card-body">
                    <div class="discord-widget">
                        <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">
                            Join our Discord server to chat with other listeners, get updates, and participate in events!
                        </p>
                        <a href="#" class="btn btn-discord" style="width: 100%; justify-content: center;">
                            <i class="fab fa-discord"></i> Join Discord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    @vite('resources/js/modules/radio-page.js')
</x-layouts.app>
