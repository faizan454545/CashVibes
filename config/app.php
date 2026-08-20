<?php

use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_NAME', 'CashVibes'),
    'version' => env('APP_VERSION', '2.2.0'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', env('APP_URL')),

    'timezone' => 'Asia/Karachi',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([
        AppServiceProvider::class,
        SocialiteServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'Socialite' => Socialite::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | CashVibes System Constants
    |--------------------------------------------------------------------------
    |
    | Core business and programmatic boundaries for the platform.
    | These values control coin valuation, bonuses, and withdrawal thresholds.
    |
    */
    'coin_value_pkr' => (float) env('COIN_VALUE_PKR', 0.30),
    'registration_bonus' => (int) env('REGISTRATION_BONUS', 50),
    'referral_bonus_amt' => (int) env('REFERRAL_BONUS_AMT', 50),
    'min_withdrawal_coins' => (int) env('MIN_WITHDRAWAL_COINS', 500),
    'referral_task_reward_pct' => (float) env('REFERRAL_TASK_REWARD_PCT', 0.10),

    'daily_target' => (int) env('DAILY_TARGET', 300),
    'admin_profit_pct' => (float) env('ADMIN_PROFIT_PCT', 0.30),
    'max_task_reward' => (float) env('MAX_TASK_REWARD', 500.0),
    'daily_task_limit' => (int) env('DAILY_TASK_LIMIT', 20),

    'withdrawal_fee_easypaisa' => (float) env('WITHDRAWAL_FEE_EASYPAISA', 0.00),
    'withdrawal_fee_jazzcash' => (float) env('WITHDRAWAL_FEE_JAZZCASH', 0.00),

];
