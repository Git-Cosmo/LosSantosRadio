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

        // Load schedule from API
        function loadSchedule() {
            const stationId = 1; // Default station ID
            fetch(`/api/station/${stationId}/playlists`)
                .then(response => response.json())
                .then(data => {
                    const scheduleContent = document.getElementById('schedule-content');
                    const scheduleLoading = document.getElementById('schedule-loading');
                    const scheduleFallback = document.getElementById('schedule-fallback');
                    
                    if (scheduleLoading) scheduleLoading.style.display = 'none';
                    
                    if (data.success && data.data && data.data.length > 0) {
                        // Filter playlists that have schedules and are enabled
                        const scheduledPlaylists = data.data.filter(p => 
                            p.is_enabled && 
                            p.formatted_schedule && 
                            p.formatted_schedule.length > 0
                        );
                        
                        if (scheduledPlaylists.length > 0) {
                            if (scheduleFallback) scheduleFallback.style.display = 'none';
                            
                            // Group schedules by day
                            const schedulesByDay = {};
                            const dayOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            
                            scheduledPlaylists.forEach(playlist => {
                                playlist.formatted_schedule.forEach(schedule => {
                                    if (!schedulesByDay[schedule.day]) {
                                        schedulesByDay[schedule.day] = [];
                                    }
                                    schedulesByDay[schedule.day].push({
                                        ...schedule,
                                        playlistName: playlist.name,
                                        isActive: playlist.is_currently_active
                                    });
                                });
                            });
                            
                            // Get current day
                            const currentDay = dayOrder[new Date().getDay()];
                            
                            // Clear schedule content
                            scheduleContent.innerHTML = '';
                            
                            // Render schedule using DOM manipulation to prevent XSS
                            dayOrder.forEach(day => {
                                if (schedulesByDay[day] && schedulesByDay[day].length > 0) {
                                    const isToday = day === currentDay;
                                    
                                    // Create day section
                                    const daySection = document.createElement('div');
                                    daySection.className = 'schedule-day-section';
                                    
                                    // Create day header
                                    const dayHeader = document.createElement('h3');
                                    dayHeader.className = 'schedule-day-header';
                                    
                                    // Add icon
                                    const icon = document.createElement('i');
                                    icon.className = isToday ? 'fas fa-calendar-day schedule-day-icon-today' : 'far fa-calendar schedule-day-icon';
                                    dayHeader.appendChild(icon);
                                    
                                    // Add day name
                                    const dayText = document.createTextNode(' ' + day);
                                    dayHeader.appendChild(dayText);
                                    
                                    // Add today badge if applicable
                                    if (isToday) {
                                        const todayBadge = document.createElement('span');
                                        todayBadge.className = 'schedule-today-badge';
                                        todayBadge.textContent = 'Today';
                                        dayHeader.appendChild(todayBadge);
                                    }
                                    
                                    daySection.appendChild(dayHeader);
                                    
                                    // Create schedule items container
                                    const scheduleItems = document.createElement('div');
                                    scheduleItems.className = 'schedule-items-container';
                                    
                                    schedulesByDay[day].forEach(schedule => {
                                        const isActive = schedule.isActive && isToday;
                                        
                                        // Create schedule item
                                        const scheduleItem = document.createElement('div');
                                        scheduleItem.className = 'schedule-item' + (isActive ? ' active' : '');
                                        
                                        // Create time section
                                        const timeDiv = document.createElement('div');
                                        timeDiv.className = 'schedule-time';
                                        const timeSpan = document.createElement('span');
                                        timeSpan.textContent = schedule.start_time + ' - ' + schedule.end_time;
                                        timeDiv.appendChild(timeSpan);
                                        scheduleItem.appendChild(timeDiv);
                                        
                                        // Create info section
                                        const infoDiv = document.createElement('div');
                                        infoDiv.className = 'schedule-info';
                                        const infoTitle = document.createElement('h4');
                                        infoTitle.textContent = schedule.playlistName; // Safe - uses textContent
                                        infoDiv.appendChild(infoTitle);
                                        scheduleItem.appendChild(infoDiv);
                                        
                                        // Add ON AIR badge if active
                                        if (isActive) {
                                            const badge = document.createElement('span');
                                            badge.className = 'badge badge-live schedule-live-badge';
                                            badge.textContent = 'ON AIR';
                                            scheduleItem.appendChild(badge);
                                        }
                                        
                                        scheduleItems.appendChild(scheduleItem);
                                    });
                                    
                                    daySection.appendChild(scheduleItems);
                                    scheduleContent.appendChild(daySection);
                                }
                            });
                        } else {
                            // No scheduled playlists
                            if (scheduleFallback) scheduleFallback.style.display = 'block';
                        }
                    } else {
                        // No playlists or error
                        if (scheduleFallback) scheduleFallback.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Failed to load schedule:', err);
                    const scheduleLoading = document.getElementById('schedule-loading');
                    const scheduleFallback = document.getElementById('schedule-fallback');
                    if (scheduleLoading) scheduleLoading.style.display = 'none';
                    if (scheduleFallback) scheduleFallback.style.display = 'block';
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
            loadSchedule();
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
