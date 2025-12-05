<x-admin.layouts.app :title="'Game Stores'">
    <div class="admin-header">
        <h1>Game Stores</h1>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($stores->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Store</th>
                            <th>External ID</th>
                            <th>Active Deals</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                            <tr>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->external_id }}</td>
                                <td>{{ $store->deals_count }}</td>
                                <td>
                                    @if($store->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-gray">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="padding: 2rem; text-align: center; color: var(--color-text-muted);">No stores synced yet. Sync deals to populate stores.</p>
            @endif
        </div>
    </div>
</x-admin.layouts.app>
