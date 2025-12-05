<x-layouts.app>
    <x-slot name="title">DJ Schedule</x-slot>

    <div class="hero-section" style="padding: 2rem; margin-bottom: 2rem;">
        <div class="hero-content">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">
                <i class="fas fa-calendar-alt" style="color: var(--color-accent);"></i>
                Weekly DJ Schedule
            </h1>
            <p style="color: var(--color-text-secondary);">
                See when your favorite DJs are live!
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @php
                $hasSchedules = false;
                foreach ($schedules as $daySchedules) {
                    if ($daySchedules->count() > 0) {
                        $hasSchedules = true;
                        break;
                    }
                }
            @endphp

            @if($hasSchedules)
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem;">
                    @foreach($days as $dayNum => $dayName)
                        <div style="min-width: 0;">
                            <div style="padding: 0.75rem; background: {{ $currentDay === $dayNum ? 'var(--color-accent)' : 'var(--color-bg-tertiary)' }}; color: {{ $currentDay === $dayNum ? 'white' : 'var(--color-text-primary)' }}; border-radius: 8px 8px 0 0; text-align: center; font-weight: 600;">
                                {{ substr($dayName, 0, 3) }}
                                @if($currentDay === $dayNum)
                                    <span style="display: block; font-size: 0.625rem; font-weight: 400;">Today</span>
                                @endif
                            </div>
                            <div style="border: 1px solid var(--color-border); border-top: none; border-radius: 0 0 8px 8px; min-height: 200px; padding: 0.5rem;">
                                @if(isset($schedules[$dayNum]) && $schedules[$dayNum]->count() > 0)
                                    @foreach($schedules[$dayNum] as $schedule)
                                        <a href="{{ route('djs.show', $schedule->djProfile) }}" style="display: block; padding: 0.5rem; margin-bottom: 0.5rem; background: {{ $schedule->isLiveNow() ? 'rgba(63, 185, 80, 0.2)' : 'var(--color-bg-tertiary)' }}; border-radius: 6px; border-left: 3px solid {{ $schedule->isLiveNow() ? 'var(--color-success)' : 'var(--color-accent)' }}; text-decoration: none; transition: all 0.2s ease;">
                                            <p style="font-size: 0.625rem; color: var(--color-text-muted); margin-bottom: 0.125rem;">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                                            </p>
                                            <p style="font-size: 0.8125rem; font-weight: 500; color: var(--color-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $schedule->djProfile->stage_name }}
                                            </p>
                                            @if($schedule->isLiveNow())
                                                <span class="badge badge-live" style="margin-top: 0.25rem; font-size: 0.5rem;">LIVE</span>
                                            @endif
                                        </a>
                                    @endforeach
                                @else
                                    <div style="text-align: center; padding: 2rem 0.5rem; color: var(--color-text-muted);">
                                        <i class="fas fa-robot" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                        <span style="font-size: 0.75rem;">AutoDJ</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 3rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-accent), #a855f7); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-robot" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">AutoDJ is Running 24/7</h3>
                    <p style="color: var(--color-text-muted); max-width: 400px; margin: 0 auto;">
                        No DJ schedules set up yet. Our AutoDJ keeps the music playing around the clock!
                    </p>
                </div>
            @endif
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-body">
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 12px; height: 12px; background: var(--color-success); border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem; color: var(--color-text-secondary);">Live Now</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 12px; height: 12px; background: var(--color-accent); border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem; color: var(--color-text-secondary);">Scheduled Show</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 12px; height: 12px; background: var(--color-bg-tertiary); border-radius: 50%; border: 1px solid var(--color-border);"></div>
                    <span style="font-size: 0.875rem; color: var(--color-text-secondary);">AutoDJ</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .card-body > div[style*="grid-template-columns: repeat(7"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
    </style>
</x-layouts.app>
