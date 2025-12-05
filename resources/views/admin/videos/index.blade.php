<x-admin.layouts.app :title="'Videos Management'">
    <div class="admin-header">
        <h1>Videos Management</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $yylylCount }}</div>
            <div class="stat-label">YLYL Videos</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $clipsCount }}</div>
            <div class="stat-label">Streamer Clips</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Total Views</div>
        </div>
    </div>

    <div class="grid grid-cols-2" style="gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('admin.videos.ylyl') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-laugh-squint"></i> Manage YLYL Videos
                    </a>
                    <a href="{{ route('admin.videos.clips') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-tv"></i> Manage Clips
                    </a>
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                        <i class="fas fa-plus"></i> Add Video Manually
                    </a>
                    <hr style="border-color: var(--color-border);">
                    <form action="{{ route('admin.videos.sync') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="category" value="ylyl">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: flex-start;">
                            <i class="fas fa-sync"></i> Sync YLYL from Reddit
                        </button>
                    </form>
                    <form action="{{ route('admin.videos.sync') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="category" value="clips">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: flex-start;">
                            <i class="fas fa-sync"></i> Sync Clips from Reddit
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Videos</h2>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($recentVideos->count() > 0)
                    <table class="table">
                        <tbody>
                            @foreach($recentVideos as $video)
                                <tr>
                                    <td>{{ Str::limit($video->title, 35) }}</td>
                                    <td><span class="badge badge-gray">{{ $video->category }}</span></td>
                                    <td>{{ number_format($video->views) }} views</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="padding: 1rem; color: var(--color-text-muted);">No videos yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-admin.layouts.app>
