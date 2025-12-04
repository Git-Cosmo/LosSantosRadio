<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_page_loads(): void
    {
        $response = $this->get(route('schedule'));

        $response->assertStatus(200);
        $response->assertSee('Schedule');
    }

    public function test_schedule_page_shows_autodj_message_when_no_schedule(): void
    {
        $response = $this->get(route('schedule'));

        $response->assertStatus(200);
        $response->assertSee('AutoDJ');
    }
}
