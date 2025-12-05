<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function show(User $user): View
    {
        $user->load(['achievements', 'socialAccounts']);

        $recentRequests = $user->songRequests()
            ->latest()
            ->take(5)
            ->get();

        $recentXp = $user->xpTransactions()
            ->latest()
            ->take(10)
            ->get();

        $rank = $this->gamificationService->getUserRank($user);

        return view('profile.show', [
            'user' => $user,
            'recentRequests' => $recentRequests,
            'recentXp' => $recentXp,
            'rank' => $rank,
        ]);
    }

    public function edit(): View
    {
        $user = auth()->user();

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    public function achievements(): View
    {
        $user = auth()->user();
        $user->load('achievements');

        $allAchievements = \App\Models\Achievement::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        $earnedSlugs = $user->achievements->pluck('slug')->toArray();

        return view('profile.achievements', [
            'user' => $user,
            'allAchievements' => $allAchievements,
            'earnedSlugs' => $earnedSlugs,
        ]);
    }

    public function xpHistory(): View
    {
        $user = auth()->user();

        $transactions = $user->xpTransactions()
            ->latest()
            ->paginate(20);

        return view('profile.xp-history', [
            'user' => $user,
            'transactions' => $transactions,
        ]);
    }
}
