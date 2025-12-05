<x-layouts.app :title="'YLYL - You Laugh You Lose'">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-laugh-squint" style="color: var(--color-accent);"></i>
                YLYL - You Laugh You Lose
            </h1>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">
                The best funny videos from across the internet. Can you watch without laughing?
            </p>

            @if($videos->count() > 0)
                <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                    @foreach($videos as $video)
                        <a href="{{ route('videos.show', $video) }}" class="video-card card" style="text-decoration: none; color: inherit;">
                            <div style="position: relative; height: 180px; background: var(--color-bg-tertiary);">
                                @if($video->thumbnail_url)
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-video" style="font-size: 3rem; color: var(--color-text-muted);"></i>
                                    </div>
                                @endif
                                <div class="video-overlay" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.2s;">
                                    <i class="fas fa-play-circle" style="font-size: 4rem; color: white;"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem; line-height: 1.3;">{{ Str::limit($video->title, 70) }}</h3>
                                <div style="display: flex; justify-content: space-between; align-items: center; color: var(--color-text-muted); font-size: 0.8125rem;">
                                    <span><i class="fas fa-user"></i> {{ $video->author ?? 'Unknown' }}</span>
                                    <span><i class="fas fa-eye"></i> {{ number_format($video->views) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div style="margin-top: 2rem;">
                    {{ $videos->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-laugh-squint" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No Videos Yet</h3>
                    <p style="color: var(--color-text-muted);">Check back later for hilarious videos!</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .video-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .video-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }
        .video-card:hover .video-overlay {
            opacity: 1 !important;
        }
    </style>
</x-layouts.app>
