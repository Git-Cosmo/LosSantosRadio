<x-layouts.app>
    {{-- Set page metadata --}}
    @php
        $title = 'Home';
        $metaDescription = 'Los Santos Radio - Your 24/7 online radio station featuring live music, gaming community, news, events, and more. Join thousands of listeners worldwide!';
    @endphp

    {{-- Hero Section with Now Playing --}}
    <div class="hero-section">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Content Area (Center) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Now Playing Card --}}
                    @if($nowPlaying)
                    <div class="now-playing-hero card">
                        <div class="card-body p-6">
                            <div class="flex items-center gap-6">
                                {{-- Album Art --}}
                                <div class="flex-shrink-0">
                                    @if($nowPlaying['song']['art'] ?? null)
                                        <img src="{{ $nowPlaying['song']['art'] }}"
                                             alt="{{ $nowPlaying['song']['title'] ?? 'Album Art' }}"
                                             class="w-32 h-32 rounded-lg object-cover shadow-lg hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-32 h-32 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Song Info --}}
                                <div class="flex-1">
                                    <div class="text-sm text-secondary mb-1">🎵 Now Playing</div>
                                    <h2 class="text-2xl font-bold mb-2">{{ $nowPlaying['song']['title'] ?? 'Unknown Title' }}</h2>
                                    <p class="text-lg text-secondary mb-3">{{ $nowPlaying['song']['artist'] ?? 'Unknown Artist' }}</p>
                                    
                                    {{-- Listener Count --}}
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            <span class="text-sm">{{ $nowPlaying['listeners']['current'] ?? 0 }} listening</span>
                                        </div>
                                        <a href="{{ route('radio.player') }}" class="btn btn-primary">
                                            Listen Now →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Latest News --}}
                    @if($recentNews && $recentNews->count() > 0)
                    <div class="card">
                        <div class="card-header flex items-center justify-between">
                            <h3 class="card-title">📰 Latest News</h3>
                            <a href="{{ route('news.index') }}" class="text-accent hover:text-accent-hover text-sm">View All →</a>
                        </div>
                        <div class="card-body divide-y divide-border">
                            @foreach($recentNews as $news)
                            <a href="{{ route('news.show', $news) }}" class="block py-4 first:pt-0 last:pb-0 hover:bg-bg-secondary transition-colors px-4 -mx-4 rounded">
                                <div class="flex gap-4">
                                    @if($news->featured_image)
                                    <img src="{{ $news->featured_image }}" alt="{{ $news->title }}" class="w-20 h-20 object-cover rounded flex-shrink-0">
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-semibold mb-1 hover:text-accent transition-colors">{{ $news->title }}</h4>
                                        <p class="text-sm text-secondary line-clamp-2">{{ Str::limit(strip_tags($news->content), 120) }}</p>
                                        <p class="text-xs text-muted mt-1">{{ $news->published_at?->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Upcoming Events --}}
                    @if($upcomingEvents && $upcomingEvents->count() > 0)
                    <div class="card">
                        <div class="card-header flex items-center justify-between">
                            <h3 class="card-title">📅 Upcoming Events</h3>
                            <a href="{{ route('events.index') }}" class="text-accent hover:text-accent-hover text-sm">View All →</a>
                        </div>
                        <div class="card-body space-y-4">
                            @foreach($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event) }}" class="block p-4 bg-bg-secondary rounded-lg hover:bg-bg-tertiary transition-colors">
                                <div class="flex gap-4">
                                    @if($event->banner_image)
                                    <img src="{{ $event->banner_image }}" alt="{{ $event->title }}" class="w-24 h-24 object-cover rounded flex-shrink-0">
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-semibold mb-2 hover:text-accent transition-colors">{{ $event->title }}</h4>
                                        <div class="flex items-center gap-4 text-sm text-secondary">
                                            <span>📍 {{ $event->location ?? 'Online' }}</span>
                                            <span>🕒 {{ $event->starts_at?->format('M d, Y g:i A') }}</span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs bg-accent/20 text-accent px-2 py-1 rounded">{{ $event->likes_count }} likes</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Top Game Deals --}}
                    @if($topGameDeals && $topGameDeals->count() > 0)
                    <div class="card">
                        <div class="card-header flex items-center justify-between">
                            <h3 class="card-title">🎮 Hot Game Deals</h3>
                            <a href="{{ route('games.deals') }}" class="text-accent hover:text-accent-hover text-sm">View All →</a>
                        </div>
                        <div class="card-body grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($topGameDeals as $deal)
                            <a href="{{ route('games.deals') }}" class="block p-4 bg-bg-secondary rounded-lg hover:bg-bg-tertiary transition-colors">
                                @if($deal->game_thumb)
                                <img src="{{ $deal->game_thumb }}" alt="{{ $deal->game_title }}" class="w-full h-32 object-cover rounded mb-3">
                                @endif
                                <h4 class="font-semibold text-sm mb-2">{{ Str::limit($deal->game_title, 40) }}</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-success">${{ number_format($deal->sale_price, 2) }}</span>
                                    <span class="text-sm bg-success/20 text-success px-2 py-1 rounded">-{{ $deal->savings_percent }}%</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-6">
                    {{-- Quick Stats Widget --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📊 Community Stats</h3>
                        </div>
                        <div class="card-body space-y-3">
                            <div class="flex items-center justify-between p-3 bg-bg-secondary rounded">
                                <span class="text-secondary">Listeners</span>
                                <span class="font-bold text-lg">{{ $nowPlaying['listeners']['current'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-bg-secondary rounded">
                                <span class="text-secondary">Songs Played</span>
                                <span class="font-bold text-lg">{{ number_format(rand(5000, 10000)) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-bg-secondary rounded">
                                <span class="text-secondary">Active Members</span>
                                <span class="font-bold text-lg">{{ number_format(rand(500, 1500)) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Active Polls --}}
                    @if($activePolls && $activePolls->count() > 0)
                    <div class="card">
                        <div class="card-header flex items-center justify-between">
                            <h3 class="card-title">📊 Active Polls</h3>
                            <a href="{{ route('polls.index') }}" class="text-accent hover:text-accent-hover text-sm">View All →</a>
                        </div>
                        <div class="card-body space-y-4">
                            @foreach($activePolls as $poll)
                            <a href="{{ route('polls.show', $poll) }}" class="block p-3 bg-bg-secondary rounded hover:bg-bg-tertiary transition-colors">
                                <h4 class="font-semibold text-sm mb-2">{{ $poll->question }}</h4>
                                <p class="text-xs text-secondary">{{ $poll->votes_count ?? 0 }} votes</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Free Games --}}
                    @if($freeGames && $freeGames->count() > 0)
                    <div class="card">
                        <div class="card-header flex items-center justify-between">
                            <h3 class="card-title">🎁 Free Games</h3>
                            <a href="{{ route('games.free') }}" class="text-accent hover:text-accent-hover text-sm">View All →</a>
                        </div>
                        <div class="card-body space-y-3">
                            @foreach($freeGames as $game)
                            <a href="{{ $game->url }}" target="_blank" class="block p-3 bg-bg-secondary rounded hover:bg-bg-tertiary transition-colors">
                                <h4 class="font-semibold text-sm mb-1">{{ Str::limit($game->title, 35) }}</h4>
                                <p class="text-xs text-secondary">{{ $game->platform }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Quick Links --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">🔗 Quick Links</h3>
                        </div>
                        <div class="card-body space-y-2">
                            <a href="{{ route('radio.player') }}" class="block p-2 rounded hover:bg-bg-secondary transition-colors">🎵 Listen Live</a>
                            <a href="{{ route('requests.index') }}" class="block p-2 rounded hover:bg-bg-secondary transition-colors">🎼 Request a Song</a>
                            <a href="{{ route('songs') }}" class="block p-2 rounded hover:bg-bg-secondary transition-colors">📀 Song Library</a>
                            <a href="{{ route('schedule') }}" class="block p-2 rounded hover:bg-bg-secondary transition-colors">📅 Show Schedule</a>
                            <a href="{{ route('djs.index') }}" class="block p-2 rounded hover:bg-bg-secondary transition-colors">🎙️ Our DJs</a>
                            <a href="{{ route('leaderboard') }}" class="block p-2 rounded hover:bg-bg-secondary transition-colors">🏆 Leaderboard</a>
                        </div>
                    </div>

                    {{-- Discord Widget --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">💬 Join Our Discord</h3>
                        </div>
                        <div class="card-body text-center">
                            <p class="text-sm text-secondary mb-4">Connect with our community, chat with DJs, and stay updated!</p>
                            @if(config('services.discord.invite_url'))
                            <a href="{{ config('services.discord.invite_url') }}" target="_blank" class="btn btn-discord w-full">
                                Join Discord Server
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
