<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_index_page_loads(): void
    {
        $response = $this->get('/events');

        $response->assertStatus(200);
    }

    public function test_events_index_displays_published_events(): void
    {
        $event = Event::factory()->create([
            'is_published' => true,
            'starts_at' => now()->addDays(1),
        ]);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee($event->title);
    }

    public function test_unpublished_events_not_shown_on_index(): void
    {
        $event = Event::factory()->create([
            'is_published' => false,
            'starts_at' => now()->addDays(1),
        ]);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertDontSee($event->title);
    }

    public function test_events_show_page_displays_event(): void
    {
        $event = Event::factory()->create([
            'is_published' => true,
        ]);

        $response = $this->get('/events/'.$event->slug);

        $response->assertStatus(200);
        $response->assertSee($event->title);
    }

    public function test_events_show_page_404_for_unpublished(): void
    {
        $event = Event::factory()->create([
            'is_published' => false,
        ]);

        $response = $this->get('/events/'.$event->slug);

        $response->assertStatus(404);
    }
}
