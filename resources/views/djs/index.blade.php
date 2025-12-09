<x-layouts.app>
    <x-slot name="title">Our DJs</x-slot>

    <div class="hero-section" style="padding: 2rem; margin-bottom: 2rem;">
        <div class="hero-content">
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">
                <i class="fas fa-headphones" style="color: var(--color-accent);"></i>
                Meet Our DJs
            </h1>
            <p style="color: var(--color-text-secondary);">
                The talented team bringing you the best music 24/7!
            </p>
        </div>
    </div>

    @if($featuredDjs->count() > 0)
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                    Featured DJs
                </h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3" style="gap: 1.5rem;">
                    @foreach($featuredDjs as $dj)
                        <div class="card" style="border-color: var(--color-accent);">
                            <div style="height: 150px; background: linear-gradient(135deg, var(--color-accent), #a855f7); display: flex; align-items: center; justify-content: center;">
                                @if($dj->avatar)
                                    <img src="{{ $dj->avatar }}" alt="{{ $dj->stage_name }}" style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; object-fit: cover;">
                                @else
                                    <div style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; background: var(--color-bg-secondary); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-headphones-alt" style="font-size: 2.5rem; color: var(--color-text-muted);"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body" style="text-align: center;">
                                <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">{{ $dj->stage_name }}</h3>
                                @if($dj->show_name)
                                    <p style="color: var(--color-accent); font-size: 0.875rem; margin-bottom: 0.5rem;">{{ $dj->show_name }}</p>
                                @endif
                                @if($dj->formatted_genres)
                                    <p style="color: var(--color-text-muted); font-size: 0.8125rem; margin-bottom: 1rem;">
                                        {{ $dj->formatted_genres }}
                                    </p>
                                @endif
                                <a href="{{ route('djs.show', $dj) }}" class="btn btn-primary" style="width: 100%;">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr]" style="gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="card-title">
                        <i class="fas fa-users" style="color: var(--color-accent);"></i>
                        All DJs
                    </h2>
                    <a href="{{ route('djs.schedule') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-calendar-alt"></i> View Schedule
                    </a>
                </div>
                <div class="card-body">
                    @if($djs->count() > 0)
                        <div class="dj-profiles">
                            @foreach($djs as $dj)
                                <div class="dj-profile">
                                    @if($dj->avatar)
                                        <img src="{{ $dj->avatar }}" alt="{{ $dj->stage_name }}" class="dj-avatar" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div class="dj-avatar">
                                            <i class="fas fa-headphones-alt"></i>
                                        </div>
                                    @endif
                                    <div class="dj-info" style="flex: 1;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                            <h4 class="dj-name">{{ $dj->stage_name }}</h4>
                                            @if($dj->is_featured)
                                                <span class="badge badge-warning" style="font-size: 0.625rem;">Featured</span>
                                            @endif
                                        </div>
                                        @if($dj->show_name)
                                            <p style="color: var(--color-accent); font-size: 0.875rem;">{{ $dj->show_name }}</p>
                                        @endif
                                        @if($dj->bio)
                                            <p class="dj-bio">{{ Str::limit($dj->bio, 100) }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('djs.show', $dj) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 3rem;">
                            <div class="dj-avatar" style="width: 80px; height: 80px; margin: 0 auto 1rem;">
                                <i class="fas fa-user-plus" style="font-size: 2rem;"></i>
                            </div>
                            <h3 style="color: var(--color-text-secondary); margin-bottom: 0.5rem;">No DJs Yet</h3>
                            <p style="color: var(--color-text-muted);">Our DJ team is growing! Check back soon.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-robot" style="color: var(--color-accent);"></i>
                        AutoDJ
                    </h2>
                </div>
                <div class="card-body">
                    <div style="text-align: center; padding: 1rem;">
                        <div class="dj-avatar" style="width: 80px; height: 80px; margin: 0 auto 1rem;">
                            <i class="fas fa-robot" style="font-size: 2rem;"></i>
                        </div>
                        <h4 style="margin-bottom: 0.5rem;">AutoDJ</h4>
                        <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                            Playing the best tracks 24/7 when our live DJs aren't on air. Never miss a beat!
                        </p>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-microphone" style="color: #a855f7;"></i>
                        Become a DJ
                    </h2>
                </div>
                <div class="card-body">
                    <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                        Want to join our team and broadcast your own shows? We're always looking for talented DJs!
                    </p>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem;">
                        <li style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-check" style="color: var(--color-success);"></i>
                            Share your music taste
                        </li>
                        <li style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-check" style="color: var(--color-success);"></i>
                            Build your following
                        </li>
                        <li style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-check" style="color: var(--color-success);"></i>
                            Join an amazing community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-discord" style="width: 100%;">
                        <i class="fab fa-discord"></i> Apply on Discord
                    </a>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-clock" style="color: var(--color-accent);"></i>
                        Current Status
                    </h2>
                </div>
                <div class="card-body">
                    <div id="on-air-status">
                        <p style="color: var(--color-text-muted); text-align: center;">
                            <i class="fas fa-spinner fa-spin"></i> Checking...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        fetch('{{ route("djs.on-air") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('on-air-status');
                if (data.on_air) {
                    container.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(63, 185, 80, 0.1); border: 1px solid var(--color-success); border-radius: 8px;">
                            <span class="badge badge-live">LIVE</span>
                            <div>
                                <p style="font-weight: 600;">${data.dj.stage_name}</p>
                                <p style="font-size: 0.875rem; color: var(--color-text-muted);">${data.dj.show_name || 'Live Show'}</p>
                            </div>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div style="text-align: center; padding: 0.5rem;">
                            <p style="font-weight: 500; margin-bottom: 0.25rem;">
                                <i class="fas fa-robot" style="color: var(--color-accent);"></i> AutoDJ is playing
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                                No live DJ at the moment
                            </p>
                        </div>
                    `;
                }
            })
            .catch(() => {
                document.getElementById('on-air-status').innerHTML = `
                    <p style="color: var(--color-text-muted); text-align: center;">
                        AutoDJ is playing
                    </p>
                `;
            });
    </script>
    @endpush
</x-layouts.app>
