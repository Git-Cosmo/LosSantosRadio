<x-admin.layouts.app :title="'YLYL Videos'">
    <div class="admin-header">
        <h1>YLYL Videos</h1>
        <div class="header-actions">
            <form action="{{ route('admin.videos.sync') }}" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="category" value="ylyl">
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Sync from Reddit
                </button>
            </form>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Video
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($videos->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Platform</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videos as $video)
                            <tr>
                                <td>
                                    <a href="{{ $video->video_url }}" target="_blank" rel="noopener">
                                        {{ Str::limit($video->title, 50) }}
                                    </a>
                                </td>
                                <td><span class="badge badge-gray">{{ $video->platform }}</span></td>
                                <td>{{ number_format($video->views) }}</td>
                                <td>
                                    @if($video->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-gray">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.25rem;">
                                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="padding: 2rem; text-align: center; color: var(--color-text-muted);">No YLYL videos yet.</p>
            @endif
        </div>
    </div>

    <div style="margin-top: 1rem;">
        {{ $videos->links() }}
    </div>
</x-admin.layouts.app>
