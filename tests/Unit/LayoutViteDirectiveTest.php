<?php

namespace Tests\Unit;

use Tests\TestCase;

class LayoutViteDirectiveTest extends TestCase
{
    /**
     * Test that the app layout contains the @vite directive
     *
     * @return void
     */
    public function test_app_layout_contains_vite_directive(): void
    {
        $layoutPath = resource_path('views/layouts/app.blade.php');
        
        $this->assertFileExists($layoutPath, 'App layout file should exist');
        
        $layoutContent = file_get_contents($layoutPath);
        
        // Check for @vite directive with the correct assets
        $this->assertStringContainsString(
            "@vite(['resources/css/app.css', 'resources/js/app.js'])",
            $layoutContent,
            'App layout must contain @vite directive to load CSS and JS assets'
        );
    }
    
    /**
     * Test that the vite config exists and is properly configured
     *
     * @return void
     */
    public function test_vite_config_exists_and_configured(): void
    {
        $viteConfigPath = base_path('vite.config.js');
        
        $this->assertFileExists($viteConfigPath, 'Vite config should exist');
        
        $viteConfig = file_get_contents($viteConfigPath);
        
        // Verify vite config includes our assets
        $this->assertStringContainsString('resources/css/app.css', $viteConfig);
        $this->assertStringContainsString('resources/js/app.js', $viteConfig);
    }
}
