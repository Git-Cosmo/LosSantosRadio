<x-layouts.app>
    <x-slot name="title">{{ $dj->stage_name }}</x-slot>

    <div style="max-width: 900px; margin: 0 auto;">
        <a href="{{ route('djs.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
            <i class="fas fa-arrow-left"></i> Back to DJs
        </a>

        <div class="card">
            @if($dj->cover_image)
                <div style="height: 200px; background-image: url('{{ $dj->cover_image }}'); background-size: cover; background-position: center; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 100px; background: linear-gradient(transparent, var(--color-bg-secondary));"></div>
                </div>
            @else
                <div style="height: 150px; background: linear-gradient(135deg, var(--color-accent), #a855f7);"></div>
            @endif

            <div class="card-body" style="position: relative;">
                <div style="display: flex; gap: 1.5rem; margin-top: {{ $dj->cover_image ? '-80px' : '-60px' }};">
                    @if($dj->avatar)
                        <img src="{{ $dj->avatar }}" alt="{{ $dj->stage_name }}" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid var(--color-bg-secondary); object-fit: cover; flex-shrink: 0;">
                    @else
                        <div style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid var(--color-bg-secondary); background: linear-gradient(135deg, var(--color-accent), #a855f7); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-headphones-alt" style="font-size: 3rem; color: white;"></i>
                        </div>
                    @endif
                    <div style="flex: 1; padding-top: {{ $dj->cover_image ? '50px' : '30px' }};">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <h1 style="font-size: 1.75rem;">{{ $dj->stage_name }}</h1>
                            @if($dj->is_featured)
                                <span class="badge badge-warning">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif
                        </div>
                        @if($dj->show_name)
                            <p style="font-size: 1.125rem; color: var(--color-accent); margin-bottom: 0.5rem;">{{ $dj->show_name }}</p>
                        @endif
                        @if($dj->formatted_genres)
                            <p style="color: var(--color-text-secondary);">
                                <i class="fas fa-music"></i> {{ $dj->formatted_genres }}
                            </p>
                        @endif
                    </div>
                </div>

                @if($dj->bio)
                    <div style="margin-top: 2rem;">
                        <h2 style="font-size: 1.125rem; margin-bottom: 0.75rem;">About</h2>
                        <p style="color: var(--color-text-secondary); line-height: 1.7;">
                            {!! nl2br(e($dj->bio)) !!}
                        </p>
                    </div>
                @endif

                @if($dj->show_description)
                    <div style="margin-top: 1.5rem; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                        <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-broadcast-tower" style="color: var(--color-accent);"></i>
                            About the Show
                        </h3>
                        <p style="color: var(--color-text-secondary); font-size: 0.9375rem;">
                            {{ $dj->show_description }}
                        </p>
                    </div>
                @endif

                @if($dj->social_links && count($dj->social_links) > 0)
                    <div style="margin-top: 1.5rem;">
                        <h3 style="font-size: 1rem; margin-bottom: 0.75rem;">Connect</h3>
                        <div style="display: flex; gap: 0.75rem;">
                            @if(isset($dj->social_links['discord']))
                                <a href="{{ $dj->social_links['discord'] }}" target="_blank" class="btn btn-discord btn-sm">
                                    <i class="fab fa-discord"></i>
                                </a>
                            @endif
                            @if(isset($dj->social_links['twitch']))
                                <a href="{{ $dj->social_links['twitch'] }}" target="_blank" class="btn btn-twitch btn-sm">
                                    <i class="fab fa-twitch"></i>
                                </a>
                            @endif
                            @if(isset($dj->social_links['twitter']))
                                <a href="{{ $dj->social_links['twitter'] }}" target="_blank" class="btn btn-secondary btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if(isset($dj->social_links['instagram']))
                                <a href="{{ $dj->social_links['instagram'] }}" target="_blank" class="btn btn-secondary btn-sm">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($dj->schedules->count() > 0)
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                        Show Schedule
                    </h2>
                </div>
                <div class="card-body">
                    <div class="schedule-list">
                        @foreach($dj->schedules as $schedule)
                            <div class="schedule-item {{ $schedule->isLiveNow() ? 'active' : '' }}">
                                <div class="schedule-time">
                                    <span class="schedule-hour">{{ substr($schedule->day_name, 0, 3) }}</span>
                                </div>
                                <div class="schedule-info">
                                    <h4 class="schedule-title">{{ $schedule->show_name ?? $dj->show_name ?? 'Live Show' }}</h4>
                                    <p class="schedule-desc">{{ $schedule->formatted_time }}</p>
                                </div>
                                @if($schedule->isLiveNow())
                                    <span class="badge badge-live">LIVE NOW</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; text-align: center;">
                    <div>
                        <p style="font-size: 1.5rem; font-weight: 700; color: var(--color-accent);">{{ $dj->total_shows }}</p>
                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">Total Shows</p>
                    </div>
                    <div>
                        <p style="font-size: 1.5rem; font-weight: 700; color: var(--color-accent);">{{ number_format($dj->total_listeners) }}</p>
                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">Total Listeners</p>
                    </div>
                    <div>
                        <p style="font-size: 1.5rem; font-weight: 700; color: var(--color-accent);">{{ $dj->schedules->count() }}</p>
                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">Weekly Shows</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
