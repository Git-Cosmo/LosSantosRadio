<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">
            <i class="fas fa-trophy"></i> Top Requesters
        </h3>
        <div style="display: flex; gap: 0.5rem;">
            <button
                wire:click="setTimeframe('all')"
                class="btn {{ $timeframe === 'all' ? 'btn-primary' : 'btn-secondary' }}"
                style="padding: 0.25rem 0.75rem; font-size: 0.75rem;"
            >
                All Time
            </button>
            <button
                wire:click="setTimeframe('month')"
                class="btn {{ $timeframe === 'month' ? 'btn-primary' : 'btn-secondary' }}"
                style="padding: 0.25rem 0.75rem; font-size: 0.75rem;"
            >
                Month
            </button>
            <button
                wire:click="setTimeframe('week')"
                class="btn {{ $timeframe === 'week' ? 'btn-primary' : 'btn-secondary' }}"
                style="padding: 0.25rem 0.75rem; font-size: 0.75rem;"
            >
                Week
            </button>
            <button
                wire:click="setTimeframe('today')"
                class="btn {{ $timeframe === 'today' ? 'btn-primary' : 'btn-secondary' }}"
                style="padding: 0.25rem 0.75rem; font-size: 0.75rem;"
            >
                Today
            </button>
        </div>
    </div>
    <div class="card-body">
        @if(count($leaders) > 0)
            <div class="trending-list">
                @foreach($leaders as $leader)
                    <div class="trending-item">
                        <span class="trending-rank">
                            @if($leader['rank'] === 1)
                                🥇
                            @elseif($leader['rank'] === 2)
                                🥈
                            @elseif($leader['rank'] === 3)
                                🥉
                            @else
                                #{{ $leader['rank'] }}
                            @endif
                        </span>
                        @if($leader['user'])
                            <img
                                src="{{ $leader['user']->avatar_url }}"
                                alt="{{ $leader['user']->name }}"
                                class="history-art"
                                style="width: 40px; height: 40px; border-radius: 50%;"
                            >
                            <div class="trending-info">
                                <div class="trending-title">{{ $leader['user']->name }}</div>
                                <div class="trending-artist">
                                    {{ $leader['request_count'] }} {{ Str::plural('request', $leader['request_count']) }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
                <i class="fas fa-music" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p>No requests yet for this period.</p>
                <p style="font-size: 0.875rem;">Be the first to request a song!</p>
            </div>
        @endif
    </div>
</div>
