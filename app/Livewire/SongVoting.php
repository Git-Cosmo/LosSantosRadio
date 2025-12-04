<?php

namespace App\Livewire;

use App\Models\SongRating;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class SongVoting extends Component
{
    public string $songId = '';

    public string $songTitle = '';

    public string $songArtist = '';

    public int $upvotes = 0;

    public int $downvotes = 0;

    public int $score = 0;

    public ?int $userRating = null;

    protected $listeners = ['refreshVotes' => 'loadVotes'];

    public function mount(string $songId = '', string $songTitle = '', string $songArtist = ''): void
    {
        $this->songId = $songId;
        $this->songTitle = $songTitle;
        $this->songArtist = $songArtist;

        if ($songId) {
            $this->loadVotes();
        }
    }

    public function loadVotes(): void
    {
        if (! $this->songId) {
            return;
        }

        $counts = SongRating::getCounts($this->songId);
        $this->upvotes = $counts['upvotes'];
        $this->downvotes = $counts['downvotes'];
        $this->score = $counts['score'];

        $existingRating = SongRating::hasRated(
            $this->songId,
            auth()->id(),
            request()->ip()
        );
        $this->userRating = $existingRating?->rating;
    }

    public function vote(int $rating): void
    {
        if (! in_array($rating, [-1, 1])) {
            return;
        }

        $userId = auth()->id();
        $ipAddress = request()->ip();

        // Rate limiting
        $rateLimitKey = 'voting:'.($userId ?? $ipAddress);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 30)) {
            $this->dispatch('showToast', type: 'error', message: 'Too many votes. Please wait a moment.');

            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        $existingRating = SongRating::hasRated($this->songId, $userId, $ipAddress);

        if ($existingRating) {
            if ($existingRating->rating === $rating) {
                // Toggle off
                $existingRating->delete();
                $this->dispatch('showToast', type: 'info', message: 'Vote removed');
            } else {
                // Change vote
                $existingRating->update(['rating' => $rating]);
                $this->dispatch('showToast', type: 'success', message: $rating === 1 ? 'Changed to upvote!' : 'Changed to downvote');
            }
        } else {
            // New vote
            SongRating::create([
                'song_id' => $this->songId,
                'song_title' => $this->songTitle,
                'song_artist' => $this->songArtist,
                'user_id' => $userId,
                'ip_address' => $userId ? null : $ipAddress,
                'rating' => $rating,
            ]);
            $this->dispatch('showToast', type: 'success', message: $rating === 1 ? 'Upvoted!' : 'Downvoted');
        }

        $this->loadVotes();
    }

    public function upvote(): void
    {
        $this->vote(1);
    }

    public function downvote(): void
    {
        $this->vote(-1);
    }

    public function render()
    {
        return view('livewire.song-voting');
    }
}
