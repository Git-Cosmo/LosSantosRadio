<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_check_command_runs(): void
    {
        $this->artisan('health:check')
            ->assertExitCode(1); // Expected to fail since AzuraCast/Icecast aren't configured
    }

    public function test_health_check_command_with_detailed_option(): void
    {
        $this->artisan('health:check', ['--detailed' => true])
            ->expectsOutputToContain('Environment')
            ->expectsOutputToContain('Database')
            ->expectsOutputToContain('Cache')
            ->assertExitCode(1);
    }
}
