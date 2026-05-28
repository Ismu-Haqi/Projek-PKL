<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration — GANDARIA
    |--------------------------------------------------------------------------
    | Batasi akses cross-origin hanya dari origin yang diizinkan.
    | Untuk production, ganti allowed_origins dengan domain resmi.
    |--------------------------------------------------------------------------
    */

    // Hanya berlaku untuk API route, bukan web route
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // Di local: izinkan semua. Di production: ganti dengan domain resmi
    // Contoh production: ['https://gandaria.diskominfo.batola.go.id']
    'allowed_origins' => env('APP_ENV') === 'production'
        ? explode(',', env('CORS_ALLOWED_ORIGINS', ''))
        : ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'X-CSRF-TOKEN',
        'Accept',
    ],

    'exposed_headers' => [],

    'max_age' => 3600,

    'supports_credentials' => true,

];
