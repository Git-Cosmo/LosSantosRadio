<x-layouts.app :title="$game->title">
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <div style="width: 200px; height: 200px; background: var(--color-bg-tertiary); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                        @if($game->image_url)
                            <img src="{{ $game->image_url }}" alt="{{ $game->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-gamepad" style="font-size: 4rem; color: var(--color-text-muted);"></i>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $game->title }}</h1>
                        <div style="margin-bottom: 1rem;">
                            <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                <i class="fas fa-gift"></i> FREE
                            </span>
                            @if($game->store)
                                <span class="badge" style="background: var(--color-bg-tertiary); color: var(--color-text); margin-left: 0.5rem;">
                                    @if($game->store === 'Epic Games')
                                        <i class="fas fa-gamepad"></i>
                                    @elseif($game->store === 'Steam')
                                        <i class="fab fa-steam"></i>
                                    @else
                                        <i class="fas fa-store"></i>
                                    @endif
                                    {{ $game->store }}
                                </span>
                            @endif
                        </div>
                        @if($game->description)
                            <p style="color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 1rem;">
                                {!! nl2br(e($game->description)) !!}
                            </p>
                        @endif
                        @if($game->expires_at)
                            <div style="padding: 0.75rem; background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 8px; margin-bottom: 1rem;">
                                <p style="color: #ef4444; font-weight: 500;">
                                    <i class="far fa-clock"></i> Ends {{ $game->expires_at->diffForHumans() }}
                                </p>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.25rem;">
                                    Claim by {{ $game->expires_at->format('F j, Y g:i A') }}
                                </p>
                            </div>
                        @endif
                        <a href="{{ $game->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
                            <i class="fas fa-external-link-alt"></i> Claim This Game
                        </a>
                    </div>
                </div>

                @if($game->reddit_author || $game->reddit_score)
                <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text-muted);">
                        <i class="fab fa-reddit"></i> Community Info
                    </h3>
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                        @if($game->reddit_author)
                            <div>
                                <p style="font-size: 0.75rem; color: var(--color-text-muted);">Posted by</p>
                                <p style="font-weight: 500;">u/{{ $game->reddit_author }}</p>
                            </div>
                        @endif
                        @if($game->reddit_score)
                            <div>
                                <p style="font-size: 0.75rem; color: var(--color-text-muted);">Reddit Score</p>
                                <p style="font-weight: 500; color: var(--color-accent);">
                                    <i class="fas fa-arrow-up"></i> {{ number_format($game->reddit_score) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($relatedGames->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-gift" style="color: var(--color-success);"></i>
                        More Free Games from {{ $game->store }}
                    </h2>
                </div>
                <div class="card-body" style="padding: 0.5rem;">
                    @foreach($relatedGames as $related)
                        <a href="{{ route('games.free.show', $related) }}" class="history-item" style="text-decoration: none; color: inherit;">
                            <div style="width: 60px; height: 60px; background: var(--color-bg-tertiary); border-radius: 4px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($related->image_url)
                                    <img src="{{ $related->image_url }}" alt="{{ $related->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-gamepad" style="color: var(--color-text-muted);"></i>
                                @endif
                            </div>
                            <div class="history-info">
                                <p class="history-title">{{ Str::limit($related->title, 40) }}</p>
                                <p class="history-artist">
                                    @if($related->expires_at)
                                        Ends {{ $related->expires_at->diffForHumans() }}
                                    @else
                                        Available now
                                    @endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('games.free') }}" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">
            <i class="fas fa-arrow-left"></i> Back to All Free Games
        </a>
    </div>
</x-layouts.app>
