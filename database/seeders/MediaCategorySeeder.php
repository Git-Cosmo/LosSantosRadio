<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use App\Models\MediaSubcategory;
use Illuminate\Database\Seeder;

class MediaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->command !== null) {
            $this->command->info('Seeding media categories and subcategories...');
        }

        $categories = [
            [
                'name' => 'Counter-Strike 2',
                'slug' => 'counter-strike-2',
                'description' => 'Maps, skins, weapon finishes, and community content for Counter-Strike 2',
                'icon' => '🔫',
                'color' => '#FF6B35',
                'order' => 1,
                'subcategories' => [
                    ['name' => 'Maps', 'description' => 'Custom maps and remakes'],
                    ['name' => 'Skins', 'description' => 'Weapon skins and finishes'],
                    ['name' => 'HUD Mods', 'description' => 'Custom HUD designs'],
                    ['name' => 'Sound Mods', 'description' => 'Weapon and ambient sounds'],
                    ['name' => 'Server Plugins', 'description' => 'Server-side modifications'],
                ],
            ],
            [
                'name' => 'Minecraft',
                'slug' => 'minecraft',
                'description' => 'Mods, texture packs, maps, and data packs for Minecraft',
                'icon' => '⛏️',
                'color' => '#00AA00',
                'order' => 2,
                'subcategories' => [
                    ['name' => 'Mods', 'description' => 'Gameplay modifications and enhancements'],
                    ['name' => 'Texture Packs', 'description' => 'Resource packs and shaders'],
                    ['name' => 'Maps', 'description' => 'Adventure, puzzle, and custom maps'],
                    ['name' => 'Data Packs', 'description' => 'Custom game mechanics'],
                    ['name' => 'Skins', 'description' => 'Player skins and models'],
                    ['name' => 'Server Plugins', 'description' => 'Bukkit, Spigot, Paper plugins'],
                ],
            ],
            [
                'name' => 'GTA V',
                'slug' => 'gta-v',
                'description' => 'Mods, scripts, vehicles, and maps for Grand Theft Auto V',
                'icon' => '🚗',
                'color' => '#00D162',
                'order' => 3,
                'subcategories' => [
                    ['name' => 'Scripts', 'description' => 'Gameplay scripts and trainers'],
                    ['name' => 'Vehicles', 'description' => 'Custom cars, bikes, and aircraft'],
                    ['name' => 'Maps', 'description' => 'Custom locations and interiors'],
                    ['name' => 'Weapons', 'description' => 'Custom weapon models'],
                    ['name' => 'Peds', 'description' => 'Character models and skins'],
                    ['name' => 'Graphics Mods', 'description' => 'ENB, ReShade, and visual enhancements'],
                ],
            ],
            [
                'name' => 'Skyrim',
                'slug' => 'skyrim',
                'description' => 'Mods and enhancements for The Elder Scrolls V: Skyrim',
                'icon' => '🐉',
                'color' => '#8B4513',
                'order' => 4,
                'subcategories' => [
                    ['name' => 'Gameplay Mods', 'description' => 'Combat, magic, and mechanics'],
                    ['name' => 'Quests & Adventures', 'description' => 'New storylines and quests'],
                    ['name' => 'Graphics & Visuals', 'description' => 'ENB, textures, and lighting'],
                    ['name' => 'Armor & Weapons', 'description' => 'Custom equipment'],
                    ['name' => 'Followers & NPCs', 'description' => 'Companion mods'],
                    ['name' => 'Utilities', 'description' => 'SKSE, UI improvements'],
                ],
            ],
            [
                'name' => 'Cyberpunk 2077',
                'slug' => 'cyberpunk-2077',
                'description' => 'Mods, tweaks, and enhancements for Cyberpunk 2077',
                'icon' => '🤖',
                'color' => '#FCEE09',
                'order' => 5,
                'subcategories' => [
                    ['name' => 'Gameplay Mods', 'description' => 'Mechanics and balance tweaks'],
                    ['name' => 'Graphics Mods', 'description' => 'Visual enhancements'],
                    ['name' => 'Clothing & Armor', 'description' => 'Custom outfits'],
                    ['name' => 'Vehicles', 'description' => 'Custom cars and bikes'],
                    ['name' => 'Weapons', 'description' => 'Weapon modifications'],
                    ['name' => 'Utilities', 'description' => 'Tools and frameworks'],
                ],
            ],
            [
                'name' => 'Starfield',
                'slug' => 'starfield',
                'description' => 'Mods and content for Starfield',
                'icon' => '🚀',
                'color' => '#1E90FF',
                'order' => 6,
                'subcategories' => [
                    ['name' => 'Gameplay Mods', 'description' => 'Game mechanics improvements'],
                    ['name' => 'Ship Mods', 'description' => 'Custom ships and parts'],
                    ['name' => 'Outpost Mods', 'description' => 'Base building enhancements'],
                    ['name' => 'Graphics', 'description' => 'Visual improvements'],
                    ['name' => 'UI Improvements', 'description' => 'Interface enhancements'],
                    ['name' => 'Weapons & Armor', 'description' => 'Equipment mods'],
                ],
            ],
            [
                'name' => 'Baldur\'s Gate 3',
                'slug' => 'baldurs-gate-3',
                'description' => 'Mods for Baldur\'s Gate 3',
                'icon' => '⚔️',
                'color' => '#8B0000',
                'order' => 7,
                'subcategories' => [
                    ['name' => 'Class Mods', 'description' => 'New classes and subclasses'],
                    ['name' => 'Companions', 'description' => 'Custom companion mods'],
                    ['name' => 'Gameplay', 'description' => 'Game mechanics changes'],
                    ['name' => 'Visual Mods', 'description' => 'Graphical improvements'],
                    ['name' => 'Quality of Life', 'description' => 'UI and convenience mods'],
                    ['name' => 'Equipment', 'description' => 'Weapons and armor'],
                ],
            ],
            [
                'name' => 'Terraria',
                'slug' => 'terraria',
                'description' => 'Mods and content for Terraria',
                'icon' => '🌲',
                'color' => '#4CAF50',
                'order' => 8,
                'subcategories' => [
                    ['name' => 'Content Mods', 'description' => 'New items, bosses, and biomes'],
                    ['name' => 'Quality of Life', 'description' => 'Gameplay improvements'],
                    ['name' => 'Texture Packs', 'description' => 'Visual overhauls'],
                    ['name' => 'Tools', 'description' => 'Map viewers and editors'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'];
            unset($categoryData['subcategories']);

            $category = MediaCategory::create($categoryData);

            foreach ($subcategories as $index => $subcategoryData) {
                MediaSubcategory::create([
                    'media_category_id' => $category->id,
                    'name' => $subcategoryData['name'],
                    'description' => $subcategoryData['description'],
                    'order' => $index + 1,
                ]);
            }

            if ($this->command !== null) {
                $this->command->info("✓ Created category: {$category->name} with " . count($subcategories) . " subcategories");
            }
        }

        if ($this->command !== null) {
            $this->command->info('Media categories seeded successfully!');
        }
    }
}

