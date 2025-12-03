<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $listenerRole = Role::firstOrCreate(['name' => 'listener']);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@lossantosradio.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Create default settings
        $this->createDefaultSettings();
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
