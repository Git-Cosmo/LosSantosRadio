<x-layouts.app title="Games">
    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-purple-900 to-gray-900 py-12">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-white mb-4 bg-gradient-to-r from-purple-400 to-pink-600 bg-clip-text text-transparent">
                    🎮 Games Hub
                </h1>
                <p class="text-gray-300 text-lg">
                    Discover amazing games, hot deals, and free offerings
                </p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-12">
                <form method="GET" action="{{ route('games.index') }}" class="flex gap-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search games..." 
                        class="flex-1 px-6 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-purple-500"
                    >
                    <button type="submit" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                        Search
                    </button>
                </form>
            </div>

            <!-- Top Deals Section -->
            @if($topDeals->count() > 0)
            <div class="mb-16">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-white">🔥 Hot Deals</h2>
                    <a href="{{ route('games.deals') }}" class="text-purple-400 hover:text-purple-300 font-semibold">
                        View All →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($topDeals as $deal)
                    <a href="{{ route('games.deals.show', $deal) }}" class="block group">
                        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden hover:border-purple-500 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50">
                            @if($deal->thumb)
                            <img src="{{ $deal->thumb }}" alt="{{ $deal->title }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-3 py-1 bg-red-600 text-white text-sm font-bold rounded-full">
                                        -{{ $deal->savings_percent }}%
                                    </span>
                                    @if($deal->store)
                                    <span class="text-xs text-gray-400">{{ $deal->store->name }}</span>
                                    @endif
                                </div>
                                <h3 class="text-white font-semibold mb-2 line-clamp-2 group-hover:text-purple-400 transition">
                                    {{ $deal->title }}
                                </h3>
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl text-green-400 font-bold">${{ number_format($deal->sale_price, 2) }}</span>
                                    <span class="text-gray-500 line-through">${{ number_format($deal->normal_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Free Games Section -->
            @if($freeGames->count() > 0)
            <div class="mb-16">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-white">🎁 Free Games</h2>
                    <a href="{{ route('games.free') }}" class="text-purple-400 hover:text-purple-300 font-semibold">
                        View All →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($freeGames as $game)
                    <a href="{{ route('games.free.show', $game) }}" class="block group">
                        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden hover:border-green-500 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-green-500/50">
                            @if($game->image_url)
                            <img src="{{ $game->image_url }}" alt="{{ $game->title }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-3 py-1 bg-green-600 text-white text-sm font-bold rounded-full">
                                        FREE
                                    </span>
                                    @if($game->store)
                                    <span class="text-xs text-gray-400">{{ $game->store }}</span>
                                    @endif
                                </div>
                                <h3 class="text-white font-semibold mb-2 line-clamp-2 group-hover:text-green-400 transition">
                                    {{ $game->title }}
                                </h3>
                                @if($game->expires_at)
                                <p class="text-sm text-gray-400">
                                    Expires: {{ $game->expires_at->diffForHumans() }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- All Games Section -->
            @if($games->count() > 0)
            <div>
                <h2 class="text-3xl font-bold text-white mb-6">🎯 All Games</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($games as $game)
                    <a href="{{ route('games.show', $game) }}" class="block group">
                        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden hover:border-purple-500 transition-all duration-300 transform hover:scale-105">
                            @if($game->cover_image)
                            <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full h-64 object-cover">
                            @else
                            <div class="w-full h-64 bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                                <span class="text-6xl">🎮</span>
                            </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-white font-semibold mb-2 line-clamp-2 group-hover:text-purple-400 transition">
                                    {{ $game->title }}
                                </h3>
                                @if($game->rating)
                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <span>⭐ {{ number_format($game->rating, 1) }}</span>
                                </div>
                                @endif
                                @if($game->deals->count() > 0)
                                <div class="mt-2">
                                    <span class="text-xs text-green-400">{{ $game->deals->count() }} deal(s) available</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $games->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-400 text-lg">No games found. Check back later!</p>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
