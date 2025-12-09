@extends('layouts.app')

@section('title', 'Media Hub')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Media Hub</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @foreach($categories as $category)
        <a href="{{ route('media.category', $category->slug) }}" 
           class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="text-4xl mb-4">{{ $category->icon }}</div>
            <h3 class="text-xl font-bold mb-2">{{ $category->name }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $category->media_items_count }} items</p>
        </a>
        @endforeach
    </div>

    @if($featured->isNotEmpty())
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Featured Content</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold mb-2">{{ $item->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $item->category->name }} › {{ $item->subcategory->name }}</p>
                <p class="text-sm mb-4">{{ Str::limit($item->description, 100) }}</p>
                <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}" 
                   class="text-blue-600 hover:text-blue-800">View Details →</a>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
