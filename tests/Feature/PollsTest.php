<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollsTest extends TestCase
{
    use RefreshDatabase;

    public function test_polls_index_page_loads(): void
    {
        $response = $this->get('/polls');

        $response->assertStatus(200);
    }

    public function test_polls_index_displays_active_polls(): void
    {
        $poll = Poll::factory()->create([
            'is_active' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(1),
        ]);
        $poll->options()->create(['option_text' => 'Option 1']);
        $poll->options()->create(['option_text' => 'Option 2']);

        $response = $this->get('/polls');

        $response->assertStatus(200);
        $response->assertSee($poll->question);
    }

    public function test_polls_show_page_loads(): void
    {
        $poll = Poll::factory()->create([
            'is_active' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(1),
        ]);
        $poll->options()->create(['option_text' => 'Option 1']);
        $poll->options()->create(['option_text' => 'Option 2']);

        $response = $this->get('/polls/'.$poll->slug);

        $response->assertStatus(200);
        $response->assertSee($poll->question);
    }

    public function test_user_can_vote_in_poll(): void
    {
        $poll = Poll::factory()->create([
            'is_active' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(1),
        ]);
        $option = $poll->options()->create(['option_text' => 'Option 1']);
        $poll->options()->create(['option_text' => 'Option 2']);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/polls/'.$poll->id.'/vote', [
                'option_id' => $option->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_vote_twice(): void
    {
        $poll = Poll::factory()->create([
            'is_active' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(1),
        ]);
        $option1 = $poll->options()->create(['option_text' => 'Option 1']);
        $option2 = $poll->options()->create(['option_text' => 'Option 2']);

        $user = User::factory()->create();

        // First vote
        $this->actingAs($user)
            ->postJson('/polls/'.$poll->id.'/vote', ['option_id' => $option1->id]);

        // Second vote attempt
        $response = $this->actingAs($user)
            ->postJson('/polls/'.$poll->id.'/vote', ['option_id' => $option2->id]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_cannot_vote_in_ended_poll(): void
    {
        $poll = Poll::factory()->create([
            'is_active' => true,
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subDay(),
        ]);
        $option = $poll->options()->create(['option_text' => 'Option 1']);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/polls/'.$poll->id.'/vote', [
                'option_id' => $option->id,
            ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }
}
