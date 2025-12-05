<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            // Streak achievements
            [
                'name' => '3 Day Streak',
                'slug' => 'streak_3',
                'description' => 'Visit the radio for 3 consecutive days',
                'icon' => 'fas fa-fire',
                'badge_color' => '#f97316',
                'xp_reward' => 50,
                'category' => 'streaks',
                'sort_order' => 1,
            ],
            [
                'name' => 'Week Warrior',
                'slug' => 'streak_7',
                'description' => 'Visit the radio for 7 consecutive days',
                'icon' => 'fas fa-fire',
                'badge_color' => '#f97316',
                'xp_reward' => 100,
                'category' => 'streaks',
                'sort_order' => 2,
            ],
            [
                'name' => 'Two Week Champion',
                'slug' => 'streak_14',
                'description' => 'Visit the radio for 14 consecutive days',
                'icon' => 'fas fa-fire-alt',
                'badge_color' => '#ef4444',
                'xp_reward' => 200,
                'category' => 'streaks',
                'sort_order' => 3,
            ],
            [
                'name' => 'Monthly Devotee',
                'slug' => 'streak_30',
                'description' => 'Visit the radio for 30 consecutive days',
                'icon' => 'fas fa-fire-alt',
                'badge_color' => '#ef4444',
                'xp_reward' => 500,
                'category' => 'streaks',
                'sort_order' => 4,
            ],
            [
                'name' => 'Two Month Legend',
                'slug' => 'streak_60',
                'description' => 'Visit the radio for 60 consecutive days',
                'icon' => 'fas fa-crown',
                'badge_color' => '#fbbf24',
                'xp_reward' => 1000,
                'category' => 'streaks',
                'sort_order' => 5,
            ],
            [
                'name' => 'Century Club',
                'slug' => 'streak_100',
                'description' => 'Visit the radio for 100 consecutive days',
                'icon' => 'fas fa-crown',
                'badge_color' => '#fbbf24',
                'xp_reward' => 2000,
                'category' => 'streaks',
                'sort_order' => 6,
            ],

            // Request achievements
            [
                'name' => 'First Request',
                'slug' => 'requester_1',
                'description' => 'Make your first song request',
                'icon' => 'fas fa-music',
                'badge_color' => '#58a6ff',
                'xp_reward' => 25,
                'category' => 'requests',
                'sort_order' => 1,
            ],
            [
                'name' => 'Avid Listener',
                'slug' => 'requester_10',
                'description' => 'Make 10 song requests',
                'icon' => 'fas fa-music',
                'badge_color' => '#58a6ff',
                'xp_reward' => 75,
                'category' => 'requests',
                'sort_order' => 2,
            ],
            [
                'name' => 'Request Pro',
                'slug' => 'requester_50',
                'description' => 'Make 50 song requests',
                'icon' => 'fas fa-compact-disc',
                'badge_color' => '#a855f7',
                'xp_reward' => 200,
                'category' => 'requests',
                'sort_order' => 3,
            ],
            [
                'name' => 'Request Master',
                'slug' => 'requester_100',
                'description' => 'Make 100 song requests',
                'icon' => 'fas fa-compact-disc',
                'badge_color' => '#a855f7',
                'xp_reward' => 500,
                'category' => 'requests',
                'sort_order' => 4,
            ],
            [
                'name' => 'Request Legend',
                'slug' => 'requester_500',
                'description' => 'Make 500 song requests',
                'icon' => 'fas fa-star',
                'badge_color' => '#fbbf24',
                'xp_reward' => 1500,
                'category' => 'requests',
                'sort_order' => 5,
            ],

            // Level achievements
            [
                'name' => 'Level 5',
                'slug' => 'level_5',
                'description' => 'Reach level 5',
                'icon' => 'fas fa-arrow-up',
                'badge_color' => '#3fb950',
                'xp_reward' => 100,
                'category' => 'levels',
                'sort_order' => 1,
            ],
            [
                'name' => 'Level 10',
                'slug' => 'level_10',
                'description' => 'Reach level 10',
                'icon' => 'fas fa-arrow-up',
                'badge_color' => '#3fb950',
                'xp_reward' => 250,
                'category' => 'levels',
                'sort_order' => 2,
            ],
            [
                'name' => 'Level 15',
                'slug' => 'level_15',
                'description' => 'Reach level 15',
                'icon' => 'fas fa-bolt',
                'badge_color' => '#fbbf24',
                'xp_reward' => 500,
                'category' => 'levels',
                'sort_order' => 3,
            ],
            [
                'name' => 'Level 20',
                'slug' => 'level_20',
                'description' => 'Reach the maximum level',
                'icon' => 'fas fa-crown',
                'badge_color' => '#fbbf24',
                'xp_reward' => 1000,
                'category' => 'levels',
                'sort_order' => 4,
            ],

            // Community achievements
            [
                'name' => 'Community Member',
                'slug' => 'first_comment',
                'description' => 'Post your first comment',
                'icon' => 'fas fa-comment',
                'badge_color' => '#58a6ff',
                'xp_reward' => 25,
                'category' => 'community',
                'sort_order' => 1,
            ],
            [
                'name' => 'Poll Participant',
                'slug' => 'first_vote',
                'description' => 'Vote in your first poll',
                'icon' => 'fas fa-vote-yea',
                'badge_color' => '#58a6ff',
                'xp_reward' => 25,
                'category' => 'community',
                'sort_order' => 2,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['slug' => $achievement['slug']],
                $achievement
            );
        }
    }
}
