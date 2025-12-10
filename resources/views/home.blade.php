<x-layouts.app>
    <x-slot:title>Welcome to Los Santos Radio</x-slot:title>

    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="mb-12 text-center">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                    Welcome to Los Santos Radio
                </h1>
                <p class="text-xl text-gray-400 mb-8">
                    Your 24/7 online radio station featuring music streaming, gaming content, and an active community
                </p>
                
                <!-- Call-to-Action Buttons -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <button 
                        @click="$dispatch('open-search-modal')" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>
                        Try Search Modal
                    </button>
                    
                    <button 
                        onclick="toggleListenModal()" 
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-headphones mr-2"></i>
                        How to Listen Modal
                    </button>
                    
                    <a 
                        href="{{ route('login') }}" 
                        class="px-6 py-3 bg-gradient-to-r from-pink-600 to-red-600 hover:from-pink-700 hover:to-red-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In (OAuth)
                    </a>
                </div>
            </div>
        </section>

        <!-- Modal Showcase Section -->
        <section class="mb-12">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold mb-6 text-center">
                    <i class="fas fa-window-restore mr-2 text-blue-500"></i>
                    Modal Components Showcase
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Search Modal Card -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 hover:border-blue-500 transition-all hover:shadow-lg hover:shadow-blue-500/20">
                        <div class="text-4xl mb-4 text-center text-blue-500">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-center">Search Modal</h3>
                        <p class="text-gray-400 text-sm mb-4 text-center">
                            Powerful search across news, events, games, and videos with real-time results.
                        </p>
                        <button 
                            @click="$dispatch('open-search-modal')" 
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            Open Search
                        </button>
                        <div class="mt-4 pt-4 border-t border-gray-700">
                            <p class="text-xs text-gray-500 mb-2"><strong>Features:</strong></p>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li><i class="fas fa-check text-green-500 mr-1"></i> Alpine.js powered</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> Debounced search</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> ESC to close</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> API-driven results</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Listen Modal Card -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 hover:border-green-500 transition-all hover:shadow-lg hover:shadow-green-500/20">
                        <div class="text-4xl mb-4 text-center text-green-500">
                            <i class="fas fa-headphones"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-center">Listen Modal</h3>
                        <p class="text-gray-400 text-sm mb-4 text-center">
                            Multiple ways to tune in: popup player, stream URL, mobile apps, and smart speakers.
                        </p>
                        <button 
                            onclick="toggleListenModal()" 
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            How to Listen
                        </button>
                        <div class="mt-4 pt-4 border-t border-gray-700">
                            <p class="text-xs text-gray-500 mb-2"><strong>Features:</strong></p>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li><i class="fas fa-check text-green-500 mr-1"></i> Popup player</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> Stream URL copy</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> VLC compatible</li>
                                <li><i class="fas fa-check text-green-500 mr-1"></i> Mobile & smart speaker</li>
                            </ul>
                        </div>
                    </div>

                    <!-- OAuth Login Card -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 hover:border-pink-500 transition-all hover:shadow-lg hover:shadow-pink-500/20">
                        <div class="text-4xl mb-4 text-center text-pink-500">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-center">OAuth Login</h3>
                        <p class="text-gray-400 text-sm mb-4 text-center">
                            Secure OAuth authentication via Discord, Twitch, Steam, and Battle.net.
                        </p>
                        <a 
                            href="{{ route('login') }}" 
                            class="block w-full px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition-colors text-center">
                            Sign In
                        </a>
                        <div class="mt-4 pt-4 border-t border-gray-700">
                            <p class="text-xs text-gray-500 mb-2"><strong>Providers:</strong></p>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li><i class="fab fa-discord text-blue-400 mr-1"></i> Discord</li>
                                <li><i class="fab fa-twitch text-purple-400 mr-1"></i> Twitch</li>
                                <li><i class="fab fa-steam text-gray-400 mr-1"></i> Steam</li>
                                <li><i class="fab fa-battle-net text-blue-300 mr-1"></i> Battle.net</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Now Playing Widget -->
        @if($nowPlaying)
        <section class="mb-12">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold mb-6 text-center">
                    <i class="fas fa-music mr-2 text-purple-500"></i>
                    Now Playing
                </h2>
                
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        @if($nowPlaying->art)
                        <img src="{{ $nowPlaying->art }}" alt="{{ $nowPlaying->title }}" class="w-32 h-32 rounded-lg shadow-lg">
                        @else
                        <div class="w-32 h-32 bg-gray-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-music text-4xl text-gray-500"></i>
                        </div>
                        @endif
                        
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-2xl font-bold mb-2">{{ $nowPlaying->title }}</h3>
                            <p class="text-xl text-gray-400 mb-3">{{ $nowPlaying->artist }}</p>
                            <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                <span class="px-3 py-1 bg-red-600 text-white text-sm rounded-full">
                                    <i class="fas fa-dot-circle mr-1"></i>
                                    LIVE
                                </span>
                                <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $nowPlaying->listeners }} Listeners
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Content Grid -->
        <section class="mb-12">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent News -->
                    @if($recentNews->isNotEmpty())
                    <div>
                        <h2 class="text-2xl font-bold mb-4">
                            <i class="fas fa-newspaper mr-2 text-yellow-500"></i>
                            Latest News
                        </h2>
                        <div class="space-y-4">
                            @foreach($recentNews as $news)
                            <a href="{{ route('news.show', $news->slug) }}" class="block bg-gray-800 rounded-lg p-4 border border-gray-700 hover:border-yellow-500 transition-all hover:shadow-lg">
                                <h3 class="font-semibold mb-2">{{ $news->title }}</h3>
                                <p class="text-sm text-gray-400">{{ Str::limit($news->excerpt, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $news->published_at->diffForHumans() }}
                                </p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Upcoming Events -->
                    @if($upcomingEvents->isNotEmpty())
                    <div>
                        <h2 class="text-2xl font-bold mb-4">
                            <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                            Upcoming Events
                        </h2>
                        <div class="space-y-4">
                            @foreach($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event->slug) }}" class="block bg-gray-800 rounded-lg p-4 border border-gray-700 hover:border-green-500 transition-all hover:shadow-lg">
                                <h3 class="font-semibold mb-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-400">{{ Str::limit($event->description, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $event->starts_at->format('M j, Y - g:i A') }}
                                </p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Footer CTA -->
        <section class="text-center py-12 bg-gradient-to-r from-blue-900/30 to-purple-900/30 rounded-lg border border-gray-700">
            <h2 class="text-3xl font-bold mb-4">Ready to Join the Community?</h2>
            <p class="text-gray-400 mb-6 max-w-2xl mx-auto">
                Sign in with your favorite gaming platform to unlock more features, request songs, and interact with the community.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </a>
                <button onclick="toggleListenModal()" class="px-8 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-headphones mr-2"></i>
                    Start Listening
                </button>
            </div>
        </section>
    </div>
</x-layouts.app>
