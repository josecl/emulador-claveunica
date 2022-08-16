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
