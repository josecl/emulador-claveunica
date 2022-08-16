<?php

declare(strict_types=1);

namespace Josecl\EmuladorClaveunica\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmuladorClaveunicaController
{
    public function authorize(Request $request): RedirectResponse
    {
        if (config('emulador-claveunica.log_requests')) {
            logger('authorize', $request->all());
        }

        $safe = $request->validate([
            'client_id' => ['required', 'string'],
            'redirect_uri' => ['required', 'url'],
            'state' => ['required', 'string'],
            'scope' => ['required', 'string', 'in:openid run name'],
            'response_type' => ['required', 'string', 'in:code'],
        ]);

        throw_if($safe['client_id'] !== config('emulador-claveunica.client_id'), new AuthenticationException());

        $state = $safe['state'];
        $code = md5($state);

        return response()->redirectTo($request->input('redirect_uri') . "?code={$code}&state={$state}");
    }

    public function token(Request $request): array
    {
        if (config('emulador-claveunica.log_requests')) {
            logger('token', $request->all());
        }

        $safe = $request->validate([
            'grant_type' => ['required', 'string', 'in:authorization_code'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'code' => ['required', 'string'],
            'redirect_uri' => ['required', 'url'],
        ]);

        throw_if($safe['client_id'] !== config('emulador-claveunica.client_id'), new AuthenticationException());
        throw_if($safe['client_secret'] !== config('emulador-claveunica.client_secret'), new AuthenticationException());

        [$rut, $dv] = str(config('emulador-claveunica.rut'))->upper()->remove('.')->explode('-');

        $user = [
            'RolUnico' => [
                'numero' => $rut,
                'DV' => $dv,
            ],
            'name' => [
                'nombres' => ['José', 'Antonio'],
                'apellidos' => ['Rodríguez', 'Valderrama'],
            ],
        ];

        return [
            'access_token' => base64_encode(json_encode($user, JSON_THROW_ON_ERROR)),
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ];
    }

    public function userinfo(Request $request): array
    {
        if (config('emulador-claveunica.log_requests')) {
            logger('userinfo', ['authorization' => $request->header('Authorization')]);
        }

        Validator::validate([
            'authorization' => $request->header('Authorization'),
        ], [
            'authorization' => ['required', 'string', 'starts_with:Bearer '],
        ]);

        return json_decode(base64_decode(str($request->header('Authorization'))->after('Bearer '), true), true, 10, JSON_THROW_ON_ERROR);
    }
}
