<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    'cpx' => [
        'app_id' => env('CPX_APP_ID', '34726'),
        'secure_hash' => env('CPX_SECURE_HASH', 'YOUR_CPX_SECURE_HASH_HERE'),
        'whitelist_ips' => array_filter(explode(',', env('CPX_WHITELIST_IPS', '188.40.3.73,157.90.97.92'))),
    ],

    'timewall' => [
        'placement_id' => env('TIMEWALL_PLACEMENT_ID', 'fe9fc7796546d9ee'),
        'secret_key' => env('TIMEWALL_SECRET_KEY', '952a336b0b723c9039c36e31697210ce'),
        'whitelist_ips' => ['18.156.132.55', '51.81.120.73', '142.111.248.18'],
    ],

    'bitlabs' => [
        'app_token' => env('BITLABS_APP_TOKEN', '1f354d36-439c-4bef-b78a-71a3a05c1a40'),
        'secret_key' => env('BITLABS_SECRET_KEY', 'Rrw2bkzf96RQv2B5rt8DfLATr5TCAy4y'),
        'server_key' => env('BITLABS_SERVER_KEY', 'a8eflYHEbWLXbSk26f6z1c8oNJbnFube'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', '6LdbDGYtAAAAACZWYLycz5Gxz6VbBmqhkP1ZiKGP'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', '6LdbDGYtAAAAAMbnDx7WNS_jsXQ10zAqkaV5uWyn'),
    ],

];
