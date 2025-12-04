<?php

namespace App\Console\Commands;

use App\Services\AzuraCastService;
use App\Services\IcecastService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'health:check
                            {--detailed : Show detailed information for each check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run health checks for the application and external services';

    /**
     * Execute the console command.
     */
    public function handle(AzuraCastService $azuraCast, IcecastService $icecast): int
    {
        $this->info('Running health checks...');
        $this->newLine();

        $checks = [
            'Environment' => $this->checkEnvironment(),
            'Database' => $this->checkDatabase(),
            'Cache' => $this->checkCache(),
            'AzuraCast API' => $this->checkAzuraCast($azuraCast),
            'Icecast' => $this->checkIcecast($icecast),
            'Storage' => $this->checkStorage(),
            'Required Extensions' => $this->checkExtensions(),
        ];

        $failures = 0;
        $tableData = [];

        foreach ($checks as $name => $result) {
            $status = $result['status'] ? '✓' : '✗';
            $statusColor = $result['status'] ? 'green' : 'red';

            $tableData[] = [
                "<fg={$statusColor}>{$status}</>",
                $name,
                $result['message'],
            ];

            if (! $result['status']) {
                $failures++;
            }

            if ($this->option('detailed') && isset($result['details'])) {
                foreach ($result['details'] as $detail) {
                    $tableData[] = ['', "  └─ {$detail['name']}", $detail['value']];
                }
            }
        }

        $this->table(['Status', 'Check', 'Message'], $tableData);

        $this->newLine();

        if ($failures > 0) {
            $this->error("{$failures} health check(s) failed!");

            return Command::FAILURE;
        }

        $this->info('All health checks passed!');

        return Command::SUCCESS;
    }

    /**
     * Check environment configuration.
     */
    protected function checkEnvironment(): array
    {
        $required = [
            'APP_KEY' => env('APP_KEY'),
            'APP_URL' => env('APP_URL'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'AZURACAST_BASE_URL' => env('AZURACAST_BASE_URL'),
            'AZURACAST_API_KEY' => env('AZURACAST_API_KEY'),
        ];

        $missing = [];
        foreach ($required as $key => $value) {
            if (empty($value)) {
                $missing[] = $key;
            }
        }

        $details = [];
        foreach ($required as $key => $value) {
            $details[] = [
                'name' => $key,
                'value' => empty($value) ? '<fg=red>Not set</>' : '<fg=green>Set</>',
            ];
        }

        if (count($missing) > 0) {
            return [
                'status' => false,
                'message' => 'Missing: '.implode(', ', $missing),
                'details' => $details,
            ];
        }

        return [
            'status' => true,
            'message' => 'All required variables set',
            'details' => $details,
        ];
    }

    /**
     * Check database connection.
     */
    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            if (DB::connection()->getDriverName() === 'sqlite') {
                $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
            } else {
                $tables = DB::select('SHOW TABLES');
            }

            return [
                'status' => true,
                'message' => 'Connected ('.count($tables).' tables)',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Connection failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connection.
     */
    protected function checkCache(): array
    {
        try {
            $key = 'health-check-'.time();
            Cache::put($key, 'ok', 10);
            $value = Cache::get($key);
            Cache::forget($key);

            if ($value === 'ok') {
                return [
                    'status' => true,
                    'message' => 'Cache is working ('.config('cache.default').')',
                ];
            }

            return [
                'status' => false,
                'message' => 'Cache read/write failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Cache error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check AzuraCast API connection.
     */
    protected function checkAzuraCast(AzuraCastService $azuraCast): array
    {
        $baseUrl = config('services.azuracast.base_url');

        if (empty($baseUrl) || $baseUrl === 'https://your-azuracast-instance.com') {
            return [
                'status' => false,
                'message' => 'AzuraCast URL not configured',
            ];
        }

        try {
            $nowPlaying = $azuraCast->getNowPlaying();

            if ($nowPlaying) {
                return [
                    'status' => true,
                    'message' => 'Connected to AzuraCast',
                ];
            }

            return [
                'status' => false,
                'message' => 'No response from AzuraCast',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'AzuraCast error: '.substr($e->getMessage(), 0, 50),
            ];
        }
    }

    /**
     * Check Icecast connection.
     */
    protected function checkIcecast(IcecastService $icecast): array
    {
        $host = config('services.icecast.host');

        if (empty($host) || $host === 'localhost') {
            return [
                'status' => false,
                'message' => 'Icecast not configured (using default)',
            ];
        }

        try {
            $status = $icecast->getStatus();

            if ($status) {
                return [
                    'status' => true,
                    'message' => 'Connected to Icecast',
                ];
            }

            return [
                'status' => false,
                'message' => 'No response from Icecast',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Icecast error: '.substr($e->getMessage(), 0, 50),
            ];
        }
    }

    /**
     * Check storage accessibility.
     */
    protected function checkStorage(): array
    {
        $paths = [
            'storage/app' => storage_path('app'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $issues = [];
        foreach ($paths as $name => $path) {
            if (! is_writable($path)) {
                $issues[] = $name;
            }
        }

        if (count($issues) > 0) {
            return [
                'status' => false,
                'message' => 'Not writable: '.implode(', ', $issues),
            ];
        }

        return [
            'status' => true,
            'message' => 'All storage paths writable',
        ];
    }

    /**
     * Check required PHP extensions.
     */
    protected function checkExtensions(): array
    {
        $required = ['pdo', 'json', 'curl', 'openssl', 'mbstring'];
        $missing = [];

        foreach ($required as $ext) {
            if (! extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }

        if (count($missing) > 0) {
            return [
                'status' => false,
                'message' => 'Missing: '.implode(', ', $missing),
            ];
        }

        return [
            'status' => true,
            'message' => 'All required extensions loaded',
        ];
    }
}
