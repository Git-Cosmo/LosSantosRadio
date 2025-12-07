<?php

namespace Database\Factories;

use App\Models\DjProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DjSchedule>
 */
class DjScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = fake()->numberBetween(6, 22);
        $endHour = $startHour + fake()->numberBetween(1, 3);

        return [
            'dj_profile_id' => DjProfile::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time' => sprintf('%02d:00:00', $startHour),
            'end_time' => sprintf('%02d:00:00', min($endHour, 23)),
            'show_name' => fake()->optional()->words(3, true),
            'is_active' => true,
        ];
    }
}
