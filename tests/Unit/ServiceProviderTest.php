<?php

namespace Tests\Unit;

use App\Services\AzuraCastService;
use App\Services\CacheService;
use App\Services\IcecastService;
use App\Services\RequestLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
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
     * Test that AzuraCastService has CacheService injected.
     */
    public function test_azuracast_service_has_cache_service_injected(): void
    {
        $service = $this->app->make(AzuraCastService::class);
        
        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('cacheService');
        $property->setAccessible(true);
        $cacheService = $property->getValue($service);
        
        $this->assertInstanceOf(CacheService::class, $cacheService);
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
