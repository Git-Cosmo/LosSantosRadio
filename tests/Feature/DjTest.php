<?php

namespace Tests\Feature;

use App\Models\DjProfile;
use App\Models\DjSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DjTest extends TestCase
{
    use RefreshDatabase;

    public function test_dj_index_page_loads(): void
    {
        $response = $this->get('/djs');

        $response->assertStatus(200);
    }

    public function test_dj_index_displays_active_djs(): void
    {
        $user = User::factory()->create();
        $dj = DjProfile::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
            'stage_name' => 'DJ TestMaster',
        ]);

        $response = $this->get('/djs');

        $response->assertStatus(200);
        $response->assertSee('DJ TestMaster');
    }

    public function test_inactive_djs_not_shown_on_index(): void
    {
        $user = User::factory()->create();
        $dj = DjProfile::factory()->create([
            'user_id' => $user->id,
            'is_active' => false,
            'stage_name' => 'DJ Inactive',
        ]);

        $response = $this->get('/djs');

        $response->assertStatus(200);
        $response->assertDontSee('DJ Inactive');
    }

    public function test_dj_schedule_page_loads(): void
    {
        $response = $this->get('/djs/schedule');

        $response->assertStatus(200);
    }

    public function test_dj_schedule_displays_schedules(): void
    {
        $user = User::factory()->create();
        $dj = DjProfile::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
            'stage_name' => 'DJ Scheduled',
        ]);

        DjSchedule::factory()->create([
            'dj_profile_id' => $dj->id,
            'day_of_week' => 1, // Monday
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'is_active' => true,
        ]);

        $response = $this->get('/djs/schedule');

        $response->assertStatus(200);
        $response->assertSee('DJ Scheduled');
    }

    public function test_dj_profile_page_loads_with_valid_slug(): void
    {
        $user = User::factory()->create();
        $dj = DjProfile::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
            'stage_name' => 'DJ Profile',
            'slug' => 'dj-profile',
        ]);

        $response = $this->get('/djs/'.$dj->slug);

        $response->assertStatus(200);
        $response->assertSee('DJ Profile');
    }

    public function test_dj_profile_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/djs/non-existent-dj');

        $response->assertStatus(404);
    }

    public function test_dj_on_air_api_returns_json(): void
    {
        $response = $this->get('/api/djs/on-air');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'on_air',
        ]);
    }

    public function test_dj_on_air_api_returns_autodj_message_when_no_dj_live(): void
    {
        $response = $this->get('/api/djs/on-air');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'on_air' => false,
            'message' => 'AutoDJ is currently playing',
        ]);
    }
}
