<x-layouts.app>
    <x-slot name="title">{{ $poll->question }}</x-slot>

    <div style="max-width: 700px; margin: 0 auto;">
        <a href="{{ route('polls.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
            <i class="fas fa-arrow-left"></i> Back to Polls
        </a>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <div>
                        <h1 style="font-size: 1.5rem; margin-bottom: 0.5rem;">{{ $poll->question }}</h1>
                        @if($poll->description)
                            <p style="color: var(--color-text-secondary);">{{ $poll->description }}</p>
                        @endif
                    </div>
                    @if($poll->isOpen())
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-gray">Ended</span>
                    @endif
                </div>

                <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                    <div>
                        <p style="font-size: 0.75rem; color: var(--color-text-muted);">Total Votes</p>
                        <p style="font-size: 1.25rem; font-weight: 600;" id="total-votes">{{ $poll->totalVotes() }}</p>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $poll->isOpen() ? 'Ends' : 'Ended' }}</p>
                        <p style="font-size: 0.875rem;">{{ $poll->ends_at->diffForHumans() }}</p>
                    </div>
                    @if($poll->allow_multiple)
                        <div>
                            <p style="font-size: 0.75rem; color: var(--color-text-muted);">Type</p>
                            <p style="font-size: 0.875rem;">Multiple choice</p>
                        </div>
                    @endif
                </div>

                <div id="poll-options">
                    @foreach($poll->options as $option)
                        <div class="poll-option" data-option-id="{{ $option->id }}" style="margin-bottom: 0.75rem; padding: 1rem; background: var(--color-bg-tertiary); border: 2px solid var(--color-border); border-radius: 8px; cursor: {{ $poll->isOpen() && !$hasVoted ? 'pointer' : 'default' }}; transition: all 0.2s ease;" @if($poll->isOpen() && !$hasVoted) onclick="vote({{ $option->id }})" @endif>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="font-weight: 500;">{{ $option->option_text }}</span>
                                <span class="vote-count" style="font-size: 0.875rem; color: var(--color-text-secondary);">
                                    @if($hasVoted || !$poll->isOpen() || $poll->show_results)
                                        {{ $option->voteCount() }} votes ({{ $option->votePercentage() }}%)
                                    @endif
                                </span>
                            </div>
                            @if($hasVoted || !$poll->isOpen() || $poll->show_results)
                                <div style="height: 8px; background: var(--color-bg-hover); border-radius: 4px; overflow: hidden;">
                                    <div class="vote-bar" style="height: 100%; background: linear-gradient(90deg, var(--color-accent), #a855f7); width: {{ $option->votePercentage() }}%; transition: width 0.5s ease;"></div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($poll->isOpen() && !$hasVoted)
                    <p style="text-align: center; color: var(--color-text-muted); font-size: 0.875rem; margin-top: 1rem;">
                        <i class="fas fa-info-circle"></i> Click an option to vote
                    </p>
                @elseif($hasVoted)
                    <div style="text-align: center; padding: 1rem; background: rgba(63, 185, 80, 0.1); border-radius: 8px; margin-top: 1rem;">
                        <p style="color: var(--color-success);">
                            <i class="fas fa-check-circle"></i> You've already voted in this poll
                        </p>
                    </div>
                @endif
            </div>
        </div>

        @if($poll->creator)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body">
                    <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.5rem;">Created by</p>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <img src="{{ $poll->creator->avatar_url }}" alt="{{ $poll->creator->name }}" style="width: 40px; height: 40px; border-radius: 50%;">
                        <div>
                            <p style="font-weight: 500;">{{ $poll->creator->name }}</p>
                            <p style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $poll->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function vote(optionId) {
            const options = document.querySelectorAll('.poll-option');
            options.forEach(opt => {
                opt.style.pointerEvents = 'none';
                opt.style.opacity = '0.7';
            });

            fetch('{{ route("polls.vote", $poll) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ option_id: optionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    updateResults(data.results, data.total_votes);

                    // Highlight voted option
                    options.forEach(opt => {
                        opt.style.cursor = 'default';
                        opt.onclick = null;
                        if (parseInt(opt.dataset.optionId) === optionId) {
                            opt.style.borderColor = 'var(--color-success)';
                            opt.style.background = 'rgba(63, 185, 80, 0.1)';
                        }
                    });
                } else {
                    showToast('error', data.message);
                    options.forEach(opt => {
                        opt.style.pointerEvents = 'auto';
                        opt.style.opacity = '1';
                    });
                }
            })
            .catch(err => {
                console.error(err);
                showToast('error', 'Failed to submit vote. Please try again.');
                options.forEach(opt => {
                    opt.style.pointerEvents = 'auto';
                    opt.style.opacity = '1';
                });
            });
        }

        function updateResults(results, totalVotes) {
            document.getElementById('total-votes').textContent = totalVotes;

            results.forEach(result => {
                const option = document.querySelector(`[data-option-id="${result.id}"]`);
                if (option) {
                    const voteCount = option.querySelector('.vote-count');
                    voteCount.textContent = `${result.votes} votes (${result.percentage}%)`;

                    let bar = option.querySelector('.vote-bar');
                    if (!bar) {
                        const barContainer = document.createElement('div');
                        barContainer.style.cssText = 'height: 8px; background: var(--color-bg-hover); border-radius: 4px; overflow: hidden; margin-top: 0.5rem;';
                        bar = document.createElement('div');
                        bar.className = 'vote-bar';
                        bar.style.cssText = 'height: 100%; background: linear-gradient(90deg, var(--color-accent), #a855f7); width: 0%; transition: width 0.5s ease;';
                        barContainer.appendChild(bar);
                        option.appendChild(barContainer);
                    }
                    setTimeout(() => {
                        bar.style.width = `${result.percentage}%`;
                    }, 100);

                    option.style.opacity = '1';
                }
            });
        }
    </script>    @endpush
</x-layouts.app>
