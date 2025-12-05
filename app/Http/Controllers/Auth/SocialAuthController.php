<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported providers.
     */
    protected array $providers = ['discord', 'twitch', 'steam', 'battlenet'];

    /**
     * Redirect to the provider for authentication.
     */
    public function redirect(string $provider)
    {
        if (! in_array($provider, $this->providers)) {
            return redirect()->route('login')->with('error', 'Unsupported authentication provider.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the provider.
     */
    public function callback(string $provider)
    {
        if (! in_array($provider, $this->providers)) {
            return redirect()->route('login')->with('error', 'Unsupported authentication provider.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with '.ucfirst($provider).'. Please try again.');
        }

        return $this->handleSocialUser($provider, $socialUser);
    }

    /**
     * Handle the social user authentication.
     */
    protected function handleSocialUser(string $provider, $socialUser)
    {
        $currentUser = Auth::user();

        // Check if this social account already exists
        $existingSocialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        // If user is already logged in, link the account
        if ($currentUser) {
            return $this->linkAccountToUser($currentUser, $provider, $socialUser, $existingSocialAccount);
        }

        // If social account exists, log in that user
        if ($existingSocialAccount) {
            $this->updateSocialAccountData($existingSocialAccount, $socialUser);
            Auth::login($existingSocialAccount->user, true);

            activity()
                ->causedBy($existingSocialAccount->user)
                ->log("Logged in via {$provider}");

            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        // Try to find user by email and link the account
        $email = $socialUser->getEmail();
        if ($email) {
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                $this->createSocialAccount($existingUser, $provider, $socialUser);
                Auth::login($existingUser, true);

                activity()
                    ->causedBy($existingUser)
                    ->log("Logged in and linked {$provider} account");

                return redirect()->intended('/')->with('success', 'Welcome back! Your '.ucfirst($provider).' account has been linked.');
            }
        }

        // Create a new user
        return $this->createNewUser($provider, $socialUser);
    }

    /**
     * Link a social account to the current logged-in user.
     */
    protected function linkAccountToUser(User $user, string $provider, $socialUser, ?SocialAccount $existingAccount)
    {
        // Check if this provider is already linked to another user
        if ($existingAccount && $existingAccount->user_id !== $user->id) {
            return redirect()->route('profile.linked-accounts')
                ->with('error', 'This '.ucfirst($provider).' account is already linked to another user.');
        }

        // Check if user already has this provider linked
        if ($user->hasSocialProvider($provider)) {
            return redirect()->route('profile.linked-accounts')
                ->with('info', 'Your '.ucfirst($provider).' account is already linked.');
        }

        $this->createSocialAccount($user, $provider, $socialUser);

        activity()
            ->causedBy($user)
            ->log("Linked {$provider} account");

        return redirect()->route('profile.linked-accounts')
            ->with('success', ucfirst($provider).' account linked successfully!');
    }

    /**
     * Create a new user from social data.
     */
    protected function createNewUser(string $provider, $socialUser)
    {
        $email = $socialUser->getEmail();

        // For Steam, which doesn't provide email
        if (! $email && $provider === 'steam') {
            $email = 'steam_'.$socialUser->getId().'@placeholder.local';
        }

        // Check if this will be the first user BEFORE creating (prevents race condition)
        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Listener',
            'email' => $email ?? $provider.'_'.$socialUser->getId().'@placeholder.local',
            'avatar' => $socialUser->getAvatar(),
            'password' => null, // Social login users don't need password
        ]);

        // Assign role: first user becomes admin, subsequent users are listeners
        if (class_exists('Spatie\Permission\Models\Role')) {
            if ($isFirstUser) {
                // Ensure admin role exists
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
                $user->assignRole('admin');
            } else {
                // Ensure listener role exists
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'listener']);
                $user->assignRole('listener');
            }
        }

        $this->createSocialAccount($user, $provider, $socialUser);

        Auth::login($user, true);

        $roleAssigned = $user->hasRole('admin') ? 'admin' : 'listener';
        activity()
            ->causedBy($user)
            ->log("Registered via {$provider} as {$roleAssigned}");

        return redirect()->intended('/')->with('success', 'Welcome to Los Santos Radio!');
    }

    /**
     * Create a social account record.
     */
    protected function createSocialAccount(User $user, string $provider, $socialUser): SocialAccount
    {
        return SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_nickname' => $socialUser->getNickname(),
            'provider_avatar' => $socialUser->getAvatar(),
            'access_token' => $socialUser->token ?? null,
            'refresh_token' => $socialUser->refreshToken ?? null,
            'token_expires_at' => isset($socialUser->expiresIn)
                ? now()->addSeconds($socialUser->expiresIn)
                : null,
        ]);
    }

    /**
     * Update social account data on login.
     */
    protected function updateSocialAccountData(SocialAccount $account, $socialUser): void
    {
        $account->update([
            'provider_nickname' => $socialUser->getNickname(),
            'provider_avatar' => $socialUser->getAvatar(),
            'access_token' => $socialUser->token ?? $account->access_token,
            'refresh_token' => $socialUser->refreshToken ?? $account->refresh_token,
            'token_expires_at' => isset($socialUser->expiresIn)
                ? now()->addSeconds($socialUser->expiresIn)
                : $account->token_expires_at,
        ]);

        // Update user avatar if they don't have one
        if (! $account->user->avatar && $socialUser->getAvatar()) {
            $account->user->update(['avatar' => $socialUser->getAvatar()]);
        }
    }

    /**
     * Unlink a social account from the current user.
     */
    public function unlink(Request $request, string $provider)
    {
        $user = $request->user();

        // Don't allow unlinking if it's the only login method and no password set
        if (! $user->password && $user->socialAccounts()->count() <= 1) {
            return back()->with('error', 'You cannot unlink your only login method. Please set a password first or link another provider.');
        }

        $account = $user->socialAccounts()->where('provider', $provider)->first();

        if ($account) {
            $account->delete();

            activity()
                ->causedBy($user)
                ->log("Unlinked {$provider} account");

            return back()->with('success', ucfirst($provider).' account unlinked successfully.');
        }

        return back()->with('error', 'No '.ucfirst($provider).' account found to unlink.');
    }
}
