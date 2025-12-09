@extends('admin.layouts.app')

@section('title', 'Radio Servers')

@section('content')
<div class="admin-header">
    <div>
        <h1 class="admin-title">Radio Servers</h1>
        <p class="admin-subtitle">Manage Icecast and Shoutcast servers with Docker orchestration</p>
    </div>
    <a href="{{ route('admin.radio-servers.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Add Server
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($servers->isEmpty())
            <div class="empty-state">
                <i class="fas fa-server" style="font-size: 3rem; opacity: 0.3;"></i>
                <h3>No Radio Servers</h3>
                <p>Get started by adding your first Icecast or Shoutcast server.</p>
                <a href="{{ route('admin.radio-servers.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Add Server
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Host:Port</th>
                            <th>Status</th>
                            <th>Docker</th>
                            <th>Last Check</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servers as $server)
                            <tr>
                                <td>
                                    <strong>{{ $server->name }}</strong>
                                    @if(!$server->is_active)
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ ucfirst($server->type) }}
                                    </span>
                                </td>
                                <td>
                                    <code>{{ $server->host }}:{{ $server->port }}</code>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $server->status === 'running' ? 'success' : ($server->status === 'error' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($server->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($server->docker_container_name)
                                        <i class="fab fa-docker text-primary"></i>
                                        {{ $server->docker_container_name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($server->last_check_at)
                                        <small>{{ $server->last_check_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($server->docker_container_name)
                                            @if($server->status === 'running')
                                                <button onclick="controlServer({{ $server->id }}, 'stop')" 
                                                        class="btn btn-sm btn-warning" 
                                                        title="Stop Container">
                                                    <i class="fas fa-stop"></i>
                                                </button>
                                                <button onclick="controlServer({{ $server->id }}, 'restart')" 
                                                        class="btn btn-sm btn-info" 
                                                        title="Restart Container">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            @else
                                                <button onclick="controlServer({{ $server->id }}, 'start')" 
                                                        class="btn btn-sm btn-success" 
                                                        title="Start Container">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @endif
                                        @endif
                                        <button onclick="testServer({{ $server->id }})" 
                                                class="btn btn-sm btn-secondary" 
                                                title="Test Connection">
                                            <i class="fas fa-plug"></i>
                                        </button>
                                        <a href="{{ route('admin.radio-servers.edit', $server) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.radio-servers.destroy', $server) }}" 
                                              method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this server?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection

@vite('resources/js/modules/admin-radio-servers.js')
