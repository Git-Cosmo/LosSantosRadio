<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;

class GamificationService
{
    /**
     * XP rewards for various actions.
     */
    public const XP_REWARDS = [
        'daily_login' => 10,
        'song_request' => 5,
        'song_rating' => 2,
        'comment' => 3,
        'poll_vote' => 5,
        'streak_bonus' => 5, // Per day of streak
        'first_request' => 25,
        'first_comment' => 15,
    ];

    /**
     * Award XP for a daily login and update streak.
     */
    public function recordDailyActivity(User $user): array
    {
        $rewards = [];
        $previousStreak = $user->current_streak;

        // Update streak first
        $user->updateStreak();

        // Award daily login XP
        $user->addXp(self::XP_REWARDS['daily_login'], 'Daily login');
        $rewards['daily_login'] = self::XP_REWARDS['daily_login'];

        // Award streak bonus
        if ($user->current_streak > 1) {
            $streakBonus = min($user->current_streak, 7) * self::XP_REWARDS['streak_bonus'];
            $user->addXp($streakBonus, "Daily streak bonus ({$user->current_streak} days)");
            $rewards['streak_bonus'] = $streakBonus;
        }

        // Check streak achievements
        $this->checkStreakAchievements($user);

        return $rewards;
    }

    /**
     * Award XP for making a song request.
     */
    public function awardSongRequest(User $user): void
    {
        $user->addXp(self::XP_REWARDS['song_request'], 'Song request');

        // Check if first request
        if ($user->songRequests()->count() === 1) {
            $user->addXp(self::XP_REWARDS['first_request'], 'First song request bonus');
        }

        $this->checkRequestAchievements($user);
    }

    /**
     * Award XP for rating a song.
     */
    public function awardSongRating(User $user): void
    {
        $user->addXp(self::XP_REWARDS['song_rating'], 'Song rating');
    }

    /**
     * Award XP for posting a comment.
     */
    public function awardComment(User $user, bool $isFirstComment = false): void
    {
        $user->addXp(self::XP_REWARDS['comment'], 'Comment posted');

        if ($isFirstComment) {
            $user->addXp(self::XP_REWARDS['first_comment'], 'First comment bonus');
        }
    }

    /**
     * Award XP for voting in a poll.
     */
    public function awardPollVote(User $user): void
    {
        $user->addXp(self::XP_REWARDS['poll_vote'], 'Poll vote');
    }

    /**
     * Check and award streak-related achievements.
     */
    protected function checkStreakAchievements(User $user): void
    {
        $streakAchievements = [
            'streak_3' => 3,
            'streak_7' => 7,
            'streak_14' => 14,
            'streak_30' => 30,
            'streak_60' => 60,
            'streak_100' => 100,
        ];

        foreach ($streakAchievements as $slug => $days) {
            if ($user->current_streak >= $days) {
                $achievement = Achievement::where('slug', $slug)->first();
                if ($achievement) {
                    $user->awardAchievement($achievement);
                }
            }
        }
    }

    /**
     * Check and award request-related achievements.
     */
    protected function checkRequestAchievements(User $user): void
    {
        $requestCounts = [
            'requester_1' => 1,
            'requester_10' => 10,
            'requester_50' => 50,
            'requester_100' => 100,
            'requester_500' => 500,
        ];

        $totalRequests = $user->songRequests()->count();

        foreach ($requestCounts as $slug => $count) {
            if ($totalRequests >= $count) {
                $achievement = Achievement::where('slug', $slug)->first();
                if ($achievement) {
                    $user->awardAchievement($achievement);
                }
            }
        }
    }

    /**
     * Check and award level-based achievements.
     */
    public function checkLevelAchievements(User $user): void
    {
        $levelAchievements = [
            'level_5' => 5,
            'level_10' => 10,
            'level_15' => 15,
            'level_20' => 20,
        ];

        foreach ($levelAchievements as $slug => $level) {
            if ($user->level >= $level) {
                $achievement = Achievement::where('slug', $slug)->first();
                if ($achievement) {
                    $user->awardAchievement($achievement);
                }
            }
        }
    }

    /**
     * Get leaderboard of top users by XP.
     */
    public function getXpLeaderboard(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return User::orderBy('xp', 'desc')
            ->orderBy('level', 'desc')
            ->limit($limit)
            ->get(['id', 'name', 'avatar', 'xp', 'level', 'current_streak']);
    }

    /**
     * Get user's rank by XP.
     */
    public function getUserRank(User $user): int
    {
        return User::where('xp', '>', $user->xp)->count() + 1;
    }
}
