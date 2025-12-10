<x-layouts.app>
    <x-slot name="title">{{ $category->name }} - Media Hub</x-slot>

    <!-- Category Header -->
    <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%); padding: 3rem 2rem; margin-bottom: 2rem; border-radius: 12px; position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; opacity: 0.1; background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
        
        <div style="position: relative; z-index: 1; text-align: center; max-width: 800px; margin: 0 auto;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">{{ $category->icon }}</div>
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: white; font-weight: 700;">
                {{ $category->name }}
            </h1>
            @if($category->description)
                <p style="color: rgba(255,255,255,0.9); font-size: 1.125rem; line-height: 1.6;">
                    {{ $category->description }}
                </p>
            @endif
        </div>
    </div>

    <!-- Subcategories -->
    @if($category->subcategories->isNotEmpty())
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4" style="color: var(--color-text-primary);">
            <i class="fas fa-layer-group" style="color: var(--color-accent);"></i> Browse by Type
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($category->subcategories as $subcategory)
                <a href="{{ route('media.subcategory', [$category->slug, $subcategory->slug]) }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 hover-card text-center"
                   style="text-decoration: none;">
                    <div class="text-3xl mb-2">{{ $subcategory->icon ?? '📁' }}</div>
                    <h3 class="font-bold text-sm" style="color: var(--color-text-primary);">{{ $subcategory->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $subcategory->media_items_count ?? 0 }} items
                    </p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Items Grid -->
    <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--color-text-primary);">
                All {{ $category->name }} Content
            </h2>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="{{ route('media.upload') }}" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload
                </a>
            </div>
        </div>

        @if($mediaItems->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400 mb-2">No content yet</h3>
                <p class="text-gray-500 mb-4">Be the first to share content for {{ $category->name }}!</p>
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
                            <span class="badge badge-primary" style="font-size: 0.75rem; margin-bottom: 0.75rem;">
                                {{ $item->subcategory->name }}
                            </span>
                            <h3 class="text-lg font-bold mb-2" style="color: var(--color-text-primary);">
                                <a href="{{ route('media.show', [$category->slug, $item->subcategory->slug, $item->slug]) }}" style="text-decoration: none; color: inherit;">
                                    {{ Str::limit($item->title, 50) }}
                                </a>
                            </h3>
                            <p class="text-sm mb-3" style="color: var(--color-text-secondary);">
                                {{ Str::limit($item->description, 80) }}
                            </p>
                            
                            <div style="display: flex; align-items: center; justify-between; padding-top: 0.75rem; border-top: 1px solid var(--color-border); font-size: 0.875rem; color: var(--color-text-muted);">
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
                    {{ $mediaItems->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
