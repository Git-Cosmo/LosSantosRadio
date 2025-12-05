<x-admin.layouts.app>
    <x-slot name="title">Create Poll</x-slot>

    <div class="admin-header">
        <h1>Create Poll</h1>
        <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.polls.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Question *</label>
                    <input type="text" name="question" class="form-input" value="{{ old('question') }}" placeholder="What's your favorite music genre?" required>
                    @error('question')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="2" placeholder="Optional description for the poll">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Options * (minimum 2)</label>
                    <div id="options-container">
                        <input type="text" name="options[]" class="form-input" style="margin-bottom: 0.5rem;" placeholder="Option 1" required>
                        <input type="text" name="options[]" class="form-input" style="margin-bottom: 0.5rem;" placeholder="Option 2" required>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addOption()">
                        <i class="fas fa-plus"></i> Add Option
                    </button>
                    @error('options')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Start Date/Time *</label>
                        <input type="datetime-local" name="starts_at" class="form-input" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date/Time *</label>
                        <input type="datetime-local" name="ends_at" class="form-input" value="{{ old('ends_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="allow_multiple" value="1" class="form-check-input" {{ old('allow_multiple') ? 'checked' : '' }}>
                            <span>Allow Multiple Votes</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="show_results" value="1" class="form-check-input" {{ old('show_results', true) ? 'checked' : '' }}>
                            <span>Show Results Before Ending</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Poll
                    </button>
                    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let optionCount = 2;
        function addOption() {
            optionCount++;
            const container = document.getElementById('options-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[]';
            input.className = 'form-input';
            input.style.marginBottom = '0.5rem';
            input.placeholder = 'Option ' + optionCount;
            container.appendChild(input);
        }
    </script>
</x-admin.layouts.app>
