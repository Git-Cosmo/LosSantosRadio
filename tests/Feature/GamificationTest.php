<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_earn_xp(): void
    {
        $user = User::factory()->create(['xp' => 0, 'level' => 1]);

        $user->addXp(100, 'Test XP');

        $this->assertEquals(100, $user->fresh()->xp);
        $this->assertDatabaseHas('xp_transactions', [
            'user_id' => $user->id,
            'amount' => 100,
            'reason' => 'Test XP',
        ]);
    }

    public function test_user_levels_up_when_threshold_reached(): void
    {
        $user = User::factory()->create(['xp' => 90, 'level' => 1]);

        $user->addXp(20, 'Level up test'); // Total 110 XP, threshold for level 2 is 100

        $this->assertEquals(2, $user->fresh()->level);
    }

    public function test_streak_increments_on_consecutive_days(): void
    {
        $user = User::factory()->create([
            'current_streak' => 1,
            'last_activity_date' => now()->subDay()->toDateString(),
        ]);

        $user->updateStreak();

        $this->assertEquals(2, $user->fresh()->current_streak);
    }

    public function test_streak_resets_when_day_missed(): void
    {
        $user = User::factory()->create([
            'current_streak' => 5,
            'last_activity_date' => now()->subDays(3)->toDateString(),
        ]);

        $user->updateStreak();

        $this->assertEquals(1, $user->fresh()->current_streak);
    }

    public function test_streak_not_updated_on_same_day(): void
    {
        $user = User::factory()->create([
            'current_streak' => 3,
            'last_activity_date' => now()->toDateString(),
        ]);

        $user->updateStreak();

        $this->assertEquals(3, $user->fresh()->current_streak);
    }

    public function test_user_can_earn_achievement(): void
    {
        $user = User::factory()->create(['xp' => 0, 'level' => 1]);
        $achievement = Achievement::factory()->create([
            'name' => 'Test Achievement',
            'slug' => 'test-achievement',
            'xp_reward' => 50,
        ]);

        $result = $user->awardAchievement($achievement);

        $this->assertTrue($result);
        $this->assertTrue($user->hasAchievement('test-achievement'));
        $this->assertEquals(50, $user->fresh()->xp);
    }

    public function test_achievement_not_awarded_twice(): void
    {
        $user = User::factory()->create(['xp' => 0, 'level' => 1]);
        $achievement = Achievement::factory()->create([
            'slug' => 'test-achievement',
            'xp_reward' => 50,
        ]);

        $user->awardAchievement($achievement);
        $result = $user->awardAchievement($achievement);

        $this->assertFalse($result);
        $this->assertEquals(50, $user->fresh()->xp); // Only awarded once
    }

    public function test_level_progress_calculation(): void
    {
        // Level 2 threshold is 100 XP, Level 3 is 250 XP
        $user = User::factory()->create(['xp' => 175, 'level' => 2]);

        // Progress from 100 to 250 = 150 total range
        // 175 - 100 = 75 XP into this level
        // 75 / 150 = 50%
        $this->assertEquals(50.0, $user->level_progress);
    }

    public function test_xp_to_next_level_calculation(): void
    {
        // Level 2 threshold is 100 XP, Level 3 is 250 XP
        $user = User::factory()->create(['xp' => 175, 'level' => 2]);

        // Need 250 - 175 = 75 XP for next level
        $this->assertEquals(75, $user->xp_to_next_level);
    }

    public function test_gamification_service_awards_poll_vote(): void
    {
        $user = User::factory()->create(['xp' => 0, 'level' => 1]);
        $service = new GamificationService;

        $service->awardPollVote($user);

        $this->assertEquals(5, $user->fresh()->xp); // poll_vote reward is 5 XP
    }
}
