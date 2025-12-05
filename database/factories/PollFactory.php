<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Poll>
 */
class PollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => fake()->sentence().'?',
            'description' => fake()->optional()->sentence(),
            'starts_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'ends_at' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'allow_multiple' => fake()->boolean(20),
            'is_active' => true,
            'show_results' => true,
        ];
    }
}
