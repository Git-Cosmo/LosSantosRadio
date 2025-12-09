@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">My Favorites</h1>
    
    @if($favorites->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <div class="text-6xl mb-4">❤️</div>
            <h2 class="text-2xl font-bold mb-2">No Favorites Yet</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Start exploring and favorite mods, maps, and other content!</p>
            <a href="{{ route('media.index') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                Browse Media Hub
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favorites as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                @if($item->hasMedia('images'))
                    <img src="{{ $item->getFirstMediaUrl('images') }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-6xl">{{ $item->category->icon ?? '📦' }}</span>
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        {{ $item->category->name }} › {{ $item->subcategory->name }}
                    </div>
                    
                    <h3 class="text-xl font-bold mb-2">{{ $item->title }}</h3>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ Str::limit($item->description, 100) }}
                    </p>
                    
                    <div class="flex items-center justify-between text-sm mb-4">
                        <span class="flex items-center">
                            ⭐ {{ number_format($item->rating, 1) }} 
                            <span class="ml-1 text-gray-500">({{ $item->ratings_count }})</span>
                        </span>
                        <span class="flex items-center">
                            ⬇️ {{ number_format($item->downloads_count) }}
                        </span>
                        <span class="flex items-center">
                            ❤️ {{ $item->favorites_count }}
                        </span>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}" 
                           class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            View Details
                        </a>
                        
                        <form action="{{ route('media.favorite.toggle', [$item->category->slug, $item->subcategory->slug, $item]) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" title="Remove from favorites">
                                💔
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection
