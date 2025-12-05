<x-admin.layouts.app>
    <x-slot name="title">Polls</x-slot>

    <div class="admin-header">
        <h1>Polls</h1>
        <a href="{{ route('admin.polls.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Poll
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Votes</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polls as $poll)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($poll->question, 50) }}</strong>
                            </td>
                            <td>{{ $poll->votes_count ?? 0 }}</td>
                            <td>
                                {{ $poll->starts_at->format('M j') }} - {{ $poll->ends_at->format('M j, Y') }}
                            </td>
                            <td>
                                @if($poll->isOpen())
                                    <span class="badge badge-success">Active</span>
                                @elseif($poll->hasEnded())
                                    <span class="badge badge-gray">Ended</span>
                                @else
                                    <span class="badge badge-warning">Upcoming</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.polls.edit', $poll) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.polls.destroy', $poll) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
                                No polls yet. Create your first poll!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $polls->links() }}
    </div>
</x-admin.layouts.app>
