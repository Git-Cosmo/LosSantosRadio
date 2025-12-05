<?php

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
        // User XP and Levels
        Schema::table('users', function (Blueprint $table) {
            $table->integer('xp')->default(0)->after('avatar');
            $table->integer('level')->default(1)->after('xp');
            $table->integer('current_streak')->default(0)->after('level');
            $table->integer('longest_streak')->default(0)->after('current_streak');
            $table->date('last_activity_date')->nullable()->after('longest_streak');
            $table->string('bio', 500)->nullable()->after('last_activity_date');
            $table->boolean('is_dj')->default(false)->after('bio');
            $table->string('dj_name')->nullable()->after('is_dj');
        });

        // Achievements
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('badge_color')->default('#58a6ff');
            $table->integer('xp_reward')->default(0);
            $table->string('category')->default('general');
            $table->json('requirements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // User Achievements
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->timestamp('earned_at');
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']);
        });

        // Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('event_type')->default('general');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Music Polls
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('allow_multiple')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_results')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Poll Options
        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('option_text');
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Poll Votes
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['poll_id', 'user_id']);
            $table->index(['poll_id', 'ip_address']);
        });

        // DJ Profiles (Staff)
        Schema::create('dj_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stage_name');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('social_links')->nullable();
            $table->json('genres')->nullable();
            $table->string('show_name')->nullable();
            $table->text('show_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('total_shows')->default(0);
            $table->integer('total_listeners')->default(0);
            $table->timestamps();
        });

        // DJ Schedule
        Schema::create('dj_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dj_profile_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('show_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // XP History
        Schema::create('xp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('amount');
            $table->string('reason');
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xp_transactions');
        Schema::dropIfExists('dj_schedules');
        Schema::dropIfExists('dj_profiles');
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('events');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['xp', 'level', 'current_streak', 'longest_streak', 'last_activity_date', 'bio', 'is_dj', 'dj_name']);
        });
    }
};
