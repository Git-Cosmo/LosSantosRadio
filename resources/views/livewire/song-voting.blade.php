<div class="song-voting" wire:poll.10s>
    @if($songId)
        <div class="voting-buttons">
            <button 
                wire:click="upvote"
                class="rating-btn upvote {{ $userRating === 1 ? 'active' : '' }}"
                title="Upvote this song"
            >
                <i class="fas fa-thumbs-up"></i>
                <span>{{ $upvotes }}</span>
            </button>

            <div class="score-display {{ $score > 0 ? 'positive' : ($score < 0 ? 'negative' : '') }}">
                <span>{{ $score >= 0 ? '+' : '' }}{{ $score }}</span>
            </div>

            <button 
                wire:click="downvote"
                class="rating-btn downvote {{ $userRating === -1 ? 'active' : '' }}"
                title="Downvote this song"
            >
                <i class="fas fa-thumbs-down"></i>
                <span>{{ $downvotes }}</span>
            </button>
        </div>
    @else
        <div class="voting-placeholder">
            <span class="text-muted">No song playing</span>
        </div>
    @endif

    <style>
        .song-voting {
            display: flex;
            justify-content: center;
            padding: 0.5rem 0;
        }

        .voting-buttons {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .score-display {
            font-size: 1.125rem;
            font-weight: 600;
            min-width: 50px;
            text-align: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            background-color: var(--color-bg-tertiary);
            color: var(--color-text-secondary);
        }

        .score-display.positive {
            color: var(--color-success);
            background-color: rgba(63, 185, 80, 0.1);
        }

        .score-display.negative {
            color: var(--color-danger);
            background-color: rgba(248, 81, 73, 0.1);
        }

        .voting-placeholder {
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }
    </style>
</div>
