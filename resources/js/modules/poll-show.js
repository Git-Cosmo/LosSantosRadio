document.addEventListener('DOMContentLoaded', function() {
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
});
