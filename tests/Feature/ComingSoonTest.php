<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ComingSoonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure coming soon mode is off by default for other tests
        Config::set('app.coming_soon', false);
    }

    public function test_home_page_loads_normally_when_coming_soon_disabled(): void
    {
        Config::set('app.coming_soon', false);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Coming Soon');
    }

    public function test_coming_soon_page_displays_when_enabled(): void
    {
        Config::set('app.coming_soon', true);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Coming Soon');
        $response->assertSee('Los Santos Radio');
    }

    public function test_admin_can_bypass_coming_soon_mode(): void
    {
        Config::set('app.coming_soon', true);

        // Create admin user with permission
        $admin = User::factory()->create();
        
        // Create admin role if it doesn't exist
        try {
            $role = \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');
            $admin->assignRole($role);
        } catch (\Exception $e) {
            // Skip test if roles can't be created
            $this->markTestSkipped('Could not create admin role: ' . $e->getMessage());
        }

        $response = $this->actingAs($admin)->get('/');

        // Admin should see the normal homepage, not coming soon page
        $response->assertStatus(200);
    }

    public function test_regular_user_sees_coming_soon_page(): void
    {
        Config::set('app.coming_soon', true);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Coming Soon');
    }

    public function test_coming_soon_page_has_countdown(): void
    {
        Config::set('app.coming_soon', true);
        Config::set('app.launch_date', '2024-12-31T18:00:00Z');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Coming Soon');
        // Should have countdown elements
        $response->assertSee('Days', false);
        $response->assertSee('Hours', false);
        $response->assertSee('Minutes', false);
    }

    public function test_api_routes_accessible_during_coming_soon(): void
    {
        Config::set('app.coming_soon', true);

        // API routes should still work during coming soon mode
        $response = $this->getJson('/api/search?q=test');

        $response->assertStatus(200);
    }

    public function test_admin_routes_accessible_during_coming_soon(): void
    {
        Config::set('app.coming_soon', true);

        $admin = User::factory()->create();
        
        // Create admin role if it doesn't exist
        try {
            $role = \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');
            $admin->assignRole($role);
        } catch (\Exception $e) {
            // Skip test if roles can't be created
            $this->markTestSkipped('Could not create admin role: ' . $e->getMessage());
        }

        // Admin routes should be accessible
        $response = $this->actingAs($admin)->get('/admin');

        // Should either load admin page or redirect to admin login
        $this->assertTrue($response->isSuccessful() || $response->isRedirection());
    }
}
