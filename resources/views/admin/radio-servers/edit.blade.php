@extends('admin.layouts.app')

@section('title', 'Edit Radio Server')

@section('content')
<div class="admin-header">
    <div>
        <h1 class="admin-title">Edit Radio Server</h1>
        <p class="admin-subtitle">Update {{ $radioServer->name }} configuration</p>
    </div>
    <a href="{{ route('admin.radio-servers.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<form action="{{ route('admin.radio-servers.update', $radioServer) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-header">
            <h3>Basic Information</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Server Name *</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $radioServer->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Server Type *</label>
                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    <option value="icecast" {{ old('type', $radioServer->type) === 'icecast' ? 'selected' : '' }}>Icecast</option>
                    <option value="shoutcast" {{ old('type', $radioServer->type) === 'shoutcast' ? 'selected' : '' }}>Shoutcast</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="host">Host *</label>
                        <input type="text" name="host" id="host" class="form-control @error('host') is-invalid @enderror" 
                               value="{{ old('host', $radioServer->host) }}" placeholder="localhost or IP address" required>
                        @error('host')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="port">Port *</label>
                        <input type="number" name="port" id="port" class="form-control @error('port') is-invalid @enderror" 
                               value="{{ old('port', $radioServer->port) }}" min="1" max="65535" required>
                        @error('port')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row" id="icecast-fields">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="mount_point">Mount Point</label>
                        <input type="text" name="mount_point" id="mount_point" class="form-control" 
                               value="{{ old('mount_point', $radioServer->mount_point) }}" placeholder="/stream">
                        <small class="form-text text-muted">For Icecast servers (e.g., /stream)</small>
                    </div>
                </div>
            </div>

            <div class="row" id="shoutcast-fields">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="stream_id">Stream ID</label>
                        <input type="number" name="stream_id" id="stream_id" class="form-control" 
                               value="{{ old('stream_id', $radioServer->stream_id) }}" min="1">
                        <small class="form-text text-muted">For Shoutcast servers</small>
                    </div>
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" name="ssl" id="ssl" class="form-check-input" value="1" {{ old('ssl', $radioServer->ssl) ? 'checked' : '' }}>
                <label for="ssl" class="form-check-label">Use SSL/TLS (HTTPS)</label>
            </div>

            <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $radioServer->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Active</label>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3>Admin Credentials (Optional)</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="admin_user">Admin Username</label>
                <input type="text" name="admin_user" id="admin_user" class="form-control" value="{{ old('admin_user', $radioServer->admin_user) }}">
            </div>

            <div class="form-group">
                <label for="admin_password">Admin Password</label>
                <input type="password" name="admin_password" id="admin_password" class="form-control">
                <small class="form-text text-muted">Leave blank to keep unchanged</small>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3>Docker Configuration (Optional)</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="docker_host">Docker Host</label>
                <input type="text" name="docker_host" id="docker_host" class="form-control" 
                       value="{{ old('docker_host', $radioServer->docker_host) }}" placeholder="local or remote Docker host URL">
                <small class="form-text text-muted">Use 'local' for local Docker daemon</small>
            </div>

            <div class="form-group">
                <label for="docker_container_name">Container Name</label>
                <input type="text" name="docker_container_name" id="docker_container_name" class="form-control" 
                       value="{{ old('docker_container_name', $radioServer->docker_container_name) }}" placeholder="my-radio-server">
            </div>

            <div class="form-group">
                <label for="docker_image">Docker Image</label>
                <input type="text" name="docker_image" id="docker_image" class="form-control" 
                       value="{{ old('docker_image', $radioServer->docker_image) }}" placeholder="moul/icecast:latest or mbentley/shoutcast:latest">
                <small class="form-text text-muted">Leave blank to use default image for server type</small>
            </div>

            <div class="form-check">
                <input type="checkbox" name="auto_start" id="auto_start" class="form-check-input" value="1" {{ old('auto_start', $radioServer->auto_start) ? 'checked' : '' }}>
                <label for="auto_start" class="form-check-label">Auto-start container on system boot</label>
            </div>
        </div>
    </div>

    @if($radioServer->last_error)
        <div class="alert alert-danger mt-3">
            <strong>Last Error:</strong> {{ $radioServer->last_error }}
        </div>
    @endif

    <div class="form-actions mt-3">
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Update Server
        </button>
        <a href="{{ route('admin.radio-servers.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

@endsection

@vite('resources/js/modules/admin-radio-server-edit.js')
