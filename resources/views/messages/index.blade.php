<x-layouts.app :title="'Messages'">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="card-title" style="font-size: 1.5rem;">
                <i class="fas fa-inbox" style="color: var(--color-accent);"></i>
                Messages
            </h1>
            <a href="{{ route('messages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Message
            </a>
        </div>
        <div class="card-body">
            @if($threads->count() > 0)
                <div class="messages-list" style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($threads as $thread)
                        @php
                            $isUnread = $thread->isUnread(auth()->id());
                            $lastMessage = $thread->latestMessage;
                            $participants = $thread->participants->where('user_id', '!=', auth()->id());
                        @endphp
                        <a href="{{ route('messages.show', $thread->id) }}"
                           class="message-item"
                           style="display: flex; gap: 1rem; padding: 1rem; background: {{ $isUnread ? 'rgba(88, 166, 255, 0.1)' : 'var(--color-bg-tertiary)' }}; border-radius: 8px; text-decoration: none; transition: all 0.2s; border-left: 3px solid {{ $isUnread ? 'var(--color-accent)' : 'transparent' }};">
                            <div style="flex-shrink: 0;">
                                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($thread->subject, 0, 1)) }}
                                </div>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                    <h3 style="font-weight: {{ $isUnread ? '600' : '500' }}; color: var(--color-text-primary); margin: 0;">
                                        {{ Str::limit($thread->subject, 40) }}
                                    </h3>
                                    <span style="color: var(--color-text-muted); font-size: 0.8125rem; flex-shrink: 0;">
                                        {{ $thread->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p style="color: var(--color-text-muted); font-size: 0.8125rem; margin: 0 0 0.25rem 0;">
                                    @foreach($participants->take(3) as $participant)
                                        {{ $participant->user->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($participants->count() > 3)
                                        and {{ $participants->count() - 3 }} more
                                    @endif
                                </p>
                                @if($lastMessage)
                                    <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit(strip_tags($lastMessage->body), 80) }}
                                    </p>
                                @endif
                            </div>
                            @if($isUnread)
                                <div style="flex-shrink: 0; display: flex; align-items: center;">
                                    <span style="width: 10px; height: 10px; background: var(--color-accent); border-radius: 50%;"></span>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>

                @if($threads->hasPages())
                    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                        {{ $threads->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: var(--color-text-muted); margin-bottom: 1rem;"></i>
                    <p style="color: var(--color-text-muted); margin-bottom: 1rem;">No messages yet.</p>
                    <a href="{{ route('messages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Start a Conversation
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .message-item:hover {
            background: var(--color-bg-hover) !important;
            transform: translateX(5px);
        }
    </style>
</x-layouts.app>
