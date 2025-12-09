<x-layouts.app :title="'Streamer Clips'">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-tv" style="color: var(--color-accent);"></i>
                Streamer Clips
            </h1>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('videos.clips') }}" class="btn {{ !$platform ? 'btn-primary' : 'btn-secondary' }}">All</a>
                <a href="{{ route('videos.clips', ['platform' => 'twitch']) }}" class="btn {{ $platform === 'twitch' ? 'btn-primary' : 'btn-secondary' }}">
                    <i class="fab fa-twitch"></i> Twitch
                </a>
                <a href="{{ route('videos.clips', ['platform' => 'youtube']) }}" class="btn {{ $platform === 'youtube' ? 'btn-primary' : 'btn-secondary' }}">
                    <i class="fab fa-youtube"></i> YouTube
                </a>
                <a href="{{ route('videos.clips', ['platform' => 'kick']) }}" class="btn {{ $platform === 'kick' ? 'btn-primary' : 'btn-secondary' }}">
                    <i class="fas fa-play-circle"></i> Kick
                </a>
            </div>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">
                The best moments from Twitch, YouTube, and Kick streamers.
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
                                <div style="position: absolute; top: 0.5rem; left: 0.5rem;">
                                    @if($video->platform === 'twitch')
                                        <span class="badge" style="background: #9146FF; color: white;">
                                            <i class="fab fa-twitch"></i> Twitch
                                        </span>
                                    @elseif($video->platform === 'youtube')
                                        <span class="badge" style="background: #FF0000; color: white;">
                                            <i class="fab fa-youtube"></i> YouTube
                                        </span>
                                    @else
                                        <span class="badge badge-primary">
                                            <i class="fas fa-play-circle"></i> {{ ucfirst($video->platform) }}
                                        </span>
                                    @endif
                                </div>
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
                    {{ $videos->withQueryString()->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-tv" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No Clips Found</h3>
                    <p style="color: var(--color-text-muted);">Check back later for epic streamer moments!</p>
                </div>
            @endif
        </div>
    </div></x-layouts.app>
