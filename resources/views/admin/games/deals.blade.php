<x-admin.layouts.app :title="'Game Deals'">
    <div class="admin-header">
        <h1>Game Deals</h1>
        <div class="header-actions">
            <form action="{{ route('admin.games.sync-deals') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync"></i> Sync from CheapShark
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($deals->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Store</th>
                            <th>Sale Price</th>
                            <th>Normal Price</th>
                            <th>Savings</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deals as $deal)
                            <tr>
                                <td>
                                    <a href="{{ $deal->deal_url }}" target="_blank" rel="noopener">
                                        {{ Str::limit($deal->title, 40) }}
                                    </a>
                                </td>
                                <td>{{ $deal->store?->name ?? '-' }}</td>
                                <td style="color: var(--color-success); font-weight: 600;">${{ number_format($deal->sale_price, 2) }}</td>
                                <td style="text-decoration: line-through; color: var(--color-text-muted);">${{ number_format($deal->normal_price, 2) }}</td>
                                <td><span class="badge badge-success">-{{ $deal->savings_percent }}%</span></td>
                                <td>
                                    @if($deal->is_on_sale)
                                        <span class="badge badge-success">On Sale</span>
                                    @else
                                        <span class="badge badge-gray">Ended</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="padding: 2rem; text-align: center; color: var(--color-text-muted);">No deals yet. Click "Sync from CheapShark" to get started.</p>
            @endif
        </div>
    </div>

    <div style="margin-top: 1rem;">
        {{ $deals->links() }}
    </div>
</x-admin.layouts.app>
