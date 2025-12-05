<x-admin.layouts.app title="Edit Song Request">
    <div class="admin-header">
        <h1>Edit Song Request</h1>
        <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.requests.update', $request) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Song Title</label>
                        <input type="text" value="{{ $request->song_title }}" class="form-input" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Artist</label>
                        <input type="text" value="{{ $request->song_artist }}" class="form-input" disabled>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $request->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="queue_order" class="form-label">Queue Position</label>
                        <input type="number" id="queue_order" name="queue_order" value="{{ $request->queue_order }}" class="form-input" min="0">
                        <small style="color: var(--color-text-muted);">Lower numbers play first</small>
                    </div>
                </div>

                <div class="grid grid-cols-3">
                    <div class="form-group">
                        <label class="form-label">Requester</label>
                        <input type="text" value="{{ $request->user?->name ?? 'Guest' }}" class="form-input" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">IP Address</label>
                        <input type="text" value="{{ $request->ip_address ?? 'N/A' }}" class="form-input" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Guest Email</label>
                        <input type="text" value="{{ $request->guest_email ?? 'N/A' }}" class="form-input" disabled>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
