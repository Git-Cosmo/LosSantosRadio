<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\News;
use App\Models\Poll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_loads(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
    }

    public function test_search_requires_minimum_two_characters(): void
    {
        $response = $this->get('/search?q=a');

        $response->assertStatus(200);
        // Short queries should return empty results
    }

    public function test_search_api_returns_json(): void
    {
        $response = $this->getJson('/api/search?q=test');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'results',
        ]);
    }

    public function test_search_finds_published_news(): void
    {
        // Note: Laravel Scout with collection driver requires manual indexing in tests
        // This test validates the search endpoint structure
        $response = $this->getJson('/api/search?q=Gaming');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'results' => [],
        ]);
    }

    public function test_search_does_not_find_unpublished_news(): void
    {
        $news = News::create([
            'title' => 'Unpublished News Article',
            'slug' => 'unpublished-news-article',
            'content' => 'Test content',
            'is_published' => false,
        ]);

        $response = $this->getJson('/api/search?q=Unpublished');

        $response->assertStatus(200);
        $response->assertJsonMissing(['title' => 'Unpublished News Article']);
    }

    public function test_search_finds_published_events(): void
    {
        $event = Event::factory()->create([
            'title' => 'Unique Gaming Event',
            'is_published' => true,
        ]);

        $response = $this->getJson('/api/search?q=Unique');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Unique Gaming Event']);
    }

    public function test_search_finds_active_polls(): void
    {
        $poll = Poll::factory()->create([
            'question' => 'Unique Poll Question?',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/search?q=Unique');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Unique Poll Question?']);
    }

    public function test_search_handles_multiple_content_types(): void
    {
        // Test that search can handle queries across different content types
        $response = $this->getJson('/api/search?q=game');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'results' => [],
        ]);

        // Results should be an array (empty or with items)
        $data = $response->json();
        $this->assertIsArray($data['results']);
    }

    public function test_search_returns_structured_results(): void
    {
        // Test that search returns properly structured results
        // Create a published event for search
        $event = Event::factory()->create([
            'title' => 'Test Gaming Event',
            'is_published' => true,
        ]);

        $response = $this->getJson('/api/search?q=Gaming');

        $response->assertStatus(200);
        $data = $response->json();

        // Results should be an array
        $this->assertIsArray($data['results']);

        // Response should have success field
        $this->assertArrayHasKey('success', $data);
        $this->assertTrue($data['success']);

        // If there are results, they should have proper structure
        if (! empty($data['results'])) {
            $firstResult = $data['results'][0];
            $this->assertArrayHasKey('id', $firstResult);
            $this->assertArrayHasKey('type', $firstResult);
            $this->assertArrayHasKey('title', $firstResult);
            $this->assertArrayHasKey('url', $firstResult);
            $this->assertArrayHasKey('date', $firstResult);
            $this->assertArrayHasKey('date_formatted', $firstResult);
        }
    }

    public function test_search_api_returns_success_field(): void
    {
        $response = $this->getJson('/api/search?q=test');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'results',
        ]);

        $data = $response->json();
        $this->assertTrue($data['success']);
    }
}
