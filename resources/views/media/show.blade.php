<x-layouts.app>
    <x-slot name="title">{{ $mediaItem->title }} - {{ $mediaItem->category->name }} - Media Hub</x-slot>

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
                        <a href="{{ route('media.category', $mediaItem->category->slug) }}" class="text-gray-700 dark:text-gray-400 hover:text-blue-600">
                            {{ $mediaItem->category->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('media.subcategory', [$mediaItem->category->slug, $mediaItem->subcategory->slug]) }}" class="text-gray-700 dark:text-gray-400 hover:text-blue-600">
                            {{ $mediaItem->subcategory->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500 dark:text-gray-500">{{ Str::limit($mediaItem->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-6">
                @if($mediaItem->getFirstMediaUrl('images'))
                    <div style="width: 100%; height: 300px; background-image: url('{{ e($mediaItem->getFirstMediaUrl('images')) }}'); background-position: center; background-size: cover;"></div>
                @else
                    <div style="width: 100%; height: 300px; background: linear-gradient(135deg, var(--color-accent) 0%, #a855f7 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-download" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            @if($mediaItem->is_featured)
                                <span class="badge badge-warning mb-2">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif
                            <h1 class="text-3xl font-bold mb-2" style="color: var(--color-text-primary);">
                                {{ $mediaItem->title }}
                            </h1>
                            <div class="flex items-center gap-4 text-sm" style="color: var(--color-text-muted);">
                                <span>
                                    <i class="fas fa-user"></i> {{ $mediaItem->user->name }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar"></i> {{ $mediaItem->published_at->diffForHumans() }}
                                </span>
                                @if($mediaItem->version)
                                    <span class="badge badge-secondary">v{{ $mediaItem->version }}</span>
                                @endif
                            </div>
                        </div>
                        
                        @auth
                            <button onclick="toggleFavorite()" 
                                    id="favorite-btn"
                                    class="text-3xl {{ $mediaItem->isFavoritedBy(auth()->user()) ? 'text-red-500' : 'text-gray-400' }} hover:text-red-500 transition-colors">
                                <i class="fa{{ $mediaItem->isFavoritedBy(auth()->user()) ? 's' : 'r' }} fa-heart" id="favorite-icon"></i>
                            </button>
                        @endauth
                    </div>
                    
                    <!-- Stats Bar -->
                    <div class="flex items-center gap-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-download text-blue-600"></i>
                            <div>
                                <div class="text-2xl font-bold">{{ $mediaItem->downloads_count ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Downloads</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-eye text-purple-600"></i>
                            <div>
                                <div class="text-2xl font-bold">{{ $mediaItem->views_count ?? 0 }}</div>
                                <div class="text-xs text-gray-500">Views</div>
                            </div>
                        </div>
                        @if($mediaItem->rating && $mediaItem->ratings_count > 0)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-star text-yellow-500"></i>
                                <div>
                                    <div class="text-2xl font-bold">{{ number_format($mediaItem->rating, 1) }}</div>
                                    <div class="text-xs text-gray-500">Rating ({{ $mediaItem->ratings_count }})</div>
                                </div>
                            </div>
                        @endif
                        @if($mediaItem->file_size)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-hdd text-green-600"></i>
                                <div>
                                    <div class="text-2xl font-bold">{{ $mediaItem->file_size }}</div>
                                    <div class="text-xs text-gray-500">File Size</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold mb-3" style="color: var(--color-text-primary);">
                            <i class="fas fa-info-circle" style="color: var(--color-accent);"></i> Description
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $mediaItem->description }}</p>
                    </div>
                    
                    <!-- Installation Instructions -->
                    @if($mediaItem->content)
                        <div class="mb-6">
                            <h2 class="text-xl font-bold mb-3" style="color: var(--color-text-primary);">
                                <i class="fas fa-wrench" style="color: var(--color-accent);"></i> Installation Instructions
                            </h2>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <pre class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $mediaItem->content }}</pre>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Rating Section -->
                    @auth
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h2 class="text-xl font-bold mb-3" style="color: var(--color-text-primary);">
                                <i class="fas fa-star" style="color: var(--color-accent);"></i> Rate This Content
                            </h2>
                            @if($mediaItem->isRatedBy(auth()->user()))
                                <p class="text-green-600 dark:text-green-400 mb-3">
                                    <i class="fas fa-check-circle"></i> You rated this {{ $mediaItem->getUserRating(auth()->user())->rating }} stars
                                </p>
                            @endif
                            <form action="{{ route('media.rate', [$mediaItem->category->slug, $mediaItem->subcategory->slug, $mediaItem]) }}" method="POST" id="rating-form">
                                @csrf
                                <div class="flex gap-2 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" 
                                                onclick="setRating({{ $i }})"
                                                aria-label="Rate {{ $i }} out of 5 stars"
                                                class="rating-star text-4xl text-gray-300 hover:text-yellow-500 transition-colors"
                                                data-rating="{{ $i }}">
                                            <i class="far fa-star"></i>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-input" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit Rating
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to rate this content
                            </p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Download Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold mb-4" style="color: var(--color-text-primary);">
                    <i class="fas fa-download" style="color: var(--color-accent);"></i> Download
                </h3>
                @auth
                    <a href="{{ route('media.download', [$mediaItem->category->slug, $mediaItem->subcategory->slug, $mediaItem->slug]) }}" 
                       class="btn btn-primary w-full mb-3 flex items-center justify-center gap-2 text-lg py-3">
                        <i class="fas fa-download"></i>
                        Download Now
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary w-full mb-3 flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i>
                        Login to Download
                    </a>
                @endauth
                
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>Safe & Verified</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-blue-600"></i>
                        <span>Community Approved</span>
                    </div>
                    @if($mediaItem->file_size)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-hdd text-purple-600"></i>
                            <span>Size: {{ $mediaItem->file_size }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Category Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-3" style="color: var(--color-text-primary);">
                    <i class="fas fa-folder" style="color: var(--color-accent);"></i> Category
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('media.category', $mediaItem->category->slug) }}" 
                       class="flex items-center gap-2 text-blue-600 hover:underline">
                        <span class="text-2xl">{{ $mediaItem->category->icon }}</span>
                        <span>{{ $mediaItem->category->name }}</span>
                    </a>
                    <a href="{{ route('media.subcategory', [$mediaItem->category->slug, $mediaItem->subcategory->slug]) }}" 
                       class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 text-sm">
                        <i class="fas fa-chevron-right"></i>
                        <span>{{ $mediaItem->subcategory->name }}</span>
                    </a>
                </div>
            </div>
            
            <!-- Author Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-3" style="color: var(--color-text-primary);">
                    <i class="fas fa-user" style="color: var(--color-accent);"></i> Author
                </h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($mediaItem->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold" style="color: var(--color-text-primary);">{{ $mediaItem->user->name }}</div>
                        <div class="text-sm text-gray-500">Content Creator</div>
                    </div>
                </div>
            </div>
            
            <!-- Related Items -->
            @if($related->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-4" style="color: var(--color-text-primary);">
                        <i class="fas fa-th" style="color: var(--color-accent);"></i> Related Content
                    </h3>
                    <div class="space-y-3">
                        @foreach($related as $item)
                            <a href="{{ route('media.show', [$item->category->slug, $item->subcategory->slug, $item->slug]) }}" 
                               class="flex gap-3 hover:bg-gray-50 dark:hover:bg-gray-900 p-2 rounded-lg transition-colors"
                               style="text-decoration: none;">
                                @if($item->getFirstMediaUrl('images'))
                                    <img src="{{ $item->getFirstMediaUrl('images') }}" 
                                         alt="{{ $item->title }}" 
                                         class="w-20 h-20 rounded-lg object-cover">
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <i class="fas fa-file text-white text-2xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm mb-1" style="color: var(--color-text-primary);">
                                        {{ Str::limit($item->title, 40) }}
                                    </h4>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-download"></i> {{ $item->downloads_count ?? 0 }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Rating system
        function setRating(rating) {
            document.getElementById('rating-input').value = rating;
            
            // Update stars
            document.querySelectorAll('.rating-star').forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    star.classList.add('text-yellow-500');
                    star.classList.remove('text-gray-300');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // Favorite system
        @auth
        async function toggleFavorite() {
            const favoriteUrl = @js(route('media.favorite.toggle', [$mediaItem->category->slug, $mediaItem->subcategory->slug, $mediaItem]));
            try {
                const response = await fetch(favoriteUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const btn = document.getElementById('favorite-btn');
                    const icon = document.getElementById('favorite-icon');
                    
                    if (data.favorited) {
                        btn.classList.add('text-red-500');
                        btn.classList.remove('text-gray-400');
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        btn.classList.remove('text-red-500');
                        btn.classList.add('text-gray-400');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
                alert('Failed to update favorite. Please try again.');
            }
        }
        @endauth
    </script>
    @endpush
</x-layouts.app>
