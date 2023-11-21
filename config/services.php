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

    'atomic' => [
        'api_url' => env('DEHOOT_API_URL', 'https://wax.api.atomicassets.io'),
        'collection_name' => env('DEHOOT_COLLECTION_NAME', 'dehootvalley'),
        'schema_names' => env('DEHOOT_SCHEMA_NAMES', 'specialbuild,residential,service'),
    ],

    'wax' => [
        'env' => env('WAX_ENV', 'testing'),
        'account' => env('WAX_ACCOUNT'),
        'private_keys' => env('WAX_ACCOUNT_PRIVATE_KEYS'),
        'receiver_account' => env('WAX_RECEIVER_ACCOUNT'),
        'testnet_receiver_account' => env('WAX_TESTNET_RECEIVER_ACCOUNT'),
        'rpc' => env('WAX_RPC', 'https://chain.wax.io'),
        'rpc_testnet' => env('WAX_TESTNET_RPC', 'https://testnet.waxsweden.org'),
    ],

    'token' => [
        'name' => env('TOKEN_NAME', 'HOOT'),
        'decimals' => env('TOKEN_DECIMALS', '4'),
    ],

    'horizon' => [
        'wallets' => env('HORIZON_WALLETS', ''),
    ],
];
