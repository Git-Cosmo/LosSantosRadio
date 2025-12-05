<x-admin.layouts.app title="Edit Setting">
    <div class="admin-header">
        <h1>Edit Setting: {{ $setting->key }}</h1>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Settings
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.update', $setting) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Key</label>
                    <input type="text" value="{{ $setting->key }}" class="form-input" disabled>
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="string" {{ $setting->type === 'string' ? 'selected' : '' }}>Text</option>
                        <option value="integer" {{ $setting->type === 'integer' ? 'selected' : '' }}>Number</option>
                        <option value="boolean" {{ $setting->type === 'boolean' ? 'selected' : '' }}>Yes/No</option>
                        <option value="json" {{ $setting->type === 'json' ? 'selected' : '' }}>JSON</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="value" class="form-label">Value</label>
                    <textarea id="value" name="value" class="form-textarea" rows="3" required>{{ old('value', $setting->value) }}</textarea>
                    @error('value')
                        <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="group" class="form-label">Group</label>
                        <input type="text" id="group" name="group" value="{{ old('group', $setting->group) }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" id="description" name="description" value="{{ old('description', $setting->description) }}" class="form-input">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
