<?php

return [
    /**
     * Habilita las rutas de autenticación OAuth ClaveÚnica.
     * No deben ser habilitadas en ambientes productivos.
     */
    'enabled' => env('EMULADOR_CLAVEUNICA_ENABLED', env('APP_DEBUG', false)),

    /**
     * Credenciales de autenticación.
     */
    'client_id' => env('EMULADOR_CLAVEUNICA_CLIENT_ID'),
    'client_secret' => env('EMULADOR_CLAVEUNICA_CLIENT_SECRET'),

    /**
     * RUN con que se realizará el inicio de sesión.
     */
    'rut' => env('EMULADOR_CLAVEUNICA_RUT', '44444444-4'),

    /**
     * Prefijo para las rutas de autenticación OAuth
     */
    'prefix' => env('EMULADOR_CLAVEUNICA_PREFIX', 'openid'),

    /**
     * Middleware para las rutas de autenticación OAuth.
     */
    'middleware' => [
        'api',
    ],

    /**
     * Habilita generación de logs de acceso con información detallada de los requests.
     */
    'log_requests' => env('EMULADOR_CLAVEUNICA_LOG_REQUESTS', false),
];
