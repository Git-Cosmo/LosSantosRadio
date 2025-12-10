<x-layouts.app>
    <x-slot name="title">Downloads - Media Hub</x-slot>

    <!-- Enhanced Hero Section with Search -->
    <div class="media-hero">
        <div class="media-hero-content">
            <div class="media-hero-badge">
                <i class="fas fa-download" style="font-size: 1.5rem; color: white;" aria-hidden="true"></i>
                <span style="color: white; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">Media Downloads</span>
            </div>
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: white; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Downloads Hub
            </h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.125rem; line-height: 1.6; margin-bottom: 0;">
                Browse and download community-created content, mods, and media files
            </p>
            <p style="color: rgba(255,255,255,0.8); font-size: 0.9375rem;">
                Public viewing • Login required for downloads
            </p>

            <!-- Quick Search -->
            <form action="{{ route('media.search') }}" method="GET" style="max-width: 600px; margin: 2rem auto 0; position: relative;">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search for mods, maps, skins, and more..." 
                    style="width: 100%; padding: 1rem 3rem 1rem 1.5rem; border-radius: 50px; border: none; font-size: 1rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(0,0,0,0.2);"
                    aria-label="Search downloads"
                />
                <button type="submit" aria-label="Search" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                    <i class="fas fa-search" style="font-size: 1.25rem; color: var(--color-accent);"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter/Sort Controls -->
    <div class="media-controls" x-data="{ view: 'grid', sort: '{{ request('sort', 'latest') }}' }">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <span style="font-weight: 600; color: var(--color-text-secondary); font-size: 0.875rem;">VIEW:</span>
            <div style="display: flex; gap: 0.5rem;">
                <button 
                    @click="view = 'grid'"
                    :class="view === 'grid' ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-300 text-gray-600'"
                    style="padding: 0.5rem 1rem; border: 2px solid; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;"
                    aria-label="Grid view"
                >
                    <i class="fas fa-th"></i>
                </button>
                <button 
                    @click="view = 'list'"
                    :class="view === 'list' ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-300 text-gray-600'"
                    style="padding: 0.5rem 1rem; border: 2px solid; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;"
                    aria-label="List view"
                >
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center;">
            <label for="sort-select" style="font-weight: 600; color: var(--color-text-secondary); font-size: 0.875rem;">SORT BY:</label>
            <select 
                id="sort-select"
                @change="window.location.href = '?sort=' + $event.target.value"
                style="padding: 0.75rem 1rem; border: 2px solid var(--color-border); border-radius: 8px; background: var(--color-bg-primary); color: var(--color-text-primary); cursor: pointer; font-size: 0.9375rem;"
            >
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Downloads</option>
                <option value="top-rated" {{ request('sort') === 'top-rated' ? 'selected' : '' }}>Top Rated</option>
                <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
            </select>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div style="background: var(--color-bg-secondary); padding: 1.5rem; border-radius: 12px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: var(--color-accent); margin-bottom: 0.5rem;">
                {{ $categories->sum('media_items_count') }}
            </div>
            <div style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                Total Downloads
            </div>
        </div>
        <div style="background: var(--color-bg-secondary); padding: 1.5rem; border-radius: 12px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #10b981; margin-bottom: 0.5rem;">
                {{ $categories->count() }}
            </div>
            <div style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                Categories
            </div>
        </div>
        <div style="background: var(--color-bg-secondary); padding: 1.5rem; border-radius: 12px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #f59e0b; margin-bottom: 0.5rem;">
                {{ $featured->count() }}
            </div>
            <div style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                Featured
            </div>
        </div>
        <div style="background: var(--color-bg-secondary); padding: 1.5rem; border-radius: 12px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 700; color: #ec4899; margin-bottom: 0.5rem;">
                24/7
            </div>
            <div style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                Available
            </div>
        </div>
    </div>
    
    <!-- Categories Grid -->
    <section class="mb-12">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.875rem; font-weight: 700; color: var(--color-text-primary); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-folder-open" style="color: var(--color-accent);" aria-hidden="true"></i>
                Browse by Game
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('media.category', $category->slug) }}" 
               aria-label="{{ $category->name }} category"
               class="download-card">
                <div style="padding: 2rem; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;" aria-hidden="true">{{ $category->icon }}</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--color-text-primary);">
                        {{ $category->name }}
                    </h3>
                    <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 1rem;">
                        {{ $category->description }}
                    </p>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: var(--color-bg-tertiary); border-radius: 20px; font-size: 0.875rem; color: var(--color-text-secondary);">
                        <i class="fas fa-download" style="color: var(--color-accent);"></i>
                        <span style="font-weight: 600;">{{ $category->media_items_count }}</span>
                        {{ Str::plural('item', $category->media_items_count) }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    @if($featured->isNotEmpty())
    <section class="featured-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-star section-icon" style="color: #fbbf24;" aria-hidden="true"></i>
                Featured Content
            </h2>
            <a href="{{ route('media.category', $featured->first()->category->slug) }}?sort=featured" style="color: var(--color-accent); font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $item)
            <div class="download-card">
                @if($item->getFirstMediaUrl('images'))
                <div class="download-card-image" style="background-image: url('{{ e($item->getFirstMediaUrl('images')) }}');">
                    <div class="download-card-badge badge-featured">
                        <i class="fas fa-star" aria-hidden="true"></i> Featured
                    </div>
                </div>
                @else
                <div class="download-card-image-placeholder">
                    <i class="fas fa-file-download" style="font-size: 3rem; color: rgba(255,255,255,0.3);" aria-hidden="true"></i>
                </div>
                @endif
                <div class="download-card-content">
                    <div class="download-card-category">
                        {{ $item->category->name }} › {{ $item->subcategory->name }}
                    </div>
                    <h3 class="download-card-title">{{ $item->title }}</h3>
                    <p class="download-card-description">{{ Str::limit($item->description, 100) }}</p>
                    <div class="download-card-meta">
                        <div class="download-card-stat">
                            <i class="fas fa-download"></i>
                            <span>{{ number_format($item->downloads_count ?? 0) }}</span>
                        </div>
                        <div class="download-card-stat">
                            <i class="fas fa-eye"></i>
                            <span>{{ number_format($item->views_count ?? 0) }}</span>
                        </div>
                        <div class="download-card-stat">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <span>{{ number_format($item->rating ?? 0, 1) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}" 
                       class="btn btn-primary" style="width: 100%; margin-top: 1rem; display: flex; align-items: center; justify-center; gap: 0.5rem; font-weight: 600; text-decoration: none;">
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
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-fire section-icon" style="color: #ef4444;" aria-hidden="true"></i>
                Popular Downloads
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($popular as $item)
            <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}"
               class="download-card"
               style="text-decoration: none; padding: 1.5rem;">
                <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: var(--color-text-primary); font-size: 1rem;">
                    {{ Str::limit($item->title, 45) }}
                </h3>
                <p style="font-size: 0.75rem; margin-bottom: 1rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ $item->category->name }}
                </p>
                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--color-text-muted);">
                    <span style="display: flex; align-items: center; gap: 0.375rem;">
                        <i class="fas fa-download" style="color: var(--color-accent);"></i>
                        {{ number_format($item->downloads_count ?? 0) }}
                    </span>
                    <span style="display: flex; align-items: center; gap: 0.375rem;">
                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                        {{ number_format($item->rating ?? 0, 1) }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($recent->isNotEmpty())
    <section>
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-clock section-icon" style="color: var(--color-accent);" aria-hidden="true"></i>
                Recently Added
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($recent as $item)
            <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}"
               class="download-card"
               style="text-decoration: none; padding: 1.5rem;">
                <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: var(--color-text-primary); font-size: 0.9375rem;">
                    {{ Str::limit($item->title, 40) }}
                </h3>
                <p style="font-size: 0.75rem; margin-bottom: 0.75rem; color: var(--color-text-muted);">
                    {{ $item->subcategory->name }}
                </p>
                <div style="font-size: 0.75rem; color: var(--color-text-muted); display: flex; align-items: center; gap: 0.375rem;">
                    <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                    {{ $item->published_at?->diffForHumans() ?? $item->created_at->diffForHumans() }}
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Call to Action -->
    @auth
    <div style="margin-top: 3rem; padding: 3rem 2rem; background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 100%); border-radius: 12px; text-align: center;">
        <h3 style="font-size: 1.875rem; font-weight: 700; color: white; margin-bottom: 1rem;">
            Share Your Creations
        </h3>
        <p style="color: rgba(255,255,255,0.9); font-size: 1.125rem; margin-bottom: 2rem;">
            Upload your mods, maps, and content to share with the community
        </p>
        <a href="{{ route('media.upload') }}" 
           style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: white; color: var(--color-accent); border-radius: 8px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.3)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'">
            <i class="fas fa-cloud-upload-alt"></i>
            Upload Content
        </a>
    </div>
    @else
    <div style="margin-top: 3rem; padding: 3rem 2rem; background: var(--color-bg-secondary); border: 2px solid var(--color-border); border-radius: 12px; text-align: center;">
        <h3 style="font-size: 1.875rem; font-weight: 700; color: var(--color-text-primary); margin-bottom: 1rem;">
            Join Our Community
        </h3>
        <p style="color: var(--color-text-secondary); font-size: 1.125rem; margin-bottom: 2rem;">
            Login to download content and upload your own creations
        </p>
        <a href="{{ route('login') }}" 
           style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 2rem; background: var(--color-accent); color: white; border-radius: 8px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3); transition: all 0.3s ease;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(99, 102, 241, 0.4)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(99, 102, 241, 0.3)'">
            <i class="fas fa-sign-in-alt"></i>
            Login Now
        </a>
    </div>
    @endauth
</x-layouts.app>
