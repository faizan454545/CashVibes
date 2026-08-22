<?php

return [

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'https://www.cashvibes.online/auth/google/callback'),
    ],

    'cpx' => [
        'app_id' => env('CPX_APP_ID', ''),
        'secure_hash' => env('CPX_SECURE_HASH', ''),
        'whitelist_ips' => array_filter(explode(',', env('CPX_WHITELIST_IPS') ?? '')),
    ],

    'timewall' => [
        'placement_id' => env('TIMEWALL_PLACEMENT_ID', ''),
        'secret_key' => env('TIMEWALL_SECRET_KEY', ''),
        'whitelist_ips' => array_filter(explode(',', env('TIMEWALL_WHITELIST_IPS') ?? '')),
    ],

    'bitlabs' => [
        'app_token' => env('BITLABS_APP_TOKEN', ''),
        'secret_key' => env('BITLABS_SECRET_KEY', ''),
        'server_key' => env('BITLABS_SERVER_KEY', ''),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    ],

];
