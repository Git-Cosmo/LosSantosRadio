<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_post_comment(): void
    {
        $user = User::factory()->create();
        $news = News::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Test content',
            'author_id' => $user->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->post(route('comments.store', $news->slug), [
            'body' => 'Test comment',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_post_comment(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $news = News::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Test content',
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->actingAs($commenter)->post(route('comments.store', $news->slug), [
            'body' => 'This is a test comment',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'body' => 'This is a test comment',
            'user_id' => $commenter->id,
        ]);
    }

    public function test_comment_requires_body(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $news = News::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Test content',
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $response = $this->actingAs($commenter)->post(route('comments.store', $news->slug), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_user_can_delete_own_comment(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $news = News::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Test content',
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $comment = Comment::create([
            'commentable_type' => News::class,
            'commentable_id' => $news->id,
            'user_id' => $commenter->id,
            'body' => 'Test comment to delete',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($commenter)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_delete_others_comment(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $otherUser = User::factory()->create();
        $news = News::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Test content',
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        $comment = Comment::create([
            'commentable_type' => News::class,
            'commentable_id' => $news->id,
            'user_id' => $commenter->id,
            'body' => 'Test comment',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($otherUser)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'deleted_at' => null]);
    }

    public function test_comments_appear_on_news_page(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $news = News::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Test content',
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'source' => 'manual',
        ]);

        Comment::create([
            'commentable_type' => News::class,
            'commentable_id' => $news->id,
            'user_id' => $commenter->id,
            'body' => 'This comment should appear',
            'is_approved' => true,
        ]);

        $response = $this->get(route('news.show', $news->slug));

        $response->assertStatus(200);
        $response->assertSee('This comment should appear');
    }
}
