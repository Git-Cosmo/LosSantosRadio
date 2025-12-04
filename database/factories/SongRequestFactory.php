<?php

namespace Database\Factories;

use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SongRequest>
 */
class SongRequestFactory extends Factory
{
    protected $model = SongRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'song_id' => $this->faker->uuid(),
            'song_title' => $this->faker->sentence(3),
            'song_artist' => $this->faker->name(),
            'ip_address' => $this->faker->ipv4(),
            'session_id' => $this->faker->uuid(),
            'status' => SongRequest::STATUS_PENDING,
        ];
    }

    /**
     * Indicate the request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SongRequest::STATUS_PENDING,
        ]);
    }

    /**
     * Indicate the request has been played.
     */
    public function played(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SongRequest::STATUS_PLAYED,
            'played_at' => now(),
        ]);
    }

    /**
     * Indicate the request was rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SongRequest::STATUS_REJECTED,
        ]);
    }

    /**
     * Indicate the request is from a guest.
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'guest_email' => $this->faker->safeEmail(),
        ]);
    }
}
