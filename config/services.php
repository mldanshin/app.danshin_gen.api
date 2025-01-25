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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'notice_api_key' => env("DANSHIN_NOTICE_API_KEY"),
    'notice_url_created' => env("DANSHIN_NOTICE_URL_CREATED"),
    'notice_url_deleted' => env("DANSHIN_NOTICE_URL_DELETED"),
    "sso_verify_url" => env('SSO_VERIFY_URL'),
    "sso_timeout" => 5,
    "sso_retry_attempts" => 3,
    "sso_cache_ttl" => 86400
];
