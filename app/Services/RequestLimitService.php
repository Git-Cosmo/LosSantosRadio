<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RequestLimitService
{
    protected int $guestMaxPerDay;

    protected int $userMinIntervalSeconds;

    protected int $userMaxPerWindow;

    protected int $userWindowMinutes;

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
    }

    /**
     * Check if a user can make a request.
     */
    public function canUserRequest(User $user): array
    {
        // Check minimum interval between requests
        $lastRequest = SongRequest::byUser($user)
            ->latest()
            ->first();

        if ($lastRequest) {
            $secondsSinceLast = now()->diffInSeconds($lastRequest->created_at);

            if ($secondsSinceLast < $this->userMinIntervalSeconds) {
                $waitTime = $this->userMinIntervalSeconds - $secondsSinceLast;

                return [
                    'allowed' => false,
                    'reason' => "Please wait {$waitTime} seconds before making another request.",
                    'wait_seconds' => $waitTime,
                ];
            }
        }

        // Check rolling window limit
        $recentCount = SongRequest::byUser($user)
            ->withinMinutes($this->userWindowMinutes)
            ->count();

        if ($recentCount >= $this->userMaxPerWindow) {
            return [
                'allowed' => false,
                'reason' => "You've reached the maximum of {$this->userMaxPerWindow} requests in {$this->userWindowMinutes} minutes. Please wait a while.",
                'wait_seconds' => null,
            ];
        }

        return [
            'allowed' => true,
            'reason' => null,
            'remaining' => $this->userMaxPerWindow - $recentCount,
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
}
