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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'adsense' => [
        'testing'   => config('app.debug'),
        'client_id' => env('ADSENSE_CLIENT_ID'),
        'slot_id'   => env('ADSENSE_SLOT_ID'),
    ],

    'analytics' => [
        'measurement_id' => env('ANALYTICS_MEASUREMENT_ID'),
        'api_secret'     => env('ANALYTICS_API_SECRET'),
        'testMode'       => config('app.debug'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret'    => env('PAYPAL_SECRET'),
        'mode'      => env('PAYPAL_MODE', 'sandbox'),
    ],

    'turnstile' => [
        'site_key'   => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    'beautify' => [
        'enabled'  => env('BEAUTIFY_HTML', false),
        'encoding' => 'utf8',
        'settings' => [
            'indent'               => 2,      // corresponds to auto.
            'indent-spaces'        => 4,
            'wrap'                 => 240,
            'wrap-sections'        => false,
            'markup'               => true,
            'output-xhtml'         => false,
            'char-encoding'        => 'utf8',
            'hide-comments'        => true,
            'uppercase-tags'       => false,
            'uppercase-attributes' => false,
            'break-before-br'      => false,
            'drop-empty-elements'  => false,

            // HTML5 workarounds
            'doctype'              => 'omit', //The filter will add the configured doctype later
            'new-blocklevel-tags'  => 'article,aside,canvas,dialog,embed,figcaption,figure,footer,header,hgroup,nav,output,progress,section,video,' .
                // + our custom tags for components.
                'countdown,contact-form,donate-dialog,golden-buzzer-dialog,subscribe-form,vote-dialog',
            'new-inline-tags'      => 'audio,bdi,command,datagrid,datalist,details,keygen,mark,meter,rp,rt,ruby,source,summary,time,track,wbr',
        ]
    ],
];
