<x-layouts.app :title="$thread->subject">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('messages.index') }}" style="color: var(--color-text-secondary);">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="card-title" style="font-size: 1.25rem;">
                    {{ $thread->subject }}
                </h1>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @foreach($users->take(5) as $user)
                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         title="{{ $user->name }}"
                         style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--color-bg-primary);">
                @endforeach
                @if($users->count() > 5)
                    <span style="color: var(--color-text-muted); font-size: 0.875rem;">
                        +{{ $users->count() - 5 }}
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body" style="max-height: 500px; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;" id="messages-container">
            @foreach($messages as $message)
                @php
                    $isOwn = $message->user_id === auth()->id();
                @endphp
                <div style="display: flex; gap: 0.75rem; {{ $isOwn ? 'flex-direction: row-reverse;' : '' }}">
                    <img src="{{ $message->user->avatar_url }}"
                         alt="{{ $message->user->name }}"
                         style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">
                    <div style="max-width: 70%;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem; {{ $isOwn ? 'flex-direction: row-reverse;' : '' }}">
                            <span style="font-weight: 500; color: var(--color-text-primary); font-size: 0.875rem;">
                                {{ $isOwn ? 'You' : $message->user->name }}
                            </span>
                            <span style="color: var(--color-text-muted); font-size: 0.75rem;">
                                {{ $message->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div style="background: {{ $isOwn ? 'linear-gradient(135deg, var(--color-accent), #7c3aed)' : 'var(--color-bg-tertiary)' }}; color: {{ $isOwn ? 'white' : 'var(--color-text-primary)' }}; padding: 0.75rem 1rem; border-radius: {{ $isOwn ? '12px 12px 0 12px' : '12px 12px 12px 0' }}; font-size: 0.9375rem; line-height: 1.5;">
                            {!! nl2br(e($message->body)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Form -->
        <div style="border-top: 1px solid var(--color-border); padding: 1rem;">
            <form action="{{ route('messages.update', $thread->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: flex; gap: 1rem;">
                    <textarea name="message"
                              class="form-input"
                              placeholder="Type your reply..."
                              rows="2"
                              style="flex: 1; resize: none;"
                              required></textarea>
                    <button type="submit" class="btn btn-primary" style="align-self: flex-end;">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    

    @vite('resources/js/modules/message-show.js')
</x-layouts.app>
