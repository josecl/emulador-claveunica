<?php

return [
    /**
     * Habilita las rutas de autenticación OAuth ClaveÚnica.
     * No deben ser habilitadas en ambientes productivos.
     */
    'enabled' => env('CLAVEUNICA_FAKE_ENABLED', env('APP_DEBUG', false)),

    /**
     * Credenciales de autenticación.
     */
    'client_id' => env('CLAVEUNICA_FAKE_CLIENT_ID'),
    'client_secret' => env('CLAVEUNICA_FAKE_CLIENT_SECRET'),

    /**
     * RUN con que se realizará el inicio de sesión.
     */
    'rut' => env('CLAVEUNICA_FAKE_RUT', '44444444-4'),

    /**
     * Prefijo para las rutas de autenticación OAuth
     */
    'prefix' => env('CLAVEUNICA_FAKE_PREFIX', 'openid'),

    /**
     * Middleware para las rutas de autenticación OAuth.
     */
    'middleware' => [
        'api',
    ],

    /**
     * Habilita generación de logs de acceso con información detallada de los requests.
     */
    'log_requests' => env('CLAVEUNICA_FAKE_LOG_REQUESTS', false),
];
