<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PollsController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function index(): View
    {
        $activePolls = Poll::with('options')
            ->active()
            ->orderBy('ends_at')
            ->get();

        $recentPolls = Poll::with('options')
            ->where('is_active', true)
            ->where('ends_at', '<', now())
            ->orderBy('ends_at', 'desc')
            ->take(5)
            ->get();

        return view('polls.index', [
            'activePolls' => $activePolls,
            'recentPolls' => $recentPolls,
        ]);
    }

    public function show(string $slug): View
    {
        $poll = Poll::with('options')
            ->where('slug', $slug)
            ->firstOrFail();

        $hasVoted = $poll->hasUserVoted(auth()->user(), request()->ip());

        return view('polls.show', [
            'poll' => $poll,
            'hasVoted' => $hasVoted,
        ]);
    }

    public function vote(Request $request, Poll $poll): JsonResponse
    {
        if (! $poll->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'This poll is no longer accepting votes.',
            ], 400);
        }

        $validated = $request->validate([
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $option = PollOption::findOrFail($validated['option_id']);

        if ($option->poll_id !== $poll->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid option for this poll.',
            ], 400);
        }

        $user = auth()->user();
        $ipAddress = $request->ip();

        // Check if already voted
        if ($poll->hasUserVoted($user, $ipAddress)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted in this poll.',
            ], 400);
        }

        // Create vote
        PollVote::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
            'user_id' => $user?->id,
            'ip_address' => $ipAddress,
        ]);

        // Award XP for voting
        if ($user) {
            $this->gamificationService->awardPollVote($user);
        }

        // Get updated results
        $results = $poll->options->map(fn ($opt) => [
            'id' => $opt->id,
            'option_text' => $opt->option_text,
            'votes' => $opt->voteCount(),
            'percentage' => $opt->votePercentage(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vote recorded successfully!',
            'results' => $results,
            'total_votes' => $poll->totalVotes(),
        ]);
    }

    public function results(Poll $poll): JsonResponse
    {
        if (! $poll->show_results && $poll->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Results are hidden until the poll ends.',
            ], 403);
        }

        $results = $poll->options->map(fn ($opt) => [
            'id' => $opt->id,
            'option_text' => $opt->option_text,
            'votes' => $opt->voteCount(),
            'percentage' => $opt->votePercentage(),
        ]);

        return response()->json([
            'success' => true,
            'results' => $results,
            'total_votes' => $poll->totalVotes(),
        ]);
    }
}
