<x-admin.layouts.app title="Edit User">
    <div class="admin-header">
        <h1>Edit User: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                        @error('name')
                            <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                        @error('email')
                            <p style="color: var(--color-danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Roles</label>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        @foreach($roles as $role)
                            <label class="form-check">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="form-check-input">
                                <span>{{ ucfirst($role->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                @if($user->socialAccounts->count() > 0)
                    <div class="form-group">
                        <label class="form-label">Linked Social Accounts</label>
                        <div style="display: flex; gap: 0.5rem;">
                            @foreach($user->socialAccounts as $account)
                                <span class="badge badge-primary">{{ ucfirst($account->provider) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
