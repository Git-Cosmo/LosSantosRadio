<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use App\Models\MediaItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MediaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Generates realistic demo data for the Media Downloads Portal.
     * Creates varied content across all categories and subcategories.
     */
    public function run(): void
    {
        if ($this->command !== null) {
            $this->command->info('Seeding media items...');
        }

        // Ensure we have users to assign as uploaders
        $users = User::all();
        if ($users->isEmpty()) {
            if ($this->command !== null) {
                $this->command->warn('No users found. Creating sample users...');
            }
            $users = User::factory(5)->create();
        }

        $categories = MediaCategory::with('subcategories')->get();

        if ($categories->isEmpty()) {
            if ($this->command !== null) {
                $this->command->error('No media categories found. Run MediaCategorySeeder first.');
            }

            return;
        }

        $totalItems = 0;

        foreach ($categories as $category) {
            foreach ($category->subcategories as $subcategory) {
                $items = $this->getItemsForSubcategory($category->slug, $subcategory->name);

                foreach ($items as $itemData) {
                    $mediaItem = MediaItem::create([
                        'user_id' => $users->random()->id,
                        'media_category_id' => $category->id,
                        'media_subcategory_id' => $subcategory->id,
                        'title' => $itemData['title'],
                        'slug' => Str::slug($itemData['title']),
                        'description' => $itemData['description'],
                        'content' => $itemData['content'],
                        'version' => $itemData['version'] ?? '1.0.0',
                        'file_size' => $this->randomFileSize(),
                        'downloads_count' => rand(10, 5000),
                        'views_count' => rand(50, 10000),
                        'rating' => rand(30, 50) / 10, // 3.0 to 5.0
                        'ratings_count' => rand(5, 200),
                        'is_featured' => rand(1, 10) === 1, // 10% chance to be featured
                        'is_approved' => true,
                        'is_active' => true,
                        'published_at' => now()->subDays(rand(1, 365)),
                    ]);

                    $totalItems++;
                }
            }

            if ($this->command !== null) {
                $itemsInCategory = MediaItem::where('media_category_id', $category->id)->count();
                $this->command->info("✓ Created {$itemsInCategory} items for {$category->name}");
            }
        }

        if ($this->command !== null) {
            $this->command->info("Media items seeded successfully! Total: {$totalItems} items");
        }
    }

    /**
     * Get sample items for a specific subcategory.
     */
    private function getItemsForSubcategory(string $categorySlug, string $subcategoryName): array
    {
        return match ($categorySlug) {
            'counter-strike-2' => $this->getCS2Items($subcategoryName),
            'minecraft' => $this->getMinecraftItems($subcategoryName),
            'gta-v' => $this->getGTAItems($subcategoryName),
            'skyrim' => $this->getSkyrimItems($subcategoryName),
            'cyberpunk-2077' => $this->getCyberpunkItems($subcategoryName),
            'starfield' => $this->getStarfieldItems($subcategoryName),
            'baldurs-gate-3' => $this->getBG3Items($subcategoryName),
            'terraria' => $this->getTerrariaItems($subcategoryName),
            default => [],
        };
    }

    private function getCS2Items(string $subcategory): array
    {
        return match ($subcategory) {
            'Maps' => [
                ['title' => 'de_aztec_remake', 'description' => 'Classic Aztec map remade for CS2 with improved visuals', 'content' => "Installation:\n1. Extract to csgo/maps/\n2. Restart game\n3. Use console: map de_aztec_remake", 'version' => '2.1.0'],
                ['title' => 'aim_redline', 'description' => 'Fast-paced aim training map with multiple modes', 'content' => "Features:\n- Multiple aim scenarios\n- Bot support\n- FPS optimized", 'version' => '1.5.2'],
                ['title' => 'surf_utopia_v3', 'description' => 'Popular surfing map with stunning visuals', 'content' => 'A community favorite surf map featuring 15 stages and beautiful scenery.', 'version' => '3.0.1'],
            ],
            'Skins' => [
                ['title' => 'AK-47 Neon Revolution', 'description' => 'Cyberpunk-themed skin for AK-47', 'content' => 'High-quality 4K skin with animated neon effects.', 'version' => '1.0.0'],
                ['title' => 'M4A4 Dragon King', 'description' => 'Asian-inspired dragon skin for M4A4', 'content' => 'Installation: Place in csgo/materials/models/weapons/', 'version' => '1.2.0'],
            ],
            'HUD Mods' => [
                ['title' => 'Minimal Clean HUD', 'description' => 'Minimalist HUD for competitive play', 'content' => "Features:\n- Reduced clutter\n- Better visibility\n- Tournament approved", 'version' => '2.0.0'],
            ],
            'Sound Mods' => [
                ['title' => 'Realistic Weapon Sounds', 'description' => 'High-fidelity weapon sound replacements', 'content' => 'Recorded from real firearms for authentic experience.', 'version' => '1.3.0'],
            ],
            'Server Plugins' => [
                ['title' => 'Advanced Admin Tools', 'description' => 'Complete server administration plugin', 'content' => 'Features: Ban management, votekick, player stats, and more.', 'version' => '4.2.1'],
            ],
            default => [],
        };
    }

    private function getMinecraftItems(string $subcategory): array
    {
        return match ($subcategory) {
            'Mods' => [
                ['title' => 'Biomes O\' Plenty', 'description' => 'Adds 90+ new biomes to explore', 'content' => 'Requires Forge. Adds diverse biomes with unique terrain, plants, and mobs.', 'version' => '1.20.1'],
                ['title' => 'Create Mod', 'description' => 'Advanced automation and engineering', 'content' => 'Build complex machines, factories, and contraptions.', 'version' => '0.5.1'],
                ['title' => 'JourneyMap', 'description' => 'Real-time minimap and world mapping', 'content' => "Features:\n- Real-time mapping\n- Waypoints\n- Mob radar\n- Works in multiplayer", 'version' => '5.9.7'],
            ],
            'Texture Packs' => [
                ['title' => 'Faithful 64x', 'description' => 'Higher resolution vanilla textures', 'content' => 'Maintains vanilla style with increased detail. Compatible with all versions.', 'version' => '1.20'],
                ['title' => 'BSL Shaders', 'description' => 'Beautiful shader pack with realistic lighting', 'content' => 'Requires OptiFine or Iris. Amazing visuals with good performance.', 'version' => '8.2.04'],
            ],
            'Maps' => [
                ['title' => 'SkyBlock Paradise', 'description' => 'Ultimate skyblock adventure map', 'content' => '100+ custom islands, quests, and challenges. Supports 1-4 players.', 'version' => '2.1.0'],
                ['title' => 'The Dropper 2', 'description' => 'Sequel to the popular dropper map', 'content' => '20 new levels with increasing difficulty. Test your reflexes!', 'version' => '1.0.0'],
            ],
            'Data Packs' => [
                ['title' => 'Terralith', 'description' => 'Overworld terrain overhaul', 'content' => 'Vanilla-friendly world generation with 100+ new biomes.', 'version' => '2.4.10'],
            ],
            'Skins' => [
                ['title' => 'HD Skins Pack Vol.1', 'description' => 'Collection of 50 high-quality player skins', 'content' => 'Includes medieval, sci-fi, and modern themes.', 'version' => '1.0.0'],
            ],
            'Server Plugins' => [
                ['title' => 'EssentialsX', 'description' => 'Essential server commands and features', 'content' => 'Complete server toolkit with economy, homes, warps, and more.', 'version' => '2.20.1'],
            ],
            default => [],
        };
    }

    private function getGTAItems(string $subcategory): array
    {
        return match ($subcategory) {
            'Scripts' => [
                ['title' => 'Enhanced Native Trainer', 'description' => 'Advanced trainer with 200+ options', 'content' => "Installation:\n1. Install ScriptHookV\n2. Copy files to scripts folder\n3. Press F4 in game", 'version' => '4.7.2'],
                ['title' => 'NaturalVision Evolved', 'description' => 'Complete visual overhaul mod', 'content' => 'Transform GTA V into photorealistic masterpiece. Requires high-end PC.', 'version' => '3.0'],
            ],
            'Vehicles' => [
                ['title' => '2023 Ferrari SF90', 'description' => 'Highly detailed Ferrari SF90 Stradale', 'content' => "Features:\n- High poly model\n- Custom handling\n- Working lights\n- Add-on compatible", 'version' => '1.2.0'],
                ['title' => 'Lamborghini Aventador SVJ', 'description' => 'Track-focused supercar', 'content' => 'Perfect handling, stunning visuals, and authentic sound.', 'version' => '2.0.0'],
            ],
            'Maps' => [
                ['title' => 'Liberty City in Los Santos', 'description' => 'GTA IV map imported to GTA V', 'content' => 'Complete Liberty City playable in GTA V. Requires 20GB free space.', 'version' => '1.0.0'],
            ],
            'Weapons' => [
                ['title' => 'Modern Warfare Weapons Pack', 'description' => '15 weapons from COD MW2', 'content' => 'High quality models with animations and sounds.', 'version' => '1.5.0'],
            ],
            'Peds' => [
                ['title' => 'HD Player Models', 'description' => 'Enhanced character textures', 'content' => '2K and 4K textures for all main characters.', 'version' => '1.0.0'],
            ],
            'Graphics Mods' => [
                ['title' => 'QuantV 3.0', 'description' => 'Ultimate graphics enhancement', 'content' => 'Complete visual overhaul with weather system and lighting.', 'version' => '3.0.2'],
            ],
            default => [],
        };
    }

    private function getSkyrimItems(string $subcategory): array
    {
        return match ($subcategory) {
            'Gameplay Mods' => [
                ['title' => 'Ordinator - Perks of Skyrim', 'description' => 'Complete perk overhaul with 400+ new perks', 'content' => 'Revolutionizes character building. Compatible with all DLCs.', 'version' => '9.31.0'],
                ['title' => 'Combat Gameplay Overhaul', 'description' => 'Adds dodge rolls, grip changes, and 1st person leaning', 'content' => 'Requires SKSE. Makes combat more dynamic and fun.', 'version' => '1.1.3'],
            ],
            'Quests & Adventures' => [
                ['title' => 'The Forgotten City', 'description' => 'Award-winning mystery adventure', 'content' => '8+ hours of gameplay with multiple endings. Fully voiced.', 'version' => '1.3.0'],
            ],
            'Graphics & Visuals' => [
                ['title' => 'Skyrim 2020 Parallax', 'description' => '8K texture overhaul with parallax', 'content' => 'Ultra high quality textures for landscapes, architecture, and more.', 'version' => '3.5.2'],
            ],
            'Armor & Weapons' => [
                ['title' => 'Immersive Armors', 'description' => '55 new armor sets lore-friendly', 'content' => 'Integrates naturally into the game world. All craftable.', 'version' => '8.1.0'],
            ],
            'Followers & NPCs' => [
                ['title' => 'Inigo', 'description' => 'Fully voiced khajiit follower', 'content' => '7000+ lines of dialogue, dynamic personality, and unique questline.', 'version' => '2.4c'],
            ],
            'Utilities' => [
                ['title' => 'SkyUI', 'description' => 'Complete UI overhaul', 'content' => 'Requires SKSE. Essential mod for better inventory and menu management.', 'version' => '5.2SE'],
            ],
            default => [],
        };
    }

    private function getCyberpunkItems(string $subcategory): array
    {
        return match ($subcategory) {
            'Gameplay Mods' => [
                ['title' => 'Cyber Engine Tweaks', 'description' => 'Framework for advanced modding', 'content' => 'Essential tool for other mods. Adds console, debug menu, and more.', 'version' => '1.27.1'],
            ],
            'Graphics Mods' => [
                ['title' => 'Nova City RT', 'description' => 'Enhanced ray tracing preset', 'content' => 'Improved RT quality and performance. Requires RTX GPU.', 'version' => '2.1.0'],
            ],
            'Clothing & Armor' => [
                ['title' => 'Custom Legendary Sets', 'description' => '20 new legendary outfit sets', 'content' => 'Unique designs inspired by cyberpunk media.', 'version' => '1.0.0'],
            ],
            'Vehicles' => [
                ['title' => 'Improved Vehicle Handling', 'description' => 'More realistic car physics', 'content' => 'Makes driving feel more responsive and realistic.', 'version' => '1.5.0'],
            ],
            'Weapons' => [
                ['title' => 'Weapon Rebalance Project', 'description' => 'Complete weapon balance overhaul', 'content' => 'Makes all weapons viable and unique.', 'version' => '3.2.0'],
            ],
            'Utilities' => [
                ['title' => 'Native Settings UI', 'description' => 'Better mod configuration menu', 'content' => 'In-game settings for supported mods.', 'version' => '1.96'],
            ],
            default => [],
        };
    }

    private function getStarfieldItems(string $subcategory): array
    {
        return match ($subcategory) {
            'Gameplay Mods' => [
                ['title' => 'StarUI Inventory', 'description' => 'Improved inventory interface', 'content' => 'Better sorting, filtering, and navigation.', 'version' => '2.1.0'],
            ],
            'Ship Mods' => [
                ['title' => 'Custom Ship Pack Vol.1', 'description' => '10 new ship designs', 'content' => 'Unique ships with custom parts and colors.', 'version' => '1.0.0'],
            ],
            'Outpost Mods' => [
                ['title' => 'Expanded Outpost Building', 'description' => '100+ new building pieces', 'content' => 'More variety for your outposts.', 'version' => '1.3.0'],
            ],
            'Graphics' => [
                ['title' => 'Enhanced Space Visuals', 'description' => 'Better space environments', 'content' => 'Improved nebulas, planets, and stars.', 'version' => '1.0.0'],
            ],
            'UI Improvements' => [
                ['title' => 'Better HUD', 'description' => 'Cleaner and more informative HUD', 'content' => 'Customizable HUD elements.', 'version' => '1.2.0'],
            ],
            'Weapons & Armor' => [
                ['title' => 'Legendary Loot Overhaul', 'description' => 'Better legendary weapon effects', 'content' => 'Makes legendaries more interesting and powerful.', 'version' => '1.0.0'],
            ],
            default => [],
        };
    }

    private function getBG3Items(string $subcategory): array
    {
        return match ($subcategory) {
            'Class Mods' => [
                ['title' => '5e Spells Expansion', 'description' => '50+ new spells from D&D 5e', 'content' => 'Faithful adaptations of missing 5e spells.', 'version' => '1.8.0'],
            ],
            'Companions' => [
                ['title' => 'Astarion Relationship Expansion', 'description' => 'More dialogue and romance options', 'content' => "Expands Astarion's storyline with new scenes.", 'version' => '1.2.0'],
            ],
            'Gameplay' => [
                ['title' => 'Improved AI', 'description' => 'Smarter enemy tactics', 'content' => 'Enemies use better strategies and positioning.', 'version' => '2.0.0'],
            ],
            'Visual Mods' => [
                ['title' => 'Enhanced Character Textures', 'description' => '4K character texture pack', 'content' => 'High resolution textures for all races.', 'version' => '1.0.0'],
            ],
            'Quality of Life' => [
                ['title' => 'Fast XP', 'description' => 'Adjustable XP multiplier', 'content' => 'Configure XP gain from 0.5x to 5x.', 'version' => '1.0.0'],
            ],
            'Equipment' => [
                ['title' => 'Legendary Items Pack', 'description' => '25 new legendary items', 'content' => 'Unique items with special effects.', 'version' => '1.1.0'],
            ],
            default => [],
        };
    }

    private function getTerrariaItems(string $subcategory): array
    {
        return match ($subcategory) {
            'Content Mods' => [
                ['title' => 'Calamity Mod', 'description' => 'Massive content expansion', 'content' => '2000+ items, 24 bosses, 5 new biomes. Post-Moon Lord content.', 'version' => '2.0.3.5'],
                ['title' => 'Thorium Mod', 'description' => 'Over 2000 items and 11 bosses', 'content' => 'Adds new classes, instruments, and healer role.', 'version' => '1.7.3.2'],
            ],
            'Quality of Life' => [
                ['title' => 'Magic Storage', 'description' => 'Ultimate storage solution', 'content' => 'Crafting and storage system with search functionality.', 'version' => '0.6.0'],
            ],
            'Texture Packs' => [
                ['title' => 'Calamity Texture Pack', 'description' => 'HD textures for Calamity Mod', 'content' => 'High resolution sprites for all Calamity content.', 'version' => '1.0.0'],
            ],
            'Tools' => [
                ['title' => 'TEdit', 'description' => 'World editor and map viewer', 'content' => 'Edit your worlds, view maps, and more.', 'version' => '4.0.3'],
            ],
            default => [],
        };
    }

    /**
     * Generate random file size string.
     */
    private function randomFileSize(): string
    {
        $sizeInMB = rand(1, 500);

        if ($sizeInMB < 10) {
            return $sizeInMB.'.'.rand(0, 9).' MB';
        }

        return $sizeInMB.' MB';
    }
}
