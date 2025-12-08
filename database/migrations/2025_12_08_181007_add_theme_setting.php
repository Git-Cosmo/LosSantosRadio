<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add theme setting with default value of 'none' using query builder
        DB::table('settings')->updateOrInsert(
            ['key' => 'site_theme'],
            [
                'value' => 'none',
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'Active site theme overlay (none, christmas, newyear)',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'site_theme')->delete();
    }
};
