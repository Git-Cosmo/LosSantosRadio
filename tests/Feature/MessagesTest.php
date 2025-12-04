<?php

namespace Tests\Feature;

use App\Models\User;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_messages(): void
    {
        $response = $this->get(route('messages.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_messages_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.index'));

        $response->assertStatus(200);
        $response->assertSee('Messages');
    }

    public function test_authenticated_user_can_view_create_message_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.create'));

        $response->assertStatus(200);
        $response->assertSee('New Message');
    }

    public function test_user_can_send_a_message(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $response = $this->actingAs($sender)->post(route('messages.store'), [
            'subject' => 'Test Subject',
            'message' => 'Test message body',
            'recipients' => [$recipient->id],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('threads', ['subject' => 'Test Subject']);
        $this->assertDatabaseHas('messages', ['body' => 'Test message body']);
    }

    public function test_user_can_view_their_threads(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a thread
        $thread = Thread::create(['subject' => 'Test Thread']);

        // Add message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => $user1->id,
            'body' => 'Hello!',
        ]);

        // Add participants
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user1->id,
        ]);
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->get(route('messages.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Thread');
    }

    public function test_user_can_view_thread_they_participate_in(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $thread = Thread::create(['subject' => 'Viewable Thread']);

        Message::create([
            'thread_id' => $thread->id,
            'user_id' => $user1->id,
            'body' => 'Test message content',
        ]);

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user1->id,
        ]);
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user1)->get(route('messages.show', $thread->id));

        $response->assertStatus(200);
        $response->assertSee('Viewable Thread');
        $response->assertSee('Test message content');
    }

    public function test_user_cannot_view_thread_they_do_not_participate_in(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $outsider = User::factory()->create();

        $thread = Thread::create(['subject' => 'Private Thread']);

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user1->id,
        ]);
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($outsider)->get(route('messages.show', $thread->id));

        $response->assertRedirect(route('messages.index'));
    }
}
