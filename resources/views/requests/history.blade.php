<x-layouts.app>
    <x-slot:title>My Request History</x-slot:title>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-history" style="color: var(--color-accent);"></i>
                My Request History
            </h2>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($requests->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Song</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Artist</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Status</th>
                            <th style="padding: 0.75rem 1rem; text-align: right; color: var(--color-text-secondary); font-size: 0.75rem; text-transform: uppercase;">Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr style="border-bottom: 1px solid var(--color-border-light);">
                                <td style="padding: 0.75rem 1rem; font-weight: 500;">
                                    {{ $request->song_title }}
                                </td>
                                <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary);">
                                    {{ $request->song_artist }}
                                </td>
                                <td style="padding: 0.75rem 1rem;">
                                    <span class="badge badge-{{ $request->status_color }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right; color: var(--color-text-muted);">
                                    {{ $request->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 1rem; border-top: 1px solid var(--color-border);">
                    {{ $requests->links() }}
                </div>
            @else
                <div style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
                    <i class="fas fa-music" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    <p>You haven't made any requests yet.</p>
                    <a href="{{ route('requests.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Request a Song
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
