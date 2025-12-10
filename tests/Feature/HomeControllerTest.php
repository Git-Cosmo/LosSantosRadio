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

    public function test_home_displays_content_sections(): void
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertSee('Latest News');
        $response->assertSee('Upcoming Events');
        $response->assertSee('Hot Deals');
        $response->assertSee('Free Games');
        $response->assertSee('Community Polls');
    }

    public function test_home_displays_welcome_message(): void
    {
        $response = $this->get('/test');

        $response->assertStatus(200);
        $response->assertSee('Los Santos Radio');
        $response->assertSee('Your 24/7 online radio station');
    }
}
