<x-layouts.app :title="$video->title">
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div style="aspect-ratio: 16/9; background: black; position: relative;">
                    @if($video->embed_url)
                        <iframe
                            src="{{ $video->embed_url }}"
                            style="width: 100%; height: 100%; border: none;"
                            allowfullscreen
                            loading="lazy"
                        ></iframe>
                    @else
                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem;">
                            <i class="fas fa-external-link-alt" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                            <p style="color: var(--color-text-muted); text-align: center; margin-bottom: 1rem;">This video cannot be embedded. Click below to watch on the original platform.</p>
                            <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                                <i class="fas fa-play"></i> Watch Video
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 1rem;">
            <div class="card-body">
                <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.75rem;">{{ $video->title }}</h1>
                <div style="display: flex; align-items: center; gap: 1rem; color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <span><i class="fas fa-user"></i> {{ $video->author ?? 'Unknown' }}</span>
                    <span><i class="fas fa-eye"></i> {{ number_format($video->views) }} views</span>
                    <span><i class="fas fa-thumbs-up"></i> {{ number_format($video->upvotes) }}</span>
                    @if($video->posted_at)
                        <span><i class="fas fa-clock"></i> {{ $video->posted_at->diffForHumans() }}</span>
                    @endif
                </div>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <span class="badge badge-primary">{{ $video->category === 'ylyl' ? 'YLYL' : 'Clip' }}</span>
                    <span class="badge badge-gray">{{ ucfirst($video->platform) }}</span>
                </div>
                @if($video->description)
                    <p style="color: var(--color-text-secondary);">{{ $video->description }}</p>
                @endif
                <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem;">
                    <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
                        <i class="fas fa-external-link-alt"></i> Open Original
                    </a>
                </div>
            </div>
        </div>

        @if($relatedVideos->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-video" style="color: var(--color-accent);"></i>
                        Related Videos
                    </h2>
                </div>
                <div class="card-body" style="padding: 0.5rem;">
                    @foreach($relatedVideos as $related)
                        <a href="{{ route('videos.show', $related) }}" class="history-item" style="text-decoration: none; color: inherit;">
                            <div style="width: 100px; height: 60px; background: var(--color-bg-tertiary); border-radius: 4px; flex-shrink: 0; overflow: hidden;">
                                @if($related->thumbnail_url)
                                    <img src="{{ $related->thumbnail_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-video" style="color: var(--color-text-muted);"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="history-info">
                                <p class="history-title">{{ Str::limit($related->title, 45) }}</p>
                                <p class="history-artist">{{ $related->author ?? 'Unknown' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('videos.' . $video->category) }}" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">
            <i class="fas fa-arrow-left"></i> Back to {{ $video->category === 'ylyl' ? 'YLYL' : 'Clips' }}
        </a>
    </div>
</x-layouts.app>
