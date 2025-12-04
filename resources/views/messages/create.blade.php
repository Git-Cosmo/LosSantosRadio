<x-layouts.app :title="'New Message'">
    <div class="card">
        <div class="card-header" style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('messages.index') }}" style="color: var(--color-text-secondary);">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-edit" style="color: var(--color-accent);"></i>
                New Message
            </h1>
        </div>
        <div class="card-body">
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">
                        Recipients
                    </label>
                    <select name="recipients[]"
                            multiple
                            class="form-input"
                            style="min-height: 100px;"
                            required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('recipients', [])) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    <p style="color: var(--color-text-muted); font-size: 0.8125rem; margin-top: 0.25rem;">
                        Hold Ctrl/Cmd to select multiple recipients
                    </p>
                    @error('recipients')
                        <p style="color: var(--color-danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">
                        Subject
                    </label>
                    <input type="text"
                           name="subject"
                           class="form-input"
                           placeholder="Enter message subject..."
                           value="{{ old('subject') }}"
                           required>
                    @error('subject')
                        <p style="color: var(--color-danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">
                        Message
                    </label>
                    <textarea name="message"
                              class="form-input"
                              rows="6"
                              placeholder="Type your message..."
                              required>{{ old('message') }}</textarea>
                    @error('message')
                        <p style="color: var(--color-danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                    <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
