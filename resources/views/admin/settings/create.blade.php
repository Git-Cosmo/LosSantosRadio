<x-admin.layouts.app title="Create Setting">
    <div class="admin-header">
        <h1>Create Setting</h1>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Settings
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="key" class="form-label">Key</label>
                        <input type="text" id="key" name="key" value="{{ old('key') }}" class="form-input" required>
                        @error('key')
                            <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="string" {{ old('type') === 'string' ? 'selected' : '' }}>Text</option>
                            <option value="integer" {{ old('type') === 'integer' ? 'selected' : '' }}>Number</option>
                            <option value="boolean" {{ old('type') === 'boolean' ? 'selected' : '' }}>Yes/No</option>
                            <option value="json" {{ old('type') === 'json' ? 'selected' : '' }}>JSON</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="value" class="form-label">Value</label>
                    <textarea id="value" name="value" class="form-textarea" rows="3" required>{{ old('value') }}</textarea>
                    @error('value')
                        <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="group" class="form-label">Group</label>
                        <input type="text" id="group" name="group" value="{{ old('group') }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-input">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Setting
                    </button>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
