<x-layouts.app>
    <x-slot name="title">Downloads - Media Hub</x-slot>

    <!-- Enhanced Hero Section -->
    <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%); padding: 3rem 2rem; margin-bottom: 2rem; border-radius: 12px; position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; opacity: 0.1; background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
        
        <div style="position: relative; z-index: 1; text-align: center; max-width: 800px; margin: 0 auto;">
            <div style="display: inline-flex; align-items: center; gap: 1rem; margin-bottom: 1rem; padding: 0.5rem 1.5rem; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px); border-radius: 50px;">
                <i class="fas fa-download" style="font-size: 1.5rem; color: white;"></i>
                <span style="color: white; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">Media Downloads</span>
            </div>
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: white; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Downloads Hub
            </h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.125rem; line-height: 1.6;">
                Browse and download community-created content, mods, and media files. <br>Public viewing - Login required for downloads.
            </p>
        </div>
    </div>
    
    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @foreach($categories as $category)
        <a href="{{ route('media.category', $category->slug) }}" 
           class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl hover-card">
            <div class="text-5xl mb-4 text-center">{{ $category->icon }}</div>
            <h3 class="text-xl font-bold mb-2 text-center" style="color: var(--color-text-primary);">{{ $category->name }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm text-center">
                <i class="fas fa-file-download" style="margin-right: 0.25rem; color: var(--color-accent);"></i>
                {{ $category->media_items_count }} {{ Str::plural('item', $category->media_items_count) }}
            </p>
        </a>
        @endforeach
    </div>

    @if($featured->isNotEmpty())
    <section class="mb-12">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--color-text-primary); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-star" style="color: #fbbf24;"></i>
                Featured Content
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover-card-lift">
                @if($item->getFirstMediaUrl('images'))
                <div style="width: 100%; height: 160px; background: url('{{ $item->getFirstMediaUrl('images') }}') center/cover; position: relative;">
                    <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                        <span class="badge badge-warning" style="backdrop-filter: blur(10px); font-weight: 600;">
                            <i class="fas fa-star"></i> Featured
                        </span>
                    </div>
                </div>
                @else
                <div style="width: 100%; height: 160px; background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-download" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                </div>
                @endif
                <div class="p-6">
                    <span class="badge badge-primary" style="font-size: 0.75rem; margin-bottom: 0.75rem;">
                        {{ $item->category->name }} › {{ $item->subcategory->name }}
                    </span>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-text-primary);">{{ $item->title }}</h3>
                    <p class="text-sm mb-4" style="color: var(--color-text-secondary);">{{ Str::limit($item->description, 100) }}</p>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-border);">
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                            <i class="fas fa-download"></i>
                            <span>{{ $item->downloads_count ?? 0 }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                            <i class="fas fa-eye"></i>
                            <span>{{ $item->views_count ?? 0 }}</span>
                        </div>
                    </div>
                    <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}" 
                       class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 600;">
                        View Details
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @if($popular->isNotEmpty())
    <section class="mb-12">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--color-text-primary); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-fire" style="color: #ef4444;"></i>
                Popular Downloads
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($popular as $item)
            <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}"
               class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 hover-card-lift"
               style="text-decoration: none;">
                <h3 class="font-bold mb-2" style="color: var(--color-text-primary); font-size: 0.9375rem;">{{ Str::limit($item->title, 40) }}</h3>
                <p class="text-xs mb-3" style="color: var(--color-text-muted);">{{ $item->category->name }}</p>
                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.75rem; color: var(--color-text-muted);">
                    <span><i class="fas fa-download" style="color: var(--color-accent);"></i> {{ $item->downloads_count ?? 0 }}</span>
                    <span><i class="fas fa-star" style="color: #fbbf24;"></i> {{ number_format($item->average_rating ?? 0, 1) }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($recent->isNotEmpty())
    <section>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--color-text-primary); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-clock" style="color: var(--color-accent);"></i>
                Recently Added
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($recent as $item)
            <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}"
               class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 hover-card-lift"
               style="text-decoration: none;">
                <h3 class="font-bold mb-2" style="color: var(--color-text-primary); font-size: 0.9375rem;">{{ Str::limit($item->title, 40) }}</h3>
                <p class="text-xs mb-3" style="color: var(--color-text-muted);">{{ $item->subcategory->name }}</p>
                <div style="font-size: 0.75rem; color: var(--color-text-muted);">
                    <i class="fas fa-calendar-alt" style="color: var(--color-accent); margin-right: 0.25rem;"></i>
                    {{ $item->created_at->diffForHumans() }}
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</x-layouts.app>
