<x-layouts.app>
    <x-slot:title>Sign In</x-slot:title>

    <div style="max-width: 400px; margin: 0 auto; padding-top: 2rem;">
        <div class="card">
            <div class="card-header" style="text-align: center;">
                <h2 class="card-title" style="font-size: 1.25rem;">
                    Welcome to Los Santos Radio
                </h2>
                <p style="color: var(--color-text-secondary); margin-top: 0.5rem; font-size: 0.875rem;">
                    Sign in to request more songs and track your history
                </p>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('auth.redirect', 'discord') }}" class="btn btn-discord" style="width: 100%;">
                        <i class="fab fa-discord"></i> Continue with Discord
                    </a>

                    <a href="{{ route('auth.redirect', 'twitch') }}" class="btn btn-twitch" style="width: 100%;">
                        <i class="fab fa-twitch"></i> Continue with Twitch
                    </a>

                    <a href="{{ route('auth.redirect', 'steam') }}" class="btn btn-steam" style="width: 100%;">
                        <i class="fab fa-steam"></i> Continue with Steam
                    </a>

                    <a href="{{ route('auth.redirect', 'battlenet') }}" class="btn btn-battlenet" style="width: 100%;">
                        <i class="fab fa-battle-net"></i> Continue with Battle.net
                    </a>
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border); text-align: center;">
                    <p style="color: var(--color-text-muted); font-size: 0.875rem;">
                        By signing in, you agree to our Terms of Service and Privacy Policy.
                    </p>
                </div>
            </div>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                Don't want to sign in?
                <a href="{{ route('home') }}">Continue as guest</a>
            </p>
        </div>
    </div>
</x-layouts.app>
