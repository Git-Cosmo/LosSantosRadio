<?php

namespace App\Console\Commands;

use App\Services\MediaContentService;
use Illuminate\Console\Command;

class ImportMediaContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:import 
                            {--source= : Specific source to import from (minecraft, cs2, gta5, skyrim, all)}
                            {--limit=20 : Number of items to import per source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import media content from various free sources (CurseForge, Steam Workshop, etc.)';

    /**
     * Execute the console command.
     */
    public function handle(MediaContentService $contentService): int
    {
        $source = $this->option('source') ?? 'all';
        $limit = (int) $this->option('limit');

        $this->info('Starting media content import...');

        $results = [];

        switch ($source) {
            case 'minecraft':
                $this->info('Importing Minecraft mods from CurseForge...');
                $results['minecraft'] = $contentService->importCurseForgeMinecraftMods($limit);
                break;

            case 'cs2':
                $this->info('Importing CS2 workshop items...');
                $results['cs2'] = $contentService->importSteamWorkshopCS2($limit);
                break;

            case 'gta5':
                $this->info('Importing GTA V mods...');
                $results['gta5'] = $contentService->importGTA5Mods($limit);
                break;

            case 'skyrim':
                $this->info('Importing Skyrim mods from Nexus Mods...');
                $results['skyrim'] = $contentService->importNexusModsSkyrim($limit);
                break;

            case 'all':
            default:
                $this->info('Importing from all sources...');
                $results = $contentService->importAll();
                break;
        }

        $this->newLine();
        $this->info('Import Results:');
        $this->table(
            ['Source', 'Items Imported'],
            collect($results)->map(fn($count, $source) => [$source, $count])->values()
        );

        $total = array_sum($results);
        $this->newLine();
        $this->info("Total items imported: {$total}");

        return self::SUCCESS;
    }
}

