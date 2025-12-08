<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $this->createPermissions();

        // Create roles for Online Radio Station / Gaming Community
        $this->createRoles();

        // Create default settings
        $this->createDefaultSettings();

        // Run all additional seeders
        $this->call([
            AchievementSeeder::class,
            EventSeeder::class,
            PollSeeder::class,
            RssFeedSeeder::class,
        ]);
    }

    /**
     * Create permissions for the application.
     */
    protected function createPermissions(): void
    {
        $permissions = [
            // User management
            'manage users',
            'view users',
            'edit users',
            'delete users',

            // Content management
            'manage news',
            'create news',
            'edit news',
            'delete news',

            // Events
            'manage events',
            'create events',
            'edit events',
            'delete events',

            // Polls
            'manage polls',
            'create polls',
            'edit polls',
            'delete polls',

            // Song requests
            'manage requests',
            'approve requests',
            'reject requests',

            // DJ/Show management
            'manage djs',
            'manage schedule',
            'go live',

            // Media
            'manage media',
            'upload media',
            'delete media',

            // Settings
            'manage settings',

            // Activity logs
            'view activity',

            // Discord
            'manage discord',

            // Games/Videos
            'manage games',
            'manage videos',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Create roles for Online Radio Station / Gaming Community.
     */
    protected function createRoles(): void
    {
        // Admin - Full access to everything
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Staff - General staff members with most permissions
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view users',
            'manage news',
            'create news',
            'edit news',
            'manage events',
            'create events',
            'edit events',
            'manage polls',
            'create polls',
            'edit polls',
            'manage requests',
            'approve requests',
            'reject requests',
            'view activity',
            'manage media',
            'upload media',
        ]);

        // DJ - Radio DJs who can manage their shows
        $djRole = Role::firstOrCreate(['name' => 'dj']);
        $djRole->givePermissionTo([
            'manage schedule',
            'go live',
            'manage requests',
            'approve requests',
            'reject requests',
            'upload media',
        ]);

        // Moderator - Community moderators
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $moderatorRole->givePermissionTo([
            'view users',
            'manage requests',
            'reject requests',
            'view activity',
        ]);

        // VIP Listener - Premium listeners with extra privileges
        $vipRole = Role::firstOrCreate(['name' => 'vip']);

        // Listener - Regular authenticated users (default role)
        $listenerRole = Role::firstOrCreate(['name' => 'listener']);

        // Guest - Unauthenticated visitors (for reference)
        $guestRole = Role::firstOrCreate(['name' => 'guest']);
    }

    /**
     * Create default settings for the application.
     */
    protected function createDefaultSettings(): void
    {
        $settings = [
            // Request limits
            [
                'key' => 'request_guest_max_per_day',
                'value' => '2',
                'type' => 'integer',
                'group' => 'requests',
                'description' => 'Maximum number of song requests per day for guests',
            ],
            [
                'key' => 'request_user_min_interval_seconds',
                'value' => '60',
                'type' => 'integer',
                'group' => 'requests',
                'description' => 'Minimum seconds between requests for logged-in users',
            ],
            [
                'key' => 'request_user_max_per_window',
                'value' => '10',
                'type' => 'integer',
                'group' => 'requests',
                'description' => 'Maximum requests per time window for logged-in users',
            ],
            [
                'key' => 'request_user_window_minutes',
                'value' => '20',
                'type' => 'integer',
                'group' => 'requests',
                'description' => 'Time window in minutes for request limiting',
            ],

            // Site settings
            [
                'key' => 'site_name',
                'value' => 'Los Santos Radio',
                'type' => 'string',
                'group' => 'site',
                'description' => 'The name of the radio station',
            ],
            [
                'key' => 'site_description',
                'value' => 'Your 24/7 source for the best music in Los Santos!',
                'type' => 'string',
                'group' => 'site',
                'description' => 'A brief description of the station',
            ],
            [
                'key' => 'seasonal_theme',
                'value' => 'default',
                'type' => 'string',
                'group' => 'theme',
                'description' => 'Current seasonal theme (default, halloween, christmas, summer)',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
