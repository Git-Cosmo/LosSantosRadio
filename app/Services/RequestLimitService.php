<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RequestLimitService
{
    protected int $guestMaxPerDay;

    protected int $userMinIntervalSeconds;

    protected int $userMaxPerWindow;

    protected int $userWindowMinutes;

    /**
     * Role-based rate limit multipliers.
     * Higher multiplier = more generous limits.
     */
    protected array $roleMultipliers = [
        'admin' => 10.0,      // Admins get 10x the limits
        'staff' => 5.0,       // Staff get 5x the limits
        'dj' => 3.0,          // DJs get 3x the limits
        'vip' => 2.0,         // VIP users get 2x the limits
        'subscriber' => 1.5,  // Subscribers get 1.5x the limits
    ];

    public function __construct()
    {
        $this->loadLimits();
    }

    /**
     * Load limits from settings or config.
     */
    protected function loadLimits(): void
    {
        $this->guestMaxPerDay = (int) (Setting::get('request_guest_max_per_day')
            ?? config('services.requests.guest_max_per_day', 2));

        $this->userMinIntervalSeconds = (int) (Setting::get('request_user_min_interval_seconds')
            ?? config('services.requests.user_min_interval_seconds', 60));

        $this->userMaxPerWindow = (int) (Setting::get('request_user_max_per_window')
            ?? config('services.requests.user_max_per_window', 10));

        $this->userWindowMinutes = (int) (Setting::get('request_user_window_minutes')
            ?? config('services.requests.user_window_minutes', 20));

        // Load custom role multipliers from settings
        $customMultipliers = Setting::get('request_role_multipliers');
        if ($customMultipliers) {
            $decoded = is_string($customMultipliers) ? json_decode($customMultipliers, true) : $customMultipliers;
            if (is_array($decoded)) {
                $this->roleMultipliers = array_merge($this->roleMultipliers, $decoded);
            }
        }
    }

    /**
     * Get the rate limit multiplier for a user based on their roles.
     * Returns the highest multiplier among all user's roles.
     */
    protected function getRoleMultiplier(User $user): float
    {
        $cacheKey = "user_rate_multiplier_{$user->id}";

        return Cache::remember($cacheKey, 300, function () use ($user) {
            $userRoles = $user->getRoleNames()->toArray();
            $multiplier = 1.0;

            foreach ($userRoles as $role) {
                if (isset($this->roleMultipliers[$role])) {
                    $multiplier = max($multiplier, $this->roleMultipliers[$role]);
                }
            }

            return $multiplier;
        });
    }

    /**
     * Get limits adjusted for user's role.
     */
    protected function getUserLimits(User $user): array
    {
        $multiplier = $this->getRoleMultiplier($user);

        return [
            'min_interval_seconds' => max(5, (int) floor($this->userMinIntervalSeconds / $multiplier)),
            'max_per_window' => (int) ceil($this->userMaxPerWindow * $multiplier),
            'window_minutes' => $this->userWindowMinutes,
            'multiplier' => $multiplier,
        ];
    }

    /**
     * Check if a user can make a request.
     */
    public function canUserRequest(User $user): array
    {
        $limits = $this->getUserLimits($user);

        // Check minimum interval between requests
        $lastRequest = SongRequest::byUser($user)
            ->latest()
            ->first();

        if ($lastRequest) {
            $secondsSinceLast = now()->diffInSeconds($lastRequest->created_at);

            if ($secondsSinceLast < $limits['min_interval_seconds']) {
                $waitTime = $limits['min_interval_seconds'] - $secondsSinceLast;

                return [
                    'allowed' => false,
                    'reason' => "Please wait {$waitTime} seconds before making another request.",
                    'wait_seconds' => $waitTime,
                    'limits' => $limits,
                ];
            }
        }

        // Check rolling window limit
        $recentCount = SongRequest::byUser($user)
            ->withinMinutes($limits['window_minutes'])
            ->count();

        if ($recentCount >= $limits['max_per_window']) {
            return [
                'allowed' => false,
                'reason' => "You've reached the maximum of {$limits['max_per_window']} requests in {$limits['window_minutes']} minutes. Please wait a while.",
                'wait_seconds' => null,
                'limits' => $limits,
            ];
        }

        return [
            'allowed' => true,
            'reason' => null,
            'remaining' => $limits['max_per_window'] - $recentCount,
            'limits' => $limits,
        ];
    }

    /**
     * Check if a guest can make a request.
     */
    public function canGuestRequest(Request $request): array
    {
        $ip = $request->ip();
        $sessionId = $request->session()->getId();

        $todayCount = SongRequest::byGuest($ip, $sessionId)
            ->today()
            ->count();

        if ($todayCount >= $this->guestMaxPerDay) {
            return [
                'allowed' => false,
                'reason' => "Guests are limited to {$this->guestMaxPerDay} requests per day. Please sign in for more requests!",
                'remaining' => 0,
            ];
        }

        return [
            'allowed' => true,
            'reason' => null,
            'remaining' => $this->guestMaxPerDay - $todayCount,
        ];
    }

    /**
     * Check if a request can be made (works for both users and guests).
     */
    public function canRequest(Request $request): array
    {
        $user = $request->user();

        if ($user) {
            return $this->canUserRequest($user);
        }

        return $this->canGuestRequest($request);
    }

    /**
     * Get the current limits configuration.
     */
    public function getLimits(): array
    {
        return [
            'guest_max_per_day' => $this->guestMaxPerDay,
            'user_min_interval_seconds' => $this->userMinIntervalSeconds,
            'user_max_per_window' => $this->userMaxPerWindow,
            'user_window_minutes' => $this->userWindowMinutes,
            'role_multipliers' => $this->roleMultipliers,
        ];
    }

    /**
     * Get limits for a specific user (accounting for their role).
     */
    public function getLimitsForUser(?User $user): array
    {
        if (! $user) {
            return [
                'max_per_day' => $this->guestMaxPerDay,
                'type' => 'guest',
            ];
        }

        $limits = $this->getUserLimits($user);

        return [
            'min_interval_seconds' => $limits['min_interval_seconds'],
            'max_per_window' => $limits['max_per_window'],
            'window_minutes' => $limits['window_minutes'],
            'multiplier' => $limits['multiplier'],
            'type' => 'user',
        ];
    }

    /**
     * Update a limit setting.
     */
    public function updateLimit(string $key, int $value): void
    {
        $validKeys = [
            'request_guest_max_per_day',
            'request_user_min_interval_seconds',
            'request_user_max_per_window',
            'request_user_window_minutes',
        ];

        if (in_array($key, $validKeys)) {
            Setting::set($key, $value, 'integer');
            $this->loadLimits();
        }
    }

    /**
     * Update role multipliers.
     */
    public function updateRoleMultipliers(array $multipliers): void
    {
        $sanitized = [];
        foreach ($multipliers as $role => $multiplier) {
            if (is_string($role) && is_numeric($multiplier) && $multiplier > 0) {
                $sanitized[$role] = (float) $multiplier;
            }
        }

        Setting::set('request_role_multipliers', json_encode($sanitized), 'json');
        $this->roleMultipliers = array_merge($this->roleMultipliers, $sanitized);

        // Clear cached multipliers
        Cache::tags(['rate_limits'])->flush();
    }

    /**
     * Clear the cached multiplier for a specific user.
     */
    public function clearUserCache(User $user): void
    {
        Cache::forget("user_rate_multiplier_{$user->id}");
    }
}
