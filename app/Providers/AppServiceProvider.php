<?php

namespace App\Providers;

use App\Services\AzuraCastService;
use App\Services\IcecastService;
use App\Services\RequestLimitService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Battlenet\BattlenetExtendSocialite;
use SocialiteProviders\Discord\DiscordExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Steam\SteamExtendSocialite;
use SocialiteProviders\Twitch\TwitchExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services as singletons
        $this->app->singleton(AzuraCastService::class, function ($app) {
            return new AzuraCastService($app->make(\App\Services\CacheService::class));
        });

        $this->app->singleton(IcecastService::class, function ($app) {
            return new IcecastService;
        });

        $this->app->singleton(RequestLimitService::class, function ($app) {
            return new RequestLimitService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Socialite providers
        Event::listen(SocialiteWasCalled::class, DiscordExtendSocialite::class.'@handle');
        Event::listen(SocialiteWasCalled::class, TwitchExtendSocialite::class.'@handle');
        Event::listen(SocialiteWasCalled::class, SteamExtendSocialite::class.'@handle');
        Event::listen(SocialiteWasCalled::class, BattlenetExtendSocialite::class.'@handle');
    }
}
