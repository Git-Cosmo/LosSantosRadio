<x-layouts.app>
    @push('styles')
    <style>
        /* Enhanced Hover effect for album artwork */
        .now-playing-art {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .now-playing-art:hover {
            transform: scale(1.08) rotate(2deg) !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4) !important;
        }

        /* Enhanced Album Container with Glow */
        .now-playing-album-container::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(135deg, rgba(88, 166, 255, 0.3), rgba(168, 85, 247, 0.3));
            border-radius: 20px;
            filter: blur(20px);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }
        
        .now-playing-album-container:hover::before {
            opacity: 1;
        }

        /* Pulsing Animation for Live Badge */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 10px rgba(239, 68, 68, 0.5), 0 0 20px rgba(239, 68, 68, 0.3);
            }
            50% {
                box-shadow: 0 0 20px rgba(239, 68, 68, 0.8), 0 0 40px rgba(239, 68, 68, 0.5);
            }
        }
        
        .badge-live {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        /* Equalizer Bounce Animation */
        @keyframes eqBounce {
            0%, 100% { 
                height: 12px;
                opacity: 0.7;
            }
            50% { 
                height: 26px;
                opacity: 1;
            }
        }

        /* Smooth Progress Animation */
        .progress-fill {
            transition: width 1s linear;
            position: relative;
            overflow: hidden;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Enhanced Rating Buttons */
        .rating-btn {
            position: relative;
            overflow: hidden;
        }
        
        .rating-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .rating-btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .rating-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .rating-btn.active {
            border-color: var(--color-accent) !important;
            background: rgba(88, 166, 255, 0.1) !important;
        }

        /* Floating Animation for Background Elements */
        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg);
                opacity: 0.6;
            }
            50% { 
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.8;
            }
        }

        /* Enhanced Card Animation */
        .now-playing-card {
            animation: fadeInScale 0.6s ease-out;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Gradient Text Enhancement */
        .now-playing-title {
            animation: gradientShift 3s ease infinite;
            background-size: 200% 200%;
        }
        
        @keyframes gradientShift {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        /* Accessibility fallback for gradient text */
        @supports not (-webkit-background-clip: text) {
            .now-playing-title {
                color: var(--color-text) !important;
                background: none !important;
                -webkit-text-fill-color: inherit !important;
            }
        }

        /* Listener Count Animation */
        .listeners-count {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Enhanced "Up Next" Card */
        .up-next-card {
            transition: all 0.3s ease;
        }
        
        .up-next-card:hover {
            transform: translateX(5px);
            box-shadow: -4px 0 12px rgba(88, 166, 255, 0.3);
        }

        /* Broadcast Tower Icon Animation */
        @keyframes broadcast-pulse {
            0%, 100% {
                transform: scale(1);
                filter: brightness(1);
            }
            50% {
                transform: scale(1.2);
                filter: brightness(1.3);
            }
        }

        /* DJ/Host Avatar Rotation on Hover */
        .dj-avatar {
            transition: transform 0.4s ease;
        }
        
        .dj-avatar:hover {
            transform: rotate(360deg);
        }

        /* Accessibility: Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            .now-playing-art,
            .now-playing-art:hover,
            .now-playing-album-container::before,
            .up-next-card,
            .up-next-card:hover,
            .dj-avatar,
            .dj-avatar:hover,
            .rating-btn,
            .rating-btn:hover,
            .rating-btn::before {
                transition: none !important;
                animation: none !important;
                transform: none !important;
            }
            
            .badge-live,
            .listeners-count,
            .now-playing-title,
            .now-playing-card,
            .progress-fill::after {
                animation: none !important;
            }
        }

        /* Hover effects for homepage content items */
        .news-item:hover,
        .event-item:hover,
        .poll-item:hover,
        .deal-item:hover,
        .game-item:hover {
            background: var(--color-bg-tertiary);
        }

        /* Game deal card styling */
        .deal-item, .game-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .deal-item:hover, .game-item:hover {
            transform: translateX(4px);
            box-shadow: -4px 0 12px rgba(88, 166, 255, 0.2);
        }

        /* Responsive grid for homepage content */
        .homepage-content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .homepage-content-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .homepage-content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @endpush

    @if(isset($error))
        <div class="alert alert-error">
            {{ $error }}
        </div>
    @endif

    <div class="grid grid-cols-3" style="grid-template-columns: 2fr 1fr;">
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
                        <div class="now-playing" id="now-playing" style="display: flex; gap: 1.5rem; align-items: center;">
                            <div class="now-playing-album-container" style="position: relative; flex-shrink: 0;">
                                <div style="width: 180px; height: 180px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1), 0 0 40px rgba(88, 166, 255, 0.15); position: relative; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <img src="{{ $nowPlaying->currentSong->art ?? '/images/default-album.png' }}"
                                         alt="Album art for {{ $nowPlaying->currentSong->title }} by {{ $nowPlaying->currentSong->artist }}"
                                         class="now-playing-art"
                                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                                    
                                    <!-- Animated Visualizer Overlay -->
                                    <div class="now-playing-equalizer" style="position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); display: flex; align-items: flex-end; gap: 3px; height: 24px; background: rgba(0,0,0,0.3); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); padding: 6px 12px; border-radius: 16px;" aria-hidden="true">
                                        <div class="eq-bar" style="width: 3px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate; height: 10px; box-shadow: 0 0 8px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 3px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.1s; height: 18px; box-shadow: 0 0 8px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 3px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.2s; height: 14px; box-shadow: 0 0 8px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 3px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.3s; height: 20px; box-shadow: 0 0 8px var(--color-accent);"></div>
                                        <div class="eq-bar" style="width: 3px; background: var(--color-accent); border-radius: 2px; animation: eqBounce 0.6s ease-in-out infinite alternate 0.4s; height: 12px; box-shadow: 0 0 8px var(--color-accent);"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="now-playing-info" style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 50%; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.4);">
                                        <i class="fas fa-play" aria-hidden="true" style="color: white; font-size: 0.75rem; margin-left: 2px;"></i>
                                    </span>
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-text-muted); font-weight: 600; margin-bottom: 0.25rem;">Now Playing</p>
                                        <div style="width: 100%; height: 2px; background: linear-gradient(90deg, var(--color-accent), transparent); border-radius: 1px;"></div>
                                    </div>
                                </div>
                                <h3 class="now-playing-title" id="song-title" style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.2; color: var(--color-text); background: linear-gradient(135deg, #ffffff, var(--color-accent), #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; background-size: 200% auto;">{{ $nowPlaying->currentSong->title }}</h3>
                                <p class="now-playing-artist" id="song-artist" style="font-size: 1.125rem; color: var(--color-text-secondary); font-weight: 500; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-user-music" style="font-size: 0.875rem; color: var(--color-accent);" aria-hidden="true"></i>
                                    {{ $nowPlaying->currentSong->artist }}
                                </p>
                                @if($nowPlaying->currentSong->album)
                                    <p style="color: var(--color-text-muted); font-size: 0.9375rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-compact-disc" style="margin-right: 0.25rem; color: var(--color-accent);" aria-hidden="true"></i>
                                        <span style="font-weight: 500;">{{ $nowPlaying->currentSong->album }}</span>
                                    </p>
                                @endif

                                <!-- Enhanced Song Rating UI -->
                                <div class="song-rating" id="song-rating"
                                     data-song-id="{{ $nowPlaying->currentSong->id }}"
                                     data-song-title="{{ $nowPlaying->currentSong->title }}"
                                     data-song-artist="{{ $nowPlaying->currentSong->artist }}"
                                     role="group"
                                     aria-label="Rate this song"
                                     style="margin-bottom: 1rem;">
                                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                                        <button class="rating-btn upvote" data-rating="1" title="Like this song" aria-label="Like this song" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: var(--color-bg-tertiary); border: 2px solid transparent; border-radius: 50px; transition: all 0.3s ease; font-weight: 600; cursor: pointer; color: var(--color-text);">
                                            <i class="fas fa-thumbs-up" aria-hidden="true" style="color: #43b581; font-size: 1rem;"></i>
                                            <span id="upvote-count" style="font-size: 0.9375rem;">0</span>
                                        </button>
                                        <button class="rating-btn downvote" data-rating="-1" title="Dislike this song" aria-label="Dislike this song" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: var(--color-bg-tertiary); border: 2px solid transparent; border-radius: 50px; transition: all 0.3s ease; font-weight: 600; cursor: pointer; color: var(--color-text);">
                                            <i class="fas fa-thumbs-down" aria-hidden="true" style="color: #f04747; font-size: 1rem;"></i>
                                            <span id="downvote-count" style="font-size: 0.9375rem;">0</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Enhanced Progress Bar -->
                                <div style="margin-bottom: 0.75rem;">
                                    <div class="progress-bar" role="progressbar" aria-label="Song progress" aria-valuenow="{{ $nowPlaying->elapsed }}" aria-valuemin="0" aria-valuemax="{{ $nowPlaying->duration }}" style="height: 6px; background: var(--color-bg-tertiary); border-radius: 8px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);">
                                        <div class="progress-fill" id="progress-fill" style="width: {{ $nowPlaying->progressPercentage() }}%; height: 100%; background: linear-gradient(90deg, var(--color-accent), #a855f7); border-radius: 8px; transition: width 0.3s ease; box-shadow: 0 0 8px var(--color-accent);"></div>
                                    </div>
                                    <div class="time-info" style="display: flex; justify-content: space-between; margin-top: 0.375rem; font-size: 0.8125rem; color: var(--color-text-muted); font-weight: 500;">
                                        <span id="elapsed-time">{{ gmdate('i:s', $nowPlaying->elapsed) }}</span>
                                        <span id="total-time">{{ gmdate('i:s', $nowPlaying->duration) }}</span>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Enhanced Audio Player Controls -->
                        <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.75rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border);">
                            <button id="play-btn" class="btn btn-primary" style="flex: 1; padding: 0.875rem 1.5rem; font-size: 1rem; font-weight: 600; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 0.625rem; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.3); transition: all 0.3s ease;">
                                <i class="fas fa-play" style="font-size: 1.125rem;"></i> 
                                <span>Listen Live</span>
                            </button>
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary" style="flex: 1; padding: 0.875rem 1.5rem; font-size: 1rem; font-weight: 600; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 0.625rem; transition: all 0.3s ease;">
                                <i class="fas fa-music" style="font-size: 1.125rem;"></i> 
                                <span>Request a Song</span>
                            </a>
                        </div>

                        <!-- Enhanced DJ/Host Info -->
                        <div style="margin-top: 1.25rem; padding: 1.25rem; background: linear-gradient(135deg, var(--color-bg-tertiary), rgba(88, 166, 255, 0.05)); border-radius: 10px; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--color-border); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);">
                            <div class="dj-avatar" style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(88, 166, 255, 0.4), 0 0 20px rgba(88, 166, 255, 0.2);">
                                <i class="fas fa-{{ $nowPlaying->isLive ? 'microphone' : 'robot' }}" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem;">{{ $nowPlaying->isLive ? 'Live DJ' : 'AutoDJ' }}</p>
                                <p style="color: var(--color-text-muted); font-size: 0.875rem;">{{ $nowPlaying->isLive ? 'Broadcasting live right now!' : 'Playing your favorite tracks 24/7' }}</p>
                            </div>
                            @if(isset($streamStatus))
                                <div style="text-align: right; padding: 0.625rem 1rem; background: var(--color-bg); border-radius: 8px;">
                                    <p style="font-size: 0.6875rem; color: var(--color-text-muted); margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Peak Today</p>
                                    <p style="font-weight: 700; color: var(--color-accent); font-size: 1.25rem;">{{ $streamStatus['peak_listeners'] ?? 0 }}</p>
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
                        Today's Schedule
                    </h2>
                </div>
                <div class="card-body">
                    <div class="schedule-list" id="schedule-list">
                        <div class="schedule-item active">
                            <div class="schedule-time">
                                <span class="schedule-hour">Now</span>
                            </div>
                            <div class="schedule-info">
                                <h4 class="schedule-title">{{ $nowPlaying?->isLive ? 'Live Show' : 'AutoDJ' }}</h4>
                                <p class="schedule-desc">Currently broadcasting</p>
                            </div>
                            <span class="badge badge-live">ON AIR</span>
                        </div>
                        <div class="schedule-fallback" id="schedule-fallback">
                            <p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">
                                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                                Schedule data unavailable. AutoDJ is playing your favorite tracks 24/7!
                            </p>
                        </div>
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
                <div class="card-header">
                    <h2 class="card-title" style="display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="fas fa-calendar-alt"></i> Upcoming Events</span>
                        <a href="{{ route('events.index') }}" class="text-sm" style="color: var(--color-accent); text-decoration: none;">View All →</a>
                    </h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    @foreach($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event->slug) }}" class="event-item" style="display: block; padding: 1rem; border-bottom: 1px solid var(--color-border); text-decoration: none; transition: background 0.2s;">
                        <div style="display: flex; gap: 1rem;">
                            <div style="background: var(--color-accent); color: white; border-radius: 8px; padding: 0.5rem; text-align: center; min-width: 60px; height: 60px; display: flex; flex-direction: column; justify-content: center; flex-shrink: 0;">
                                <div style="font-size: 1.25rem; font-weight: 700; line-height: 1;">{{ $event->starts_at->format('d') }}</div>
                                <div style="font-size: 0.75rem; text-transform: uppercase; opacity: 0.9;">{{ $event->starts_at->format('M') }}</div>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h3 style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9375rem;">{{ Str::limit($event->title, 50) }}</h3>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $event->starts_at->format('M j, Y g:i A') }}</span>
                                </div>
                                @if($event->location)
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ Str::limit($event->location, 30) }}</span>
                                </div>
                                @endif
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted);">
                                    <i class="fas fa-heart" style="color: #ef4444;"></i>
                                    <span>{{ $event->likesCount() }} {{ Str::plural('like', $event->likesCount()) }}</span>
                                </div>
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

    @push('scripts')
    <script>
        let audioPlayer = null;
        let isPlaying = false;

        function togglePlayback() {
            const btn = document.getElementById('play-btn');
            const nowPlayingEl = document.getElementById('now-playing');
            const streamUrl = 'https://radio.lossantosradio.com/listen/los_santos_radio/radio.mp3';

            if (!streamUrl) {
                alert('Stream URL not available');
                return;
            }

            if (!audioPlayer) {
                audioPlayer = new Audio(streamUrl);
                audioPlayer.addEventListener('playing', updatePlayState);
                audioPlayer.addEventListener('pause', updatePauseState);
                audioPlayer.addEventListener('ended', updatePauseState);
            }

            if (isPlaying) {
                audioPlayer.pause();
            } else {
                audioPlayer.play();
            }
        }

        function updatePlayState() {
            isPlaying = true;
            const btn = document.getElementById('play-btn');
            const nowPlayingEl = document.getElementById('now-playing');

            if (btn) btn.innerHTML = '<i class="fas fa-pause"></i> Stop Listening';
            if (nowPlayingEl) nowPlayingEl.classList.add('is-playing');
        }

        function updatePauseState() {
            isPlaying = false;
            const btn = document.getElementById('play-btn');
            const nowPlayingEl = document.getElementById('now-playing');

            if (btn) btn.innerHTML = '<i class="fas fa-play"></i> Listen Live';
            if (nowPlayingEl) nowPlayingEl.classList.remove('is-playing');
        }

        // Song rating functionality
        function rateSong(rating) {
            const ratingEl = document.getElementById('song-rating');
            const songId = ratingEl.dataset.songId;
            const songTitle = ratingEl.dataset.songTitle;
            const songArtist = ratingEl.dataset.songArtist;

            fetch('/api/ratings/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    song_id: songId,
                    song_title: songTitle,
                    song_artist: songArtist,
                    rating: rating
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('upvote-count').textContent = data.data.upvotes;
                    document.getElementById('downvote-count').textContent = data.data.downvotes;

                    // Update button states
                    const upvoteBtn = document.querySelector('.rating-btn.upvote');
                    const downvoteBtn = document.querySelector('.rating-btn.downvote');

                    upvoteBtn.classList.remove('active');
                    downvoteBtn.classList.remove('active');

                    if (data.action !== 'removed') {
                        if (rating === 1) upvoteBtn.classList.add('active');
                        if (rating === -1) downvoteBtn.classList.add('active');
                    }

                    // Show toast notification
                    if (data.action === 'removed') {
                        showToast('info', 'Rating removed');
                    } else if (data.action === 'created' || data.action === 'updated') {
                        showToast('success', rating === 1 ? 'Song liked!' : 'Song disliked');
                    }
                }
            })
            .catch(err => {
                console.error(err);
                showToast('error', 'Failed to rate song. Please try again.');
            });
        }

        // Load rating data for current song
        function loadSongRating() {
            const ratingEl = document.getElementById('song-rating');
            if (!ratingEl) return;

            const songId = ratingEl.dataset.songId;
            if (!songId) return;

            fetch(`/api/ratings/song/${encodeURIComponent(songId)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('upvote-count').textContent = data.data.upvotes;
                        document.getElementById('downvote-count').textContent = data.data.downvotes;

                        if (data.data.user_rating === 1) {
                            document.querySelector('.rating-btn.upvote').classList.add('active');
                        } else if (data.data.user_rating === -1) {
                            document.querySelector('.rating-btn.downvote').classList.add('active');
                        }
                    }
                })
                .catch(console.error);
        }

        // Load trending songs
        function loadTrendingSongs() {
            fetch('/api/ratings/trending')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('trending-songs');
                    if (data.success && data.data.length > 0) {
                        container.innerHTML = data.data.map((song, index) => `
                            <div class="trending-item">
                                <span class="trending-rank">#${index + 1}</span>
                                <div class="trending-info">
                                    <p class="trending-title">${song.song_title}</p>
                                    <p class="trending-artist">${song.song_artist}</p>
                                </div>
                                <span class="trending-score">
                                    <i class="fas fa-heart" style="color: #ef4444;"></i>
                                    ${song.score}
                                </span>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">No trending songs yet. Rate songs to see them here!</p>';
                    }
                })
                .catch(() => {
                    document.getElementById('trending-songs').innerHTML = '<p style="color: var(--color-text-muted); text-align: center; padding: 1rem;">Unable to load trending songs.</p>';
                });
        }

        // Update progress bar and song info
        document.addEventListener('nowPlayingUpdate', function(e) {
            const data = e.detail;

            // Update song info
            const songTitle = document.getElementById('song-title');
            const songArtist = document.getElementById('song-artist');
            if (songTitle) songTitle.textContent = data.current_song.title;
            if (songArtist) songArtist.textContent = data.current_song.artist;

            // Update listener count
            const listenerCount = document.querySelector('.listeners-count');
            if (listenerCount && data.listeners !== undefined) {
                listenerCount.innerHTML = '<i class="fas fa-headphones"></i> ' + data.listeners + ' listeners';
            }

            // Update rating data attributes and reload
            const ratingEl = document.getElementById('song-rating');
            if (ratingEl && ratingEl.dataset.songId !== data.current_song.id) {
                ratingEl.dataset.songId = data.current_song.id;
                ratingEl.dataset.songTitle = data.current_song.title;
                ratingEl.dataset.songArtist = data.current_song.artist;
                loadSongRating();
            }

            // Update progress
            const progressFill = document.getElementById('progress-fill');
            if (progressFill) {
                const progress = data.duration > 0 ? (data.elapsed / data.duration) * 100 : 0;
                progressFill.style.width = progress + '%';
            }

            // Update times
            const elapsedTime = document.getElementById('elapsed-time');
            const totalTime = document.getElementById('total-time');
            if (elapsedTime) elapsedTime.textContent = formatTime(data.elapsed);
            if (totalTime) totalTime.textContent = formatTime(data.duration);
        });

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
        }

        // Scroll to top functionality with throttling
        function createScrollToTop() {
            // Prevent duplicate scroll indicators
            if (document.querySelector('.scroll-indicator')) return;

            const scrollBtn = document.createElement('div');
            scrollBtn.className = 'scroll-indicator';
            scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
            scrollBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
            document.body.appendChild(scrollBtn);

            let ticking = false;
            let lastScrollY = window.scrollY;

            window.addEventListener('scroll', () => {
                lastScrollY = window.scrollY;

                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        if (lastScrollY > 300) {
                            scrollBtn.classList.add('visible');
                        } else {
                            scrollBtn.classList.remove('visible');
                        }
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        // Add entrance animations using CSS classes
        function addEntranceAnimations() {
            // Respect user's motion preferences
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            const cards = document.querySelectorAll('.card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('card-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach((card, index) => {
                card.classList.add('card-entrance');
                card.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(card);
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listener to play button
            const playBtn = document.getElementById('play-btn');
            if (playBtn) {
                playBtn.addEventListener('click', togglePlayback);
            }

            // Attach event listeners to rating buttons
            document.querySelectorAll('.rating-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    rateSong(rating);
                });
            });

            loadSongRating();
            loadTrendingSongs();
            createScrollToTop();
            addEntranceAnimations();
            initHighPerformanceUpdates();
        });

        // High-performance Now Playing updates using SSE
        function initHighPerformanceUpdates() {
            fetch('/api/nowplaying/sse-config')
                .then(response => response.json())
                .then(config => {
                    if (config.success && config.sse_enabled) {
                        // Use SSE for real-time updates
                        initSSEUpdates(config);
                    } else {
                        // Fall back to polling
                        initPollingUpdates(config.polling_interval || 15);
                    }
                })
                .catch(() => {
                    // Default to polling on error
                    initPollingUpdates(15);
                });
        }

        // Initialize SSE-based updates
        function initSSEUpdates(config) {
            // Connect directly to AzuraCast's SSE endpoint
            const sseUrl = new URL(config.sse_url);
            Object.keys(config.sse_params || {}).forEach(key => {
                sseUrl.searchParams.append(key, config.sse_params[key]);
            });

            let eventSource = null;
            let reconnectAttempts = 0;
            const maxReconnectAttempts = 5;
            const reconnectDelay = 3000;

            function connect() {
                try {
                    eventSource = new EventSource(sseUrl.toString());
                } catch (err) {
                    console.error('Failed to create EventSource:', err);
                    // Fall back to polling immediately
                    initPollingUpdates(config.polling_interval || 15);
                    return;
                }

                eventSource.addEventListener('message', function(event) {
                    try {
                        const data = JSON.parse(event.data);
                        // Check if this is a nowplaying update for our station
                        if (data.np) {
                            updateNowPlayingUI(data.np);
                        }
                    } catch (e) {
                        console.error('SSE parse error:', e);
                    }
                });

                eventSource.addEventListener('open', function() {
                    console.log('SSE connected');
                    reconnectAttempts = 0;
                });

                eventSource.addEventListener('error', function(event) {
                    console.warn('SSE error, will reconnect...');
                    eventSource.close();

                    if (reconnectAttempts < maxReconnectAttempts) {
                        reconnectAttempts++;
                        // True exponential backoff: 3s, 6s, 12s, 24s, 48s
                        setTimeout(connect, reconnectDelay * Math.pow(2, reconnectAttempts - 1));
                    } else {
                        console.log('SSE max reconnects reached, falling back to polling');
                        initPollingUpdates(config.polling_interval || 15);
                    }
                });
            }

            connect();

            // Clean up on page unload
            window.addEventListener('beforeunload', () => {
                if (eventSource) {
                    eventSource.close();
                }
            });
        }

        // Initialize polling-based updates
        function initPollingUpdates(interval) {
            setInterval(function() {
                fetch('/api/nowplaying/')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateNowPlayingUI(data.data);
                        }
                    })
                    .catch(console.error);
            }, interval * 1000);
        }

        // Update UI with now playing data
        function updateNowPlayingUI(data) {
            // Dispatch custom event for other listeners
            document.dispatchEvent(new CustomEvent('nowPlayingUpdate', { detail: data }));

            // Update song info
            const songTitle = document.getElementById('song-title');
            const songArtist = document.getElementById('song-artist');
            const currentSong = data.current_song || data.currentSong;

            if (songTitle && currentSong) {
                songTitle.textContent = currentSong.title;
            }
            if (songArtist && currentSong) {
                songArtist.textContent = currentSong.artist;
            }

            // Update album art if available
            const artElement = document.querySelector('.now-playing-art');
            if (artElement && currentSong && currentSong.art) {
                artElement.src = currentSong.art;
            }

            // Update listener count
            const listenerCount = document.querySelector('.listeners-count');
            if (listenerCount && data.listeners !== undefined) {
                listenerCount.innerHTML = '<i class="fas fa-headphones"></i> ' + data.listeners + ' listeners';
            }

            // Update progress
            const progressFill = document.getElementById('progress-fill');
            const duration = data.duration || 0;
            const elapsed = data.elapsed || 0;
            if (progressFill && duration > 0) {
                const progress = (elapsed / duration) * 100;
                progressFill.style.width = progress + '%';
            }

            // Update times
            const elapsedTime = document.getElementById('elapsed-time');
            const totalTime = document.getElementById('total-time');
            if (elapsedTime) elapsedTime.textContent = formatTime(elapsed);
            if (totalTime) totalTime.textContent = formatTime(duration);

            // Update rating data
            const ratingEl = document.getElementById('song-rating');
            if (ratingEl && currentSong) {
                const songId = currentSong.id || currentSong.song_id;
                if (ratingEl.dataset.songId !== songId) {
                    ratingEl.dataset.songId = songId;
                    ratingEl.dataset.songTitle = currentSong.title;
                    ratingEl.dataset.songArtist = currentSong.artist;
                    loadSongRating();
                }
            }
        }
    </script>
    <style>
        /* Equalizer bar animation for Now Playing */
        @keyframes eqBounce {
            0% { transform: scaleY(0.3); }
            100% { transform: scaleY(1); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(88, 166, 255, 0.3); }
            50% { box-shadow: 0 0 40px rgba(88, 166, 255, 0.5); }
        }

        @keyframes vinyl-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes slide-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .now-playing-card {
            overflow: hidden;
            position: relative;
        }

        .now-playing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--color-accent), #a855f7, #38bdf8);
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }

        .now-playing-album-container {
            position: relative;
            flex-shrink: 0;
        }

        .now-playing-art {
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .now-playing.is-playing .now-playing-art {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .now-playing-art:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 40px rgba(88, 166, 255, 0.2);
        }

        .now-playing-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--color-text-primary), var(--color-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
            animation: slide-in-up 0.3s ease-out;
        }

        .now-playing-artist {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            margin-bottom: 0.75rem;
        }

        .progress-bar {
            height: 6px;
            background: var(--color-bg-tertiary);
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-accent), #a855f7);
            border-radius: 3px;
            transition: width 1s linear;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            right: 0;
            top: -0.1875rem;
            bottom: -0.1875rem;
            width: 0.75rem;
            height: 0.75rem;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 0.625rem rgba(88, 166, 255, 0.5);
        }

        .time-info {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: var(--color-text-muted);
            font-variant-numeric: tabular-nums;
        }

        .rating-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border: 1px solid var(--color-border);
            background: var(--color-bg-tertiary);
            color: var(--color-text-secondary);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .rating-btn:hover {
            transform: translateY(-2px);
            border-color: var(--color-accent);
        }

        .rating-btn.upvote:hover,
        .rating-btn.upvote.active {
            background: rgba(34, 197, 94, 0.15);
            border-color: #22c55e;
            color: #22c55e;
        }

        .rating-btn.downvote:hover,
        .rating-btn.downvote.active {
            background: rgba(239, 68, 68, 0.15);
            border-color: #ef4444;
            color: #ef4444;
        }

        .song-rating {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .listeners-count {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: var(--color-bg-tertiary);
            border-radius: 20px;
            font-size: 0.875rem;
            color: var(--color-text-secondary);
        }

        .listeners-count i {
            color: var(--color-accent);
        }

        .badge-live {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
        }

        .pulse-animation {
            animation: pulse-live 2s infinite;
        }

        @keyframes pulse-live {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .now-playing-equalizer {
            opacity: 0.8;
        }

        .now-playing.is-playing .now-playing-equalizer {
            opacity: 1;
        }

        /* Responsive adjustments for Now Playing */
        @media (max-width: 768px) {
            .now-playing {
                flex-direction: column;
                text-align: center;
            }

            .now-playing-album-container {
                margin-bottom: 1rem;
            }

            .now-playing-art {
                width: 180px !important;
                height: 180px !important;
            }
        }
    </style>
    @endpush
</x-layouts.app>
