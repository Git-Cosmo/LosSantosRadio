<x-layouts.app :title="$deal->title">
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <div style="width: 200px; height: 200px; background: var(--color-bg-tertiary); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        @if($deal->thumb)
                            <img src="{{ $deal->thumb }}" alt="{{ $deal->title }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        @else
                            <i class="fas fa-gamepad" style="font-size: 4rem; color: var(--color-text-muted);"></i>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $deal->title }}</h1>
                        @if($deal->store)
                            <p style="color: var(--color-text-muted); margin-bottom: 0.75rem;">
                                <i class="fas fa-store"></i> Available on {{ $deal->store->name }}
                            </p>
                        @endif
                        @if($deal->metacritic_score)
                            <div style="margin-bottom: 1rem;">
                                <span style="background: var(--color-success); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600;">
                                    {{ number_format($deal->metacritic_score) }}
                                </span>
                                <span style="color: var(--color-text-muted); margin-left: 0.5rem;">Metacritic Score</span>
                            </div>
                        @endif
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <span style="font-size: 2rem; font-weight: 700; color: var(--color-success);">
                                    ${{ number_format($deal->sale_price, 2) }}
                                </span>
                                <span style="font-size: 1rem; color: var(--color-text-muted); text-decoration: line-through; margin-left: 0.5rem;">
                                    ${{ number_format($deal->normal_price, 2) }}
                                </span>
                            </div>
                            <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 0.75rem;">
                                -{{ $deal->savings_percent }}% OFF
                            </span>
                        </div>
                        <a href="{{ $deal->deal_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart"></i> Get This Deal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedDeals->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-tags" style="color: var(--color-accent);"></i>
                        More Deals from {{ $deal->store?->name ?? 'This Store' }}
                    </h2>
                </div>
                <div class="card-body" style="padding: 0.5rem;">
                    @foreach($relatedDeals as $related)
                        <a href="{{ route('games.deals.show', $related) }}" class="history-item" style="text-decoration: none; color: inherit;">
                            <div style="width: 60px; height: 60px; background: var(--color-bg-tertiary); border-radius: 4px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                @if($related->thumb)
                                    <img src="{{ $related->thumb }}" alt="" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <i class="fas fa-gamepad" style="color: var(--color-text-muted);"></i>
                                @endif
                            </div>
                            <div class="history-info">
                                <p class="history-title">{{ Str::limit($related->title, 40) }}</p>
                                <p class="history-artist">${{ number_format($related->sale_price, 2) }} (-{{ $related->savings_percent }}%)</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('games.deals') }}" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">
            <i class="fas fa-arrow-left"></i> Back to All Deals
        </a>
    </div>
</x-layouts.app>
