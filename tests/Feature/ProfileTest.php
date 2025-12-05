<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_edit(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Edit Profile');
        $response->assertSee($user->name);
    }

    public function test_user_can_update_their_name(): void
    {
        $user = User::factory()->create(['name' => 'Original Name']);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect(route('profile.show', $user));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_can_update_their_bio(): void
    {
        $user = User::factory()->create(['bio' => null]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'bio' => 'This is my new bio.',
        ]);

        $response->assertRedirect(route('profile.show', $user));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'bio' => 'This is my new bio.',
        ]);
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_cannot_exceed_255_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_bio_cannot_exceed_500_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Valid Name',
            'bio' => str_repeat('a', 501),
        ]);

        $response->assertSessionHasErrors('bio');
    }

    public function test_bio_can_be_null(): void
    {
        $user = User::factory()->create(['bio' => 'Some bio']);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'bio' => null,
        ]);

        $response->assertRedirect(route('profile.show', $user));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'bio' => null,
        ]);
    }

    public function test_guest_cannot_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->put(route('profile.update'), [
            'name' => 'Hacker Name',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_public_profile(): void
    {
        $user = User::factory()->create(['name' => 'Public User']);

        $response = $this->get(route('profile.show', $user));

        $response->assertStatus(200);
        $response->assertSee('Public User');
    }
}
