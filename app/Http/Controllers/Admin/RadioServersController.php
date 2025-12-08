<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioServer;
use App\Services\RadioServerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadioServersController extends Controller
{
    public function __construct(
        protected RadioServerService $radioServerService
    ) {}

    /**
     * Display a listing of radio servers.
     */
    public function index(): View
    {
        $servers = $this->radioServerService->getAllServers();

        return view('admin.radio-servers.index', compact('servers'));
    }

    /**
     * Show the form for creating a new radio server.
     */
    public function create(): View
    {
        return view('admin.radio-servers.create');
    }

    /**
     * Store a newly created radio server.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:icecast,shoutcast',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'mount_point' => 'nullable|string|max:255',
            'stream_id' => 'nullable|integer',
            'admin_user' => 'nullable|string|max:255',
            'admin_password' => 'nullable|string|max:255',
            'ssl' => 'boolean',
            'is_active' => 'boolean',
            'auto_start' => 'boolean',
            'docker_host' => 'nullable|string|max:255',
            'docker_container_name' => 'nullable|string|max:255',
            'docker_image' => 'nullable|string|max:255',
        ]);

        $server = $this->radioServerService->createServer($validated);

        return redirect()
            ->route('admin.radio-servers.index')
            ->with('success', "Radio server '{$server->name}' created successfully!");
    }

    /**
     * Display the specified radio server.
     */
    public function show(RadioServer $radioServer): View
    {
        $status = $this->radioServerService->getServerStatus($radioServer);

        return view('admin.radio-servers.show', compact('radioServer', 'status'));
    }

    /**
     * Show the form for editing the specified radio server.
     */
    public function edit(RadioServer $radioServer): View
    {
        return view('admin.radio-servers.edit', compact('radioServer'));
    }

    /**
     * Update the specified radio server.
     */
    public function update(Request $request, RadioServer $radioServer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:icecast,shoutcast',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'mount_point' => 'nullable|string|max:255',
            'stream_id' => 'nullable|integer',
            'admin_user' => 'nullable|string|max:255',
            'admin_password' => 'nullable|string|max:255',
            'ssl' => 'boolean',
            'is_active' => 'boolean',
            'auto_start' => 'boolean',
            'docker_host' => 'nullable|string|max:255',
            'docker_container_name' => 'nullable|string|max:255',
            'docker_image' => 'nullable|string|max:255',
        ]);

        $this->radioServerService->updateServer($radioServer, $validated);

        return redirect()
            ->route('admin.radio-servers.index')
            ->with('success', "Radio server '{$radioServer->name}' updated successfully!");
    }

    /**
     * Remove the specified radio server.
     */
    public function destroy(RadioServer $radioServer): RedirectResponse
    {
        $name = $radioServer->name;
        $this->radioServerService->deleteServer($radioServer);

        return redirect()
            ->route('admin.radio-servers.index')
            ->with('success', "Radio server '{$name}' deleted successfully!");
    }

    /**
     * Test server connection.
     */
    public function test(RadioServer $radioServer)
    {
        $result = $this->radioServerService->testConnection($radioServer);

        return response()->json($result);
    }

    /**
     * Start Docker container for server.
     */
    public function start(RadioServer $radioServer)
    {
        $result = $this->radioServerService->startDockerContainer($radioServer);

        return response()->json($result);
    }

    /**
     * Stop Docker container for server.
     */
    public function stop(RadioServer $radioServer)
    {
        $result = $this->radioServerService->stopDockerContainer($radioServer);

        return response()->json($result);
    }

    /**
     * Restart Docker container for server.
     */
    public function restart(RadioServer $radioServer)
    {
        $result = $this->radioServerService->restartDockerContainer($radioServer);

        return response()->json($result);
    }

    /**
     * Get server status.
     */
    public function status(RadioServer $radioServer)
    {
        $result = $this->radioServerService->getServerStatus($radioServer);

        return response()->json($result);
    }
}
