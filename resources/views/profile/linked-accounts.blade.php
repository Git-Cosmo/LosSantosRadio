<x-layouts.app>
    <x-slot:title>Linked Accounts</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-link" style="color: var(--color-accent);"></i>
                    Linked Accounts
                </h2>
            </div>
            <div class="card-body">
                <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">
                    Link multiple accounts to sign in with any of them.
                </p>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @php
                        $providers = [
                            'discord' => ['name' => 'Discord', 'icon' => 'fab fa-discord', 'color' => '#5865F2'],
                            'twitch' => ['name' => 'Twitch', 'icon' => 'fab fa-twitch', 'color' => '#9146FF'],
                            'steam' => ['name' => 'Steam', 'icon' => 'fab fa-steam', 'color' => '#1b2838'],
                            'battlenet' => ['name' => 'Battle.net', 'icon' => 'fab fa-battle-net', 'color' => '#00AEFF'],
                        ];
                    @endphp

                    @foreach($providers as $provider => $info)
                        @php
                            $linked = $socialAccounts->firstWhere('provider', $provider);
                        @endphp
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--color-bg-tertiary); border-radius: 8px;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: {{ $info['color'] }}; border-radius: 8px;">
                                    <i class="{{ $info['icon'] }}" style="color: white; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <p style="font-weight: 500;">{{ $info['name'] }}</p>
                                    @if($linked)
                                        <p style="font-size: 0.875rem; color: var(--color-text-secondary);">
                                            {{ $linked->provider_nickname ?? 'Connected' }}
                                        </p>
                                    @else
                                        <p style="font-size: 0.875rem; color: var(--color-text-muted);">
                                            Not connected
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if($linked)
                                <form action="{{ route('auth.unlink', $provider) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to unlink this account?')">
                                        <i class="fas fa-unlink"></i> Unlink
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('auth.redirect', $provider) }}" class="btn btn-primary">
                                    <i class="fas fa-link"></i> Link
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user" style="color: var(--color-accent);"></i>
                    Account Info
                </h3>
            </div>
            <div class="card-body">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 64px; height: 64px; border-radius: 50%;">
                    <div>
                        <p style="font-weight: 600; font-size: 1.125rem;">{{ auth()->user()->name }}</p>
                        <p style="color: var(--color-text-secondary);">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
