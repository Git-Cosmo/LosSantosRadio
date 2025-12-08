<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add theme setting with default value of 'none'
        Setting::updateOrCreate(
            ['key' => 'site_theme'],
            [
                'value' => 'none',
                'type' => Setting::TYPE_STRING,
                'group' => 'appearance',
                'description' => 'Active site theme overlay (none, christmas, newyear)'
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'site_theme')->delete();
    }
};
