<x-layouts.app :title="$game->title">
    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-purple-900 to-gray-900 py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex text-sm text-gray-400">
                    <a href="{{ route('games.index') }}" class="hover:text-purple-400">Games</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-300">{{ $game->title }}</span>
                </nav>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Game Header -->
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 mb-8">
                        <div class="flex flex-col md:flex-row gap-6">
                            @if($game->cover_image)
                            <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full md:w-64 rounded-lg shadow-lg">
                            @endif
                            <div class="flex-1">
                                <h1 class="text-4xl font-bold text-white mb-4">{{ $game->title }}</h1>
                                
                                @if($game->rating)
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl">⭐</span>
                                        <span class="text-xl text-white font-bold">{{ number_format($game->rating, 1) }}</span>
                                        @if($game->rating_count)
                                        <span class="text-sm text-gray-400">({{ number_format($game->rating_count) }} ratings)</span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($game->release_date)
                                <p class="text-gray-400 mb-2">
                                    <span class="font-semibold">Release Date:</span> {{ $game->release_date->format('F d, Y') }}
                                </p>
                                @endif

                                @if($game->genres)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($game->genres as $genre)
                                    <span class="px-3 py-1 bg-purple-900/50 text-purple-300 text-sm rounded-full">
                                        {{ is_array($genre) ? $genre['name'] : $genre }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif

                                @if($game->platforms)
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($game->platforms, 0, 5) as $platform)
                                    <span class="px-3 py-1 bg-gray-700 text-gray-300 text-sm rounded">
                                        {{ is_array($platform) ? $platform['name'] : $platform }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($game->description)
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-white mb-4">About</h2>
                        <p class="text-gray-300 leading-relaxed">{{ $game->description }}</p>
                    </div>
                    @endif

                    <!-- Storyline -->
                    @if($game->storyline)
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-white mb-4">Storyline</h2>
                        <p class="text-gray-300 leading-relaxed">{{ $game->storyline }}</p>
                    </div>
                    @endif

                    <!-- Screenshots -->
                    @if($game->screenshots && count($game->screenshots) > 0)
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-white mb-4">Screenshots</h2>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($game->screenshots as $screenshot)
                            <img src="{{ $screenshot }}" alt="Screenshot" class="w-full rounded-lg">
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Active Deals -->
                    @if($game->deals->count() > 0)
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-white mb-4">💰 Available Deals</h3>
                        <div class="space-y-4">
                            @foreach($game->deals as $deal)
                            <a href="{{ $deal->deal_url }}" target="_blank" class="block p-4 bg-gray-900 rounded-lg hover:bg-gray-700 transition">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-400">{{ $deal->store->name ?? 'Unknown' }}</span>
                                    <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">
                                        -{{ $deal->savings_percent }}%
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xl text-green-400 font-bold">${{ number_format($deal->sale_price, 2) }}</span>
                                    <span class="text-gray-500 line-through text-sm">${{ number_format($deal->normal_price, 2) }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- External Links -->
                    @if($game->igdb_url || ($game->websites && count($game->websites) > 0))
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-white mb-4">🔗 Links</h3>
                        <div class="space-y-2">
                            @if($game->igdb_url)
                            <a href="{{ $game->igdb_url }}" target="_blank" class="block px-4 py-2 bg-purple-900/50 hover:bg-purple-800/50 text-purple-300 rounded transition">
                                IGDB Page →
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Related Games -->
                    @if($relatedGames->count() > 0)
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-white mb-4">Similar Games</h3>
                        <div class="space-y-4">
                            @foreach($relatedGames as $related)
                            <a href="{{ route('games.show', $related) }}" class="flex gap-3 hover:bg-gray-700 p-2 rounded transition">
                                @if($related->cover_image)
                                <img src="{{ $related->cover_image }}" alt="{{ $related->title }}" class="w-16 h-20 object-cover rounded">
                                @endif
                                <div>
                                    <h4 class="text-white font-semibold text-sm line-clamp-2">{{ $related->title }}</h4>
                                    @if($related->rating)
                                    <span class="text-xs text-gray-400">⭐ {{ number_format($related->rating, 1) }}</span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
