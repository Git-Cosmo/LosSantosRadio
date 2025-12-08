<?php

namespace Tests\Unit;

use App\Services\AzuraCastService;
use App\Services\IcecastService;
use App\Services\RequestLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    // RefreshDatabase is needed because RequestLimitService accesses the database in its constructor
    use RefreshDatabase;
    /**
     * Test that AzuraCastService can be resolved from the container.
     */
    public function test_azuracast_service_can_be_resolved(): void
    {
        $service = $this->app->make(AzuraCastService::class);
        
        $this->assertInstanceOf(AzuraCastService::class, $service);
    }

    /**
     * Test that AzuraCastService is registered as a singleton.
     */
    public function test_azuracast_service_is_singleton(): void
    {
        $service1 = $this->app->make(AzuraCastService::class);
        $service2 = $this->app->make(AzuraCastService::class);
        
        $this->assertSame($service1, $service2);
    }

    /**
     * Test that AzuraCastService is properly configured and functional.
     * This indirectly verifies that dependencies are correctly injected.
     */
    public function test_azuracast_service_is_functional(): void
    {
        $service = $this->app->make(AzuraCastService::class);
        
        // The isConfigured method should work without errors if dependencies are injected
        // This is a behavior test rather than testing internal implementation
        $this->assertIsBool($service->isConfigured());
    }

    /**
     * Test that IcecastService can be resolved from the container.
     */
    public function test_icecast_service_can_be_resolved(): void
    {
        $service = $this->app->make(IcecastService::class);
        
        $this->assertInstanceOf(IcecastService::class, $service);
    }

    /**
     * Test that RequestLimitService can be resolved from the container.
     */
    public function test_request_limit_service_can_be_resolved(): void
    {
        $service = $this->app->make(RequestLimitService::class);
        
        $this->assertInstanceOf(RequestLimitService::class, $service);
    }
}
