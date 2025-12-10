<x-layouts.app>
    <x-slot name="title">{{ $subcategory->name }} - {{ $category->name }} - Media Hub</x-slot>

    <!-- Breadcrumb -->
    <div class="mb-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('media.index') }}" class="text-gray-700 dark:text-gray-400 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Media Hub
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('media.category', $category->slug) }}" class="text-gray-700 dark:text-gray-400 hover:text-blue-600">
                            {{ $category->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500 dark:text-gray-500">{{ $subcategory->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Subcategory Header -->
    <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%); padding: 2.5rem 2rem; margin-bottom: 2rem; border-radius: 12px;">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <div style="font-size: 3.5rem; margin-bottom: 1rem;">{{ $subcategory->icon ?? '📁' }}</div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: white; font-weight: 700;">
                {{ $subcategory->name }}
            </h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 1rem;">
                {{ $category->name }}
                @if($subcategory->description)
                    • {{ $subcategory->description }}
                @endif
            </p>
        </div>
    </div>

    <!-- Filters and Sorting -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary);">
            {{ $mediaItems->total() }} {{ Str::plural('Item', $mediaItems->total()) }}
        </h2>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <form method="GET" action="{{ route('media.subcategory', [$category->slug, $subcategory->slug]) }}" class="flex gap-2">
                <select name="sort" 
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Downloaded</option>
                    <option value="top-rated" {{ request('sort') == 'top-rated' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </form>
            <a href="{{ route('media.upload') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload
            </a>
        </div>
    </div>

    <!-- Items Grid -->
    @if($mediaItems->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400 mb-2">No {{ $subcategory->name }} yet</h3>
            <p class="text-gray-500 mb-4">Be the first to share {{ $subcategory->name }} for {{ $category->name }}!</p>
            <a href="{{ route('media.upload') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Content
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($mediaItems as $item)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover-card-lift">
                    @if($item->getFirstMediaUrl('images'))
                        <div style="width: 100%; height: 160px; background-image: url('{{ e($item->getFirstMediaUrl('images')) }}'); background-position: center; background-size: cover; position: relative;">
                            @if($item->is_featured)
                                <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                    <span class="badge badge-warning" style="backdrop-filter: blur(10px);">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div style="width: 100%; height: 160px; background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-download" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        @if($item->version)
                            <span class="badge badge-secondary" style="font-size: 0.75rem; margin-bottom: 0.5rem;">
                                v{{ $item->version }}
                            </span>
                        @endif
                        <h3 class="text-lg font-bold mb-2" style="color: var(--color-text-primary);">
                            <a href="{{ route('media.show', [$category->slug, $subcategory->slug, $item->slug]) }}" style="text-decoration: none; color: inherit;">
                                {{ Str::limit($item->title, 50) }}
                            </a>
                        </h3>
                        <p class="text-sm mb-3" style="color: var(--color-text-secondary);">
                            {{ Str::limit($item->description, 80) }}
                        </p>
                        
                        <!-- User Info -->
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 0.875rem; color: var(--color-text-muted);">
                            <i class="fas fa-user"></i>
                            <span>{{ $item->user->name }}</span>
                        </div>
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.75rem; border-top: 1px solid var(--color-border); font-size: 0.875rem; color: var(--color-text-muted);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-download"></i>
                                <span>{{ $item->downloads_count ?? 0 }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-eye"></i>
                                <span>{{ $item->views_count ?? 0 }}</span>
                            </div>
                            @if($item->rating && $item->ratings_count > 0)
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                                    <span>{{ number_format($item->rating, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($mediaItems->hasPages())
            <div class="mt-8">
                {{ $mediaItems->appends(['sort' => request('sort')])->links() }}
            </div>
        @endif
    @endif
</x-layouts.app>
