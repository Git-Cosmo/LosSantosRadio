<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dj_profiles', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('stage_name');
        });

        // Generate slugs for existing DJ profiles
        DB::table('dj_profiles')->orderBy('id')->each(function ($djProfile) {
            $slug = Str::slug($djProfile->stage_name);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure uniqueness
            while (DB::table('dj_profiles')->where('slug', $slug)->where('id', '!=', $djProfile->id)->exists()) {
                $slug = $originalSlug.'-'.$counter;
                $counter++;
            }

            DB::table('dj_profiles')->where('id', $djProfile->id)->update(['slug' => $slug]);
        });

        // Make slug non-nullable after populating
        Schema::table('dj_profiles', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dj_profiles', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
