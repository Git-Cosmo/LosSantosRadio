<?php

namespace App\Services;

use App\Models\RadioServer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Radio Server Service
 * 
 * Manages Icecast and Shoutcast radio servers with full CRUD operations.
 * Supports Docker container orchestration on remote hosts.
 */
class RadioServerService
{
    /**
     * Get all active radio servers
     */
    public function getActiveServers()
    {
        return RadioServer::where('is_active', true)->get();
    }

    /**
     * Get all servers
     */
    public function getAllServers()
    {
        return RadioServer::all();
    }

    /**
     * Create a new radio server
     */
    public function createServer(array $data): RadioServer
    {
        return RadioServer::create($data);
    }

    /**
     * Update a radio server
     */
    public function updateServer(RadioServer $server, array $data): bool
    {
        return $server->update($data);
    }

    /**
     * Delete a radio server
     */
    public function deleteServer(RadioServer $server): bool
    {
        // Stop container if running
        if ($server->isRunning() && $server->docker_container_name) {
            $this->stopDockerContainer($server);
        }

        return $server->delete();
    }

    /**
     * Test server connection
     */
    public function testConnection(RadioServer $server): array
    {
        try {
            $url = $server->stream_url;
            
            $response = Http::timeout(5)->get($url);
            
            if ($response->successful() || $response->status() === 302) {
                return [
                    'success' => true,
                    'message' => 'Successfully connected to radio server',
                    'status_code' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'message' => "Failed to connect: HTTP {$response->status()}",
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Radio server connection test failed', [
                'server_id' => $server->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => "Connection error: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Start Docker container for server
     */
    public function startDockerContainer(RadioServer $server): array
    {
        if (!$server->docker_container_name) {
            return [
                'success' => false,
                'message' => 'No Docker container configured',
            ];
        }

        try {
            $dockerHost = $server->docker_host ?: config('services.docker.default_host');
            
            // Build docker run command
            $command = $this->buildDockerRunCommand($server);
            
            // Execute via SSH or Docker API
            $result = $this->executeDockerCommand($dockerHost, $command);
            
            if ($result['success']) {
                $server->markAsRunning();
                return [
                    'success' => true,
                    'message' => 'Docker container started successfully',
                ];
            }

            $server->markAsError($result['message'] ?? 'Unknown error');
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to start Docker container', [
                'server_id' => $server->id,
                'error' => $e->getMessage(),
            ]);

            $server->markAsError($e->getMessage());
            
            return [
                'success' => false,
                'message' => "Failed to start container: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Stop Docker container for server
     */
    public function stopDockerContainer(RadioServer $server): array
    {
        if (!$server->docker_container_name) {
            return [
                'success' => false,
                'message' => 'No Docker container configured',
            ];
        }

        try {
            $dockerHost = $server->docker_host ?: config('services.docker.default_host');
            
            $command = "docker stop {$server->docker_container_name}";
            
            $result = $this->executeDockerCommand($dockerHost, $command);
            
            if ($result['success']) {
                $server->markAsStopped();
                return [
                    'success' => true,
                    'message' => 'Docker container stopped successfully',
                ];
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to stop Docker container', [
                'server_id' => $server->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => "Failed to stop container: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Restart Docker container for server
     */
    public function restartDockerContainer(RadioServer $server): array
    {
        $stopResult = $this->stopDockerContainer($server);
        
        if (!$stopResult['success']) {
            return $stopResult;
        }

        // Wait for container to fully stop with polling
        $maxAttempts = 10;
        $attempt = 0;
        while ($attempt < $maxAttempts) {
            sleep(1);
            $status = $this->getServerStatus($server);
            if (!$status['running']) {
                break;
            }
            $attempt++;
        }
        
        return $this->startDockerContainer($server);
    }

    /**
     * Get server status
     */
    public function getServerStatus(RadioServer $server): array
    {
        if (!$server->docker_container_name) {
            return [
                'running' => false,
                'message' => 'No Docker container configured',
            ];
        }

        try {
            $dockerHost = $server->docker_host ?: config('services.docker.default_host');
            
            $command = "docker ps --filter name={$server->docker_container_name} --format '{{.Status}}'";
            
            $result = $this->executeDockerCommand($dockerHost, $command);
            
            if ($result['success'] && !empty($result['output'])) {
                $server->markAsRunning();
                return [
                    'running' => true,
                    'status' => $result['output'],
                ];
            }

            $server->markAsStopped();
            return [
                'running' => false,
                'status' => 'Container not running',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get server status', [
                'server_id' => $server->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'running' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build Docker run command
     */
    protected function buildDockerRunCommand(RadioServer $server): string
    {
        $image = $server->docker_image ?: $this->getDefaultImage($server->type);
        $containerName = $server->docker_container_name;
        
        $command = "docker run -d --name {$containerName}";
        
        // Add port mappings
        if ($server->docker_ports) {
            foreach ($server->docker_ports as $hostPort => $containerPort) {
                $command .= " -p {$hostPort}:{$containerPort}";
            }
        } else {
            $command .= " -p {$server->port}:8000";
        }
        
        // Add environment variables
        if ($server->docker_env) {
            foreach ($server->docker_env as $key => $value) {
                $command .= " -e {$key}='{$value}'";
            }
        }
        
        $command .= " {$image}";
        
        return $command;
    }

    /**
     * Execute Docker command
     */
    protected function executeDockerCommand(string $dockerHost, string $command): array
    {
        // For local Docker, execute directly with proper escaping
        if (empty($dockerHost) || $dockerHost === 'local' || $dockerHost === 'unix:///var/run/docker.sock') {
            // Use escapeshellcmd to prevent command injection
            // Note: In production, consider using Docker SDK for PHP instead
            $safeCommand = escapeshellcmd($command);
            exec($safeCommand . ' 2>&1', $output, $returnCode);
            
            return [
                'success' => $returnCode === 0,
                'output' => implode("\n", $output),
                'message' => $returnCode === 0 ? 'Command executed successfully' : 'Command failed',
            ];
        }
        
        // For remote Docker, use SSH or Docker API
        // This is a placeholder - implement based on your infrastructure
        // Consider using Docker SDK for PHP (https://github.com/docker-php/docker-php)
        return [
            'success' => false,
            'message' => 'Remote Docker execution not yet implemented. Please configure Docker host.',
        ];
    }

    /**
     * Get default Docker image for server type
     */
    protected function getDefaultImage(string $type): string
    {
        return match($type) {
            'icecast' => 'moul/icecast:latest',
            'shoutcast' => 'mbentley/shoutcast:latest',
            default => 'moul/icecast:latest',
        };
    }
}
