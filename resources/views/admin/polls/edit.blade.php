<x-admin.layouts.app>
    <x-slot name="title">Edit Poll</x-slot>

    <div class="admin-header">
        <h1>Edit Poll</h1>
        <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.polls.update', $poll) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Question *</label>
                    <input type="text" name="question" class="form-input" value="{{ old('question', $poll->question) }}" required>
                    @error('question')<p style="color: var(--color-danger); font-size: 0.875rem;">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="2">{{ old('description', $poll->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Current Options</label>
                    <div style="padding: 1rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                        @foreach($poll->options as $option)
                            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border);">
                                <span>{{ $option->option_text }}</span>
                                <span class="badge badge-gray">{{ $option->voteCount() }} votes</span>
                            </div>
                        @endforeach
                        <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Options cannot be edited after creation to preserve vote integrity.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Start Date/Time *</label>
                        <input type="datetime-local" name="starts_at" class="form-input" value="{{ old('starts_at', $poll->starts_at->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date/Time *</label>
                        <input type="datetime-local" name="ends_at" class="form-input" value="{{ old('ends_at', $poll->ends_at->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="allow_multiple" value="1" class="form-check-input" {{ old('allow_multiple', $poll->allow_multiple) ? 'checked' : '' }}>
                            <span>Allow Multiple Votes</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="show_results" value="1" class="form-check-input" {{ old('show_results', $poll->show_results) ? 'checked' : '' }}>
                            <span>Show Results Before Ending</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $poll->is_active) ? 'checked' : '' }}>
                        <span>Active</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Poll
                    </button>
                    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
