<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_route_loads_successfully(): void
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_view_has_required_variables(): void
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertViewHasAll([
            'nowPlaying',
            'history',
            'station',
            'streamStatus',
            'recentNews',
            'upcomingEvents',
            'activePolls',
            'topGameDeals',
            'freeGames',
        ]);
    }

    public function test_home_displays_modal_showcase(): void
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertSee('Modal Components Showcase');
        $response->assertSee('Search Modal');
        $response->assertSee('Listen Modal');
        $response->assertSee('OAuth Login');
    }

    public function test_home_displays_welcome_message(): void
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertSee('Welcome to Los Santos Radio');
    }
}
