# Emulador de ClaveÚnica - Laravel

Este proyecto emula el servicio de autenticación de [ClaveÚnica](https://claveunica.gob.cl/)
del Gobierno de Chile con el fin de permitir iniciar sesión en sistemas en desarrollo
que no cuenten con un ambiente de certificación habilitado en ClaveÚnica.

> __IMPORTANTE__
> 
> Se implementan únicamente los mecanismos mínimos para iniciar sesión.
> Este proyecto no permite validar que una aplicación cumpla con todos 
> los mecanismos de seguridad requeridos por OpenID ni ClaveÚnica.

## Requerimientos

- Laravel 9
- php 8.0

## Instalación

Instalar dependencia:

```shell
composer require josecl/emulador-claveunica
```

Publicar archivo de configuración:

```shell
php artisan vendor:publish --tag emulador-claveunica
```

Agregar las configuraciones requeridas, al menos debes configurar:

- `EMULADOR_CLAVEUNICA_ENABLED`
- `EMULADOR_CLAVEUNICA_CLIENT_ID`
- `EMULADOR_CLAVEUNICA_CLIENT_SECRET`


### Configuración de cliente

Se deben configurar las rutas del fujo de autenticación con ClaveÚnica.
Estas rutas pueden ser modificadas mediante la variable `EMULADOR_CLAVEUNICA_PREFIX`.

Las rutas originales son las siguientes:

- https://accounts.claveunica.gob.cl/openid/authorize
- https://accounts.claveunica.gob.cl/openid/token
- https://www.claveunica.gob.cl/openid/userinfo

Debes configurar tu aplicación para utilizar las siguientes rutas:

- http://example.com/openid/authorize
- http://example.com/openid/token
- http://example.com/openid/userinfo

Donde 'http://example.com' corresponde a la URL donde el emulador de ClaveÚnica está instalado.


### Integración con `josecl/claveunica`

Si utilizas el cliente [josecl/claveunica](https://github.com/josecl/claveunica),
actualiza el archivo `config/services.php` con los parámetros adicionales y configura las
siguientes variables de ambiente de acuerdo a la documentación anterior:

```php
'claveunica' => [    
  'client_id' => env('CLAVEUNICA_CLIENT_ID'),  
  'client_secret' => env('CLAVEUNICA_CLIENT_SECRET'),  
  'redirect' => env('CLAVEUNICA_REDIRECT_URI') 
  // Configura servicio emulador ClaveÚnica...
  'auth_uri' => env('CLAVEUNICA_AUTH_URI', 'https://accounts.claveunica.gob.cl/openid/authorize'),
  'token_uri' => env('CLAVEUNICA_TOKEN_URI', 'https://accounts.claveunica.gob.cl/openid/token'),
  'user_uri' => env('CLAVEUNICA_USER_URI', 'https://www.claveunica.gob.cl/openid/userinfo'),
],
```

Por ejemplo: 

```dotenv
CLAVEUNICA_AUTH_URI=http://localhost:8080/openid/authorize
CLAVEUNICA_TOKEN_URI=http://localhost:8080/openid/token
CLAVEUNICA_USER_URI=http://localhost:8080/openid/userinfo
```
