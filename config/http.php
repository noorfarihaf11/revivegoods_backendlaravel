return [
    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    */
    'cors' => [
        'paths'             => ['api/*'],   // semua endpoint API
        'allowed_methods'   => ['*'],       // GET, POST, PUT, dll
        'allowed_origins'   => ['*'],       // ganti domain asal saat produksi
        'allowed_headers'   => ['*'],
        'exposed_headers'   => [],
        'max_age'           => 0,
        'supports_credentials' => false,
    ],

    // … konfigurasi lain (middleware, csrf, dsb)
];
