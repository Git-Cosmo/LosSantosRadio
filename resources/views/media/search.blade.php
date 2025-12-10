<x-layouts.app>
    <x-slot name="title">Search Results - Media Hub</x-slot>

    <!-- Search Header -->
    <div style="background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 50%, #ec4899 100%); padding: 2rem; margin-bottom: 2rem; border-radius: 12px;">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <h1 style="font-size: 2rem; margin-bottom: 1rem; color: white; font-weight: 700;">
                <i class="fas fa-search"></i> Search Results
            </h1>
            <form method="GET" action="{{ route('media.search') }}" class="flex gap-2">
                <input type="text" 
                       name="q" 
                       value="{{ $query }}" 
                       placeholder="Search for mods, maps, and more..."
                       class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-blue-500"
                       autofocus>
                <select name="category" 
                        class="px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary px-6">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Results -->
    <div>
        @if($query)
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary);">
                    @if($results->total() > 0)
                        Found {{ $results->total() }} {{ Str::plural('result', $results->total()) }} for "{{ $query }}"
                    @else
                        No results found for "{{ $query }}"
                    @endif
                </h2>
            </div>
        @endif

        @if($results->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-search text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400 mb-2">No results found</h3>
                <p class="text-gray-500 mb-4">Try different keywords or browse our categories</p>
                <a href="{{ route('media.index') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Media Hub
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($results as $item)
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
                                {{ $item->category->name }} › {{ $item->subcategory->name }}
                            </span>
                            <h3 class="text-lg font-bold mb-2" style="color: var(--color-text-primary);">
                                <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}" style="text-decoration: none; color: inherit;">
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
            @if($results->hasPages())
                <div class="mt-8">
                    {{ $results->appends(['q' => $query, 'category' => request('category')])->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
