<x-layouts.app>
    <x-slot name="title">Music Polls</x-slot>

    <div class="hero-section" style="padding: 2rem; margin-bottom: 2rem;">
        <div class="hero-content">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">
                <i class="fas fa-poll" style="color: var(--color-accent);"></i>
                Music Polls
            </h1>
            <p style="color: var(--color-text-secondary);">
                Vote on your favorite music and help shape our playlists!
            </p>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 2fr 1fr;">
        <div>
            @if($activePolls->count() > 0)
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-vote-yea" style="color: var(--color-success);"></i>
                            Active Polls - Vote Now!
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach($activePolls as $poll)
                            <div class="card" style="margin-bottom: 1rem; border-color: var(--color-success);">
                                <div class="card-body">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                        <div>
                                            <h3 style="font-size: 1.125rem; margin-bottom: 0.5rem;">{{ $poll->question }}</h3>
                                            @if($poll->description)
                                                <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                                                    {{ $poll->description }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="badge badge-success">Active</span>
                                    </div>

                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                                        @foreach($poll->options->take(3) as $option)
                                            <span style="padding: 0.25rem 0.75rem; background: var(--color-bg-tertiary); border-radius: 999px; font-size: 0.875rem;">
                                                {{ $option->option_text }}
                                            </span>
                                        @endforeach
                                        @if($poll->options->count() > 3)
                                            <span style="padding: 0.25rem 0.75rem; background: var(--color-bg-tertiary); border-radius: 999px; font-size: 0.875rem; color: var(--color-text-muted);">
                                                +{{ $poll->options->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted);">
                                            <i class="fas fa-clock"></i> Ends {{ $poll->ends_at->diffForHumans() }}
                                        </span>
                                        <a href="{{ route('polls.show', $poll->slug) }}" class="btn btn-primary">
                                            <i class="fas fa-vote-yea"></i> Vote Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-body" style="text-align: center; padding: 3rem;">
                        <i class="fas fa-poll" style="font-size: 3rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No Active Polls</h3>
                        <p style="color: var(--color-text-muted);">Check back soon for new polls to vote on!</p>
                    </div>
                </div>
            @endif

            @if($recentPolls->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fas fa-history" style="color: var(--color-text-muted);"></i>
                            Recent Poll Results
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach($recentPolls as $poll)
                            <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px; margin-bottom: 0.75rem;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                    <h4 style="font-size: 1rem;">{{ $poll->question }}</h4>
                                    <span class="badge badge-gray">Ended</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 0.75rem; color: var(--color-text-muted);">
                                        {{ $poll->totalVotes() }} votes · Ended {{ $poll->ends_at->diffForHumans() }}
                                    </span>
                                    <a href="{{ route('polls.show', $poll->slug) }}" class="btn btn-secondary btn-sm">
                                        View Results
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                        How Polls Work
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 0.75rem;">
                            <div style="width: 30px; height: 30px; background: var(--color-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">1</div>
                            <div>
                                <p style="font-weight: 500; margin-bottom: 0.25rem;">Browse Active Polls</p>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted);">Find polls that interest you</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.75rem;">
                            <div style="width: 30px; height: 30px; background: var(--color-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">2</div>
                            <div>
                                <p style="font-weight: 500; margin-bottom: 0.25rem;">Cast Your Vote</p>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted);">Select your favorite option</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.75rem;">
                            <div style="width: 30px; height: 30px; background: var(--color-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">3</div>
                            <div>
                                <p style="font-weight: 500; margin-bottom: 0.25rem;">See Results</p>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted);">Watch how the community votes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                        Earn XP!
                    </h2>
                </div>
                <div class="card-body">
                    <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                        Voting in polls earns you <strong style="color: var(--color-accent);">5 XP</strong> per vote!
                        Login to start earning and climbing the leaderboard.
                    </p>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                            <i class="fas fa-sign-in-alt"></i> Sign In to Vote
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
