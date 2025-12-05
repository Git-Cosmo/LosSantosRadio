<x-layouts.app>
    <x-slot:title>Edit Profile</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-user-edit" style="color: var(--color-accent);"></i>
                    Edit Profile
                </h2>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 1.5rem;">
                        <label for="name" style="display: block; font-weight: 500; margin-bottom: 0.5rem;">
                            Display Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}" 
                            required 
                            maxlength="255"
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-bg-tertiary); color: var(--color-text-primary); font-size: 1rem;"
                        >
                        @error('name')
                            <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="bio" style="display: block; font-weight: 500; margin-bottom: 0.5rem;">
                            Bio
                            <span style="font-weight: 400; color: var(--color-text-muted);">(optional)</span>
                        </label>
                        <textarea 
                            id="bio" 
                            name="bio" 
                            rows="4" 
                            maxlength="500"
                            placeholder="Tell us about yourself..."
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-bg-tertiary); color: var(--color-text-primary); font-size: 1rem; resize: vertical;"
                        >{{ old('bio', $user->bio) }}</textarea>
                        <p style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.5rem;">
                            Max 500 characters
                        </p>
                        @error('bio')
                            <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <a href="{{ route('profile.show', $user) }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                    Profile Preview
                </h3>
            </div>
            <div class="card-body">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <p style="font-weight: 600; font-size: 1.125rem;">{{ $user->name }}</p>
                        <p style="color: var(--color-text-secondary);">{{ $user->email }}</p>
                        <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                            Level {{ $user->level }} · {{ number_format($user->xp) }} XP
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('profile.linked-accounts') }}" style="color: var(--color-text-secondary);">
                <i class="fas fa-link"></i> Manage Linked Accounts
            </a>
        </div>
    </div>
</x-layouts.app>
