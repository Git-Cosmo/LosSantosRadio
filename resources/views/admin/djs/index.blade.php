<x-admin.layouts.app>
    <x-slot name="title">DJ Profiles</x-slot>

    <div class="admin-header">
        <h1>DJ Profiles</h1>
        <a href="{{ route('admin.djs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add DJ
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>DJ</th>
                        <th>User Account</th>
                        <th>Show Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($djProfiles as $dj)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($dj->avatar)
                                        <img src="{{ $dj->avatar }}" style="width: 40px; height: 40px; border-radius: 50%;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--color-accent), #a855f7); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-headphones" style="color: white;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $dj->stage_name }}</strong>
                                        @if($dj->is_featured)
                                            <span class="badge badge-warning">Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $dj->user->name }}</td>
                            <td>{{ $dj->show_name ?? '-' }}</td>
                            <td>
                                @if($dj->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-gray">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.djs.schedules', $dj) }}" class="btn btn-sm btn-secondary" title="Manage Schedule">
                                    <i class="fas fa-calendar"></i>
                                </a>
                                <a href="{{ route('admin.djs.edit', $dj) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.djs.destroy', $dj) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--color-text-muted);">
                                No DJ profiles yet. Add your first DJ!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $djProfiles->links() }}
    </div>
</x-admin.layouts.app>
