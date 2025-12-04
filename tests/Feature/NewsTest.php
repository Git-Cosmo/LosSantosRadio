<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_page_loads(): void
    {
        $response = $this->get(route('news.index'));

        $response->assertStatus(200);
        $response->assertSee('News');
    }

    public function test_news_index_displays_published_articles(): void
    {
        $user = User::factory()->create();
        $news = News::create([
            'title' => 'Test News Article',
            'slug' => 'test-news-article',
            'content' => 'This is test content',
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->get(route('news.index'));

        $response->assertStatus(200);
        $response->assertSee('Test News Article');
    }

    public function test_unpublished_news_not_shown_on_index(): void
    {
        $user = User::factory()->create();
        $news = News::create([
            'title' => 'Unpublished Article',
            'slug' => 'unpublished-article',
            'content' => 'This is test content',
            'author_id' => $user->id,
            'is_published' => false,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->get(route('news.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Unpublished Article');
    }

    public function test_news_show_page_displays_article(): void
    {
        $user = User::factory()->create();
        $news = News::create([
            'title' => 'Viewable Article',
            'slug' => 'viewable-article',
            'content' => '<p>Full article content here</p>',
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->get(route('news.show', 'viewable-article'));

        $response->assertStatus(200);
        $response->assertSee('Viewable Article');
        $response->assertSee('Full article content here');
    }

    public function test_news_show_page_404_for_unpublished(): void
    {
        $user = User::factory()->create();
        $news = News::create([
            'title' => 'Hidden Article',
            'slug' => 'hidden-article',
            'content' => 'Content',
            'author_id' => $user->id,
            'is_published' => false,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->get(route('news.show', 'hidden-article'));

        $response->assertStatus(404);
    }

    public function test_slug_is_auto_generated(): void
    {
        $user = User::factory()->create();
        $news = News::create([
            'title' => 'My Amazing News Title',
            'content' => 'Content',
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now(),
            'source' => 'manual',
        ]);

        $this->assertEquals('my-amazing-news-title', $news->slug);
    }

    public function test_unique_slug_generation(): void
    {
        $user = User::factory()->create();

        $news1 = News::create([
            'title' => 'Same Title',
            'content' => 'Content 1',
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now(),
            'source' => 'manual',
        ]);

        $news2 = News::create([
            'title' => 'Same Title',
            'content' => 'Content 2',
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now(),
            'source' => 'manual',
        ]);

        $this->assertEquals('same-title', $news1->slug);
        $this->assertNotEquals($news1->slug, $news2->slug);
        $this->assertStringStartsWith('same-title', $news2->slug);
    }
}
