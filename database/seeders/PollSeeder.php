<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds entertaining gaming polls (and a couple of odd ones for testing).
     */
    public function run(): void
    {
        $polls = [
            // Normal gaming polls
            [
                'question' => 'What\'s your favorite gaming platform?',
                'description' => 'Let us know which platform you prefer for your gaming sessions!',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
                'allow_multiple' => false,
                'is_active' => true,
                'show_results' => true,
                'options' => [
                    'PC Master Race',
                    'PlayStation',
                    'Xbox',
                    'Nintendo Switch',
                    'Mobile Gaming',
                    'Retro Consoles',
                ],
            ],
            [
                'question' => 'Which game genre do you play the most?',
                'description' => 'Help us understand what types of games our community loves!',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(2),
                'allow_multiple' => false,
                'is_active' => true,
                'show_results' => true,
                'options' => [
                    'First-Person Shooters (FPS)',
                    'Role-Playing Games (RPG)',
                    'Battle Royale',
                    'Sports & Racing',
                    'Strategy & Simulation',
                    'Horror & Survival',
                    'Fighting Games',
                    'Puzzle & Casual',
                ],
            ],
            [
                'question' => 'Best gaming soundtrack of all time?',
                'description' => 'Vote for the game with the most memorable and epic music!',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(2),
                'allow_multiple' => false,
                'is_active' => true,
                'show_results' => true,
                'options' => [
                    'The Legend of Zelda: Ocarina of Time',
                    'Final Fantasy VII',
                    'DOOM (2016)',
                    'Halo Series',
                    'The Witcher 3',
                    'Minecraft',
                    'Undertale',
                    'Nier: Automata',
                ],
            ],
            [
                'question' => 'What feature would you want most in GTA VI?',
                'description' => 'The next GTA is coming! What feature are you most excited about?',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(6),
                'allow_multiple' => true,
                'is_active' => true,
                'show_results' => true,
                'options' => [
                    'Larger Map with Multiple Cities',
                    'More Realistic NPC AI',
                    'Better Online Co-op Heists',
                    'Expanded Character Customization',
                    'More Vehicles & Customization',
                    'Dynamic Weather & Seasons',
                    'VR Support',
                ],
            ],

            // Odd/funny polls that people wouldn't necessarily want to answer
            [
                'question' => 'If you could only play ONE game for the rest of your life but had to stream it 8 hours daily, which would you choose?',
                'description' => 'No breaks, no variety. This is your life now. Choose wisely...',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(1),
                'allow_multiple' => false,
                'is_active' => true,
                'show_results' => true,
                'options' => [
                    'Minecraft (Forever digging)',
                    'Dark Souls (Eternal suffering)',
                    'FIFA/Madden (Same game yearly)',
                    'Among Us (Trust issues forever)',
                    'Farming Simulator (Live the dream)',
                    'Desert Bus (Why would you...)',
                ],
            ],
            [
                'question' => 'Would you delete all your gaming progress for...',
                'description' => 'Tough choices require strong wills. Which sacrifice would you make?',
                'starts_at' => now(),
                'ends_at' => now()->addMonths(1),
                'allow_multiple' => false,
                'is_active' => true,
                'show_results' => true,
                'options' => [
                    '$10,000 cash (All saves gone)',
                    'Never have lag again (But start over)',
                    'Beta access to every game forever',
                    'Ability to pause online multiplayer',
                    'I wouldn\'t delete anything (Coward!)',
                    'What\'s gaming progress? (Casual detected)',
                ],
            ],
        ];

        foreach ($polls as $pollData) {
            $options = $pollData['options'];
            unset($pollData['options']);

            $poll = Poll::updateOrCreate(
                ['question' => $pollData['question']],
                $pollData
            );

            // Add options for this poll
            foreach ($options as $index => $optionText) {
                PollOption::updateOrCreate(
                    [
                        'poll_id' => $poll->id,
                        'option_text' => $optionText,
                    ],
                    [
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }

        $this->command->info('Polls seeded successfully! Added '.count($polls).' gaming polls (including 2 odd ones for fun).');
    }
}
