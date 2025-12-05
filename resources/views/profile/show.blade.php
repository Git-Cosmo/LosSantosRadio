<x-layouts.app>
    <x-slot name="title">{{ $user->name }}'s Profile</x-slot>

    <div style="max-width: 900px; margin: 0 auto;">
        <div class="card">
            <div style="height: 120px; background: linear-gradient(135deg, var(--color-accent), #a855f7);"></div>
            <div class="card-body" style="position: relative;">
                <div style="display: flex; gap: 1.5rem; margin-top: -60px;">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid var(--color-bg-secondary); object-fit: cover; flex-shrink: 0;">
                    <div style="flex: 1; padding-top: 30px;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <h1 style="font-size: 1.75rem;">{{ $user->name }}</h1>
                            @if($user->is_dj)
                                <span class="badge badge-primary">
                                    <i class="fas fa-headphones"></i> DJ
                                </span>
                            @endif
                        </div>
                        @if($user->bio)
                            <p style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">{{ $user->bio }}</p>
                        @endif
                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                            Member since {{ $user->created_at->format('F Y') }} · Rank #{{ $rank }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; margin-top: 1.5rem;">
            <!-- Level & XP Card -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                        Level {{ $user->level }}
                    </h2>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.875rem; color: var(--color-text-secondary);">
                                {{ number_format($user->xp) }} XP
                            </span>
                            <span style="font-size: 0.875rem; color: var(--color-text-muted);">
                                @if($user->xp_to_next_level > 0)
                                    {{ number_format($user->xp_to_next_level) }} XP to Level {{ $user->level + 1 }}
                                @else
                                    Max Level!
                                @endif
                            </span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $user->level_progress }}%;"></div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <div style="text-align: center; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                            <p style="font-size: 1.5rem; font-weight: 700; color: var(--color-accent);">{{ $user->current_streak }}</p>
                            <p style="font-size: 0.75rem; color: var(--color-text-muted);">Day Streak</p>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                            <p style="font-size: 1.5rem; font-weight: 700; color: var(--color-accent);">{{ $user->longest_streak }}</p>
                            <p style="font-size: 0.75rem; color: var(--color-text-muted);">Longest Streak</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-chart-bar" style="color: var(--color-accent);"></i>
                        Stats
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <span style="color: var(--color-text-secondary);">
                                <i class="fas fa-music" style="width: 20px;"></i> Song Requests
                            </span>
                            <span style="font-weight: 600;">{{ $user->songRequests()->count() }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <span style="color: var(--color-text-secondary);">
                                <i class="fas fa-trophy" style="width: 20px;"></i> Achievements
                            </span>
                            <span style="font-weight: 600;">{{ $user->achievements->count() }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <span style="color: var(--color-text-secondary);">
                                <i class="fas fa-fire" style="width: 20px;"></i> Total XP Earned
                            </span>
                            <span style="font-weight: 600;">{{ number_format($user->xp) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievements -->
        @if($user->achievements->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-medal" style="color: #fbbf24;"></i>
                        Achievements ({{ $user->achievements->count() }})
                    </h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        @foreach($user->achievements as $achievement)
                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: var(--color-bg-tertiary); border-radius: 8px; border-left: 3px solid {{ $achievement->badge_color }};">
                                <div style="width: 40px; height: 40px; background: {{ $achievement->badge_color }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="{{ $achievement->icon ?? 'fas fa-award' }}" style="color: white;"></i>
                                </div>
                                <div>
                                    <p style="font-weight: 500;">{{ $achievement->name }}</p>
                                    <p style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $achievement->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Requests -->
        @if($recentRequests->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-history" style="color: var(--color-accent);"></i>
                        Recent Requests
                    </h2>
                </div>
                <div class="card-body">
                    @foreach($recentRequests as $request)
                        <div class="history-item">
                            <div class="history-info">
                                <p class="history-title">{{ $request->song_title }}</p>
                                <p class="history-artist">{{ $request->song_artist }}</p>
                            </div>
                            <span class="badge badge-{{ $request->status === 'played' ? 'success' : ($request->status === 'pending' ? 'warning' : 'gray') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent XP Activity -->
        @if($recentXp->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-bolt" style="color: var(--color-warning);"></i>
                        Recent XP Activity
                    </h2>
                </div>
                <div class="card-body">
                    @foreach($recentXp as $transaction)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-bottom: 1px solid var(--color-border);">
                            <span style="color: var(--color-text-secondary);">{{ $transaction->reason }}</span>
                            <span style="color: var(--color-success); font-weight: 600;">+{{ $transaction->amount }} XP</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @auth
            @if(auth()->id() === $user->id)
                <div style="margin-top: 1.5rem; text-align: center;">
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <a href="{{ route('profile.achievements') }}" class="btn btn-secondary">
                        <i class="fas fa-trophy"></i> All Achievements
                    </a>
                </div>
            @endif
        @endauth
    </div>
</x-layouts.app>
