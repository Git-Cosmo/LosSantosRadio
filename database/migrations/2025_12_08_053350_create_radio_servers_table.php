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
        Schema::create('radio_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['icecast', 'shoutcast'])->comment('Server type');
            $table->string('host');
            $table->integer('port')->default(8000);
            $table->string('mount_point')->nullable()->comment('For Icecast');
            $table->integer('stream_id')->nullable()->comment('For Shoutcast');
            $table->string('admin_user')->nullable();
            $table->string('admin_password')->nullable();
            $table->boolean('ssl')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_start')->default(false)->comment('Auto-start Docker container');
            $table->string('docker_host')->nullable()->comment('Remote Docker host URL');
            $table->string('docker_container_name')->nullable();
            $table->string('docker_image')->nullable()->comment('Docker image to use');
            $table->json('docker_env')->nullable()->comment('Environment variables for Docker');
            $table->json('docker_ports')->nullable()->comment('Port mappings for Docker');
            $table->string('status')->default('stopped')->comment('running, stopped, error');
            $table->timestamp('last_check_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_servers');
    }
};
