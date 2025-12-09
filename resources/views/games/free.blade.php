<x-layouts.app :title="'Free Games'">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-gift" style="color: var(--color-accent);"></i>
                Free Games
            </h1>
            <span style="color: var(--color-text-muted);">{{ $games->total() }} games found</span>
        </div>
        <div class="card-body">
            @if($games->count() > 0)
                <div class="games-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    @foreach($games as $game)
                        <div class="game-card card" style="overflow: hidden;">
                            <div style="height: 160px; background: linear-gradient(135deg, var(--color-bg-tertiary) 0%, rgba(88, 166, 255, 0.1) 100%); display: flex; align-items: center; justify-content: center;">
                                @if($game->image_url)
                                    <img src="{{ $game->image_url }}" alt="{{ $game->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-gamepad" style="font-size: 3rem; color: var(--color-text-muted);"></i>
                                @endif
                            </div>
                            <div class="card-body">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem;">{{ Str::limit($game->title, 50) }}</h3>
                                @if($game->platform || $game->store)
                                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                                        @if($game->platform)
                                            <span class="badge badge-primary">{{ $game->platform }}</span>
                                        @endif
                                        @if($game->store)
                                            <span class="badge badge-gray">{{ $game->store }}</span>
                                        @endif
                                    </div>
                                @endif
                                @if($game->description)
                                    <p style="color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 0.75rem;">
                                        {{ Str::limit($game->description, 100) }}
                                    </p>
                                @endif
                                @if($game->expires_at)
                                    <p style="color: var(--color-warning); font-size: 0.8125rem; margin-bottom: 0.75rem;">
                                        <i class="fas fa-clock"></i>
                                        Expires: {{ $game->expires_at->format('M d, Y') }}
                                    </p>
                                @endif
                                <a href="{{ $game->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width: 100%;">
                                    <i class="fas fa-external-link-alt"></i> Claim Free
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 2rem;">
                    {{ $games->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-gift" style="font-size: 4rem; color: var(--color-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No Free Games Available</h3>
                    <p style="color: var(--color-text-muted);">Check back later for new free game deals!</p>
                </div>
            @endif
        </div>
    </div>

    
</x-layouts.app>
