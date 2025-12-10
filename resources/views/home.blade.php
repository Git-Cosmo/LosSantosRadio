<x-layouts.app>
    <x-slot:title>Los Santos Radio - Your 24/7 Online Radio & Gaming Hub</x-slot:title>

    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section with Now Playing -->
        <section class="mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-6">
                <!-- Main Hero Content -->
                <div>
                    <div class="text-center lg:text-left mb-8">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                            Los Santos Radio
                        </h1>
                        <p class="text-xl text-gray-400 mb-6">
                            Your 24/7 online radio station featuring music streaming, gaming content, and an active community
                        </p>
                        <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                            <button 
                                onclick="toggleListenModal()" 
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                                <i class="fas fa-headphones mr-2"></i>
                                Start Listening
                            </button>
                            <a 
                                href="{{ route('requests.index') }}" 
                                class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition-all">
                                <i class="fas fa-music mr-2"></i>
                                Request a Song
                            </a>
                        </div>
                    </div>

                    <!-- Now Playing Card -->
                    @if($nowPlaying)
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 border border-gray-700 shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold flex items-center gap-2">
                                <i class="fas fa-broadcast-tower text-blue-500"></i>
                                Now Playing
                            </h2>
                            <div class="flex items-center gap-3">
                                @if(isset($streamStatus) && $streamStatus['is_online'])
                                <span class="px-3 py-1 bg-red-600 text-white text-sm font-bold rounded-full flex items-center gap-1 animate-pulse">
                                    <i class="fas fa-circle text-xs"></i>
                                    LIVE
                                </span>
                                @endif
                                <span class="px-3 py-1 bg-gray-700 rounded-full text-sm font-semibold flex items-center gap-2">
                                    <i class="fas fa-headphones text-blue-500"></i>
                                    {{ $nowPlaying->listeners ?? 0 }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 items-center">
                            <div class="relative flex-shrink-0">
                                <img 
                                    src="{{ $nowPlaying->currentSong->art ?? '/images/default-album.svg' }}" 
                                    alt="{{ $nowPlaying->currentSong->title }}"
                                    class="w-24 h-24 md:w-32 md:h-32 rounded-lg shadow-lg object-cover"
                                    onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%2321262d%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22%238b949e%22 font-size=%2230%22>🎵</text></svg>'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-2xl font-bold mb-1 truncate bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                    {{ $nowPlaying->currentSong->title }}
                                </h3>
                                <p class="text-gray-400 mb-3 truncate">
                                    <i class="fas fa-user-music text-sm mr-1"></i>
                                    {{ $nowPlaying->currentSong->artist }}
                                </p>
                                <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all" style="width: {{ $nowPlaying->progressPercentage() }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ gmdate('i:s', $nowPlaying->elapsed) }}</span>
                                    <span>{{ gmdate('i:s', $nowPlaying->duration) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-800 rounded-lg p-8 border border-gray-700 text-center">
                        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
                        <p class="text-gray-400">Loading now playing information...</p>
                    </div>
                    @endif
                </div>

                <!-- Quick Stats Sidebar -->
                <div class="space-y-4">
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-500"></i>
                            Station Stats
                        </h3>
                        <div class="space-y-3">
                            @if(isset($streamStatus))
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Peak Listeners</span>
                                <span class="text-2xl font-bold text-blue-500">{{ $streamStatus['peak_listeners'] ?? 0 }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Status</span>
                                <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                        <div class="space-y-2">
                            <a href="{{ route('schedule') }}" class="block px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Schedule
                            </a>
                            <a href="{{ route('djs.index') }}" class="block px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                                <i class="fas fa-microphone mr-2 text-purple-500"></i>
                                DJ Profiles
                            </a>
                            <button @click="$dispatch('open-search-modal')" class="w-full text-left px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                                <i class="fas fa-search mr-2 text-green-500"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Grid: News, Events, Deals -->
        <section class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Latest News -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-newspaper text-yellow-500"></i>
                            Latest News
                        </h2>
                        <a href="{{ route('news.index') }}" class="text-blue-500 hover:text-blue-400 text-sm">View All →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all">
                            <h3 class="font-semibold mb-2 line-clamp-2">{{ $news->title }}</h3>
                            <p class="text-sm text-gray-400 line-clamp-2">{{ $news->excerpt }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $news->published_at->diffForHumans() }}
                            </p>
                        </a>
                        @empty
                        <p class="text-gray-400 text-center py-4">No news available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-calendar-check text-green-500"></i>
                            Upcoming Events
                        </h2>
                        <a href="{{ route('events.index') }}" class="text-blue-500 hover:text-blue-400 text-sm">View All →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($upcomingEvents as $event)
                        <a href="{{ route('events.show', $event->slug) }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold line-clamp-1 flex-1">{{ $event->title }}</h3>
                                @if($event->likes_count > 0)
                                <span class="text-xs text-gray-400">
                                    <i class="fas fa-heart text-red-500"></i> {{ $event->likes_count }}
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-400 line-clamp-2">{{ Str::limit($event->description, 80) }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $event->starts_at->format('M j, Y - g:i A') }}
                            </p>
                        </a>
                        @empty
                        <p class="text-gray-400 text-center py-4">No upcoming events</p>
                        @endforelse
                    </div>
                </div>

                <!-- Hot Game Deals -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-fire text-orange-500"></i>
                            Hot Deals
                        </h2>
                        <a href="{{ route('games.deals') }}" class="text-blue-500 hover:text-blue-400 text-sm">View All →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($topGameDeals as $deal)
                        <a href="{{ route('games.deals.show', $deal->slug) }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all">
                            <div class="flex items-start gap-3">
                                @if($deal->thumb)
                                <img src="{{ $deal->thumb }}" alt="{{ $deal->title }}" class="w-16 h-16 rounded object-cover flex-shrink-0">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold mb-1 line-clamp-1">{{ $deal->title }}</h3>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">
                                            -{{ round($deal->savings_percent) }}%
                                        </span>
                                        <span class="text-lg font-bold text-green-500">${{ $deal->sale_price }}</span>
                                        <span class="text-sm text-gray-400 line-through">${{ $deal->normal_price }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-gray-400 text-center py-4">No deals available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- Free Games & Polls -->
        <section class="mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Free Games -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-gift text-pink-500"></i>
                            Free Games
                        </h2>
                        <a href="{{ route('games.free') }}" class="text-blue-500 hover:text-blue-400 text-sm">View All →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($freeGames as $game)
                        <a href="{{ route('games.free.show', $game->slug) }}" class="block p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all">
                            <div class="flex items-center gap-3">
                                @if($game->image)
                                <img src="{{ $game->image }}" alt="{{ $game->title }}" class="w-20 h-20 rounded object-cover flex-shrink-0">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold mb-1">{{ $game->title }}</h3>
                                    <p class="text-sm text-gray-400 mb-1">{{ $game->platform }}</p>
                                    @if($game->ends_at)
                                    <p class="text-xs text-orange-400">
                                        <i class="fas fa-clock mr-1"></i>
                                        Ends {{ $game->ends_at->diffForHumans() }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-gray-400 text-center py-4">No free games available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Active Polls -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-poll text-teal-500"></i>
                            Community Polls
                        </h2>
                        <a href="{{ route('polls.index') }}" class="text-blue-500 hover:text-blue-400 text-sm">View All →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($activePolls as $poll)
                        <div class="p-4 bg-gray-700 rounded-lg">
                            <h3 class="font-semibold mb-3">{{ $poll->question }}</h3>
                            <div class="space-y-2">
                                @foreach($poll->options as $option)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-300">{{ $option->option_text }}</span>
                                        <span class="text-gray-400">{{ $option->votes_count ?? 0 }} votes</span>
                                    </div>
                                    <div class="w-full bg-gray-600 rounded-full h-2">
                                        @php
                                            $percentage = $poll->total_votes > 0 ? (($option->votes_count ?? 0) / $poll->total_votes) * 100 : 0;
                                        @endphp
                                        <div class="bg-teal-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="{{ route('polls.show', $poll->slug) }}" class="block text-center mt-3 text-blue-500 hover:text-blue-400 text-sm">
                                Vote Now →
                            </a>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center py-4">No active polls</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-gradient-to-r from-blue-900/30 to-purple-900/30 rounded-lg border border-gray-700 p-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Join the Community?</h2>
            <p class="text-gray-400 mb-6 max-w-2xl mx-auto">
                Sign in with your favorite gaming platform to unlock more features, request songs, and interact with the community.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                @auth
                <a href="{{ route('profile.show', auth()->user()) }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-user mr-2"></i>
                    View Profile
                </a>
                @else
                <a href="{{ route('login') }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </a>
                @endauth
                <button onclick="toggleListenModal()" class="px-8 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-headphones mr-2"></i>
                    Start Listening
                </button>
            </div>
        </section>
    </div>
</x-layouts.app>
