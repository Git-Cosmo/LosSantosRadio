<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AzuraCast Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the AzuraCast API integration. This is the primary
    | source of truth for all radio data including now playing, history,
    | library, and song requests.
    |
    */

    'azuracast' => [
        'base_url' => env('AZURACAST_BASE_URL'),
        'api_key' => env('AZURACAST_API_KEY'),
        'station_id' => env('AZURACAST_STATION_ID', 1),
        'cache_ttl' => env('AZURACAST_CACHE_TTL', 30), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Icecast Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Icecast stream server integration. Used for
    | stream status, listener counts, and stream metadata.
    |
    */

    'icecast' => [
        'host' => env('ICECAST_HOST', 'localhost'),
        'port' => env('ICECAST_PORT', 8000),
        'mount' => env('ICECAST_MOUNT', '/stream'),
        'admin_user' => env('ICECAST_ADMIN_USER', 'admin'),
        'admin_password' => env('ICECAST_ADMIN_PASSWORD'),
        'ssl' => env('ICECAST_SSL', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Shoutcast Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Shoutcast stream server integration. Used for
    | stream status, listener counts, and stream metadata.
    |
    */

    'shoutcast' => [
        'host' => env('SHOUTCAST_HOST', 'localhost'),
        'port' => env('SHOUTCAST_PORT', 8000),
        'admin_password' => env('SHOUTCAST_ADMIN_PASSWORD'),
        'ssl' => env('SHOUTCAST_SSL', false),
        'stream_id' => env('SHOUTCAST_STREAM_ID', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Radio Server Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the radio server type. This determines which
    | streaming server service is used (AzuraCast, Shoutcast, or Icecast).
    |
    | Supported types: "azuracast", "shoutcast", "icecast"
    |
    | High Performance Updates:
    | - SSE: Server-Sent Events (recommended for AzuraCast)
    | - Polling: Traditional polling with configurable interval
    |
    */

    'radio' => [
        'server_type' => env('RADIO_SERVER_TYPE', 'azuracast'),
        'now_playing_method' => env('RADIO_NOW_PLAYING_METHOD', 'sse'), // 'sse' or 'polling'
        'polling_interval' => env('RADIO_POLLING_INTERVAL', 15), // seconds
        'sse_enabled' => env('RADIO_SSE_ENABLED', true),
        'sse_max_runtime' => env('RADIO_SSE_MAX_RUNTIME', 28), // seconds, max time for SSE proxy connections
    ],

    /*
    |--------------------------------------------------------------------------
    | Discord OAuth Configuration
    |--------------------------------------------------------------------------
    */

    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
        'bot_token' => env('DISCORD_BOT_TOKEN'),
        'guild_id' => env('DISCORD_GUILD_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitch OAuth Configuration
    |--------------------------------------------------------------------------
    */

    'twitch' => [
        'client_id' => env('TWITCH_CLIENT_ID'),
        'client_secret' => env('TWITCH_CLIENT_SECRET'),
        'redirect' => env('TWITCH_REDIRECT_URI'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Steam OAuth Configuration
    |--------------------------------------------------------------------------
    */

    'steam' => [
        'client_secret' => env('STEAM_CLIENT_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Battle.net OAuth Configuration
    |--------------------------------------------------------------------------
    */

    'battlenet' => [
        'client_id' => env('BATTLENET_CLIENT_ID'),
        'client_secret' => env('BATTLENET_CLIENT_SECRET'),
        'redirect' => env('BATTLENET_REDIRECT_URI'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Request System Configuration
    |--------------------------------------------------------------------------
    |
    | Default values for the request limiting system. These can be
    | overridden via the admin panel and stored in the database.
    |
    */

    'requests' => [
        'guest_max_per_day' => env('REQUEST_GUEST_MAX_PER_DAY', 2),
        'user_min_interval_seconds' => env('REQUEST_USER_MIN_INTERVAL_SECONDS', 60),
        'user_max_per_window' => env('REQUEST_USER_MAX_PER_WINDOW', 10),
        'user_window_minutes' => env('REQUEST_USER_WINDOW_MINUTES', 20),
    ],

];
