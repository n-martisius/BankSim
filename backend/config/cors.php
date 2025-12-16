<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '/token'],

    'allowed_methods' => ['*'], // Or specify the methods needed, e.g., ['POST', 'GET']

    // Specify the exact allowed origin instead of '*' (wildcard)
    'allowed_origins' => [
        'http://localhost:5173', // Add your frontend origin here
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Or specify needed headers like ['Content-Type', 'Authorization']

    'exposed_headers' => [],

    'max_age' => 0,

    // This needs to be set to true for credentials (cookies, Authorization headers) to be allowed
    'supports_credentials' => true,

];
