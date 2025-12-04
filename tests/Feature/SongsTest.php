<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongsTest extends TestCase
{
    use RefreshDatabase;

    public function test_songs_page_loads(): void
    {
        $response = $this->get(route('songs'));

        $response->assertStatus(200);
        $response->assertSee('Song');
    }

    public function test_songs_page_has_search_form(): void
    {
        $response = $this->get(route('songs'));

        $response->assertStatus(200);
        $response->assertSee('Search');
    }

    public function test_songs_page_accepts_search_parameter(): void
    {
        $response = $this->get(route('songs', ['search' => 'test']));

        $response->assertStatus(200);
    }
}
