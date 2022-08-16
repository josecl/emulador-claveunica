<?php

declare(strict_types=1);

namespace Josecl\EmuladorClaveunica\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmuladorClaveunicaController
{
    public function authorize(Request $request): RedirectResponse|View
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

        if (config('emulador-claveunica.autologin')) {
            $state = $safe['state'];
            $code = base64_encode(
                json_encode([
                    'rut' => config('emulador-claveunica.rut'),
                    'nombres' => config('emulador-claveunica.nombres'),
                    'apellidos' => config('emulador-claveunica.apellidos'),
                ], JSON_THROW_ON_ERROR)
            );

            return response()->redirectTo($request->input('redirect_uri') . '?code=' . urlencode($code) . '&state=' . urlencode($state));
        }

        return view('emulador-claveunica::form', [
            'hidden' => $safe,
            'rut' => config('emulador-claveunica.rut'),
            'nombres' => config('emulador-claveunica.nombres'),
            'apellidos' => config('emulador-claveunica.apellidos'),
        ]);
    }

    public function authorizeAction(Request $request): RedirectResponse
    {
        if (config('emulador-claveunica.log_requests')) {
            logger('authorizeAction', $request->all());
        }

        $safe = $request->validate([
            'client_id' => ['required', 'string'],
            'redirect_uri' => ['required', 'url'],
            'state' => ['required', 'string'],
            'scope' => ['required', 'string', 'in:openid run name'],
            'response_type' => ['required', 'string', 'in:code'],
            'rut' => ['required', 'string', 'regex:/^[0-9][0-9\.]*\-[0-9kK]$/i'],
            'nombres' => ['required', 'string'],
            'apellidos' => ['required', 'string'],
        ]);

        throw_if($safe['client_id'] !== config('emulador-claveunica.client_id'), new AuthenticationException());

        $state = $safe['state'];

        // TODO: LLevar a un service
        $code = base64_encode(
            json_encode([
                'rut' => $safe['rut'],
                'nombres' => $safe['nombres'],
                'apellidos' => $safe['apellidos'],
            ], JSON_THROW_ON_ERROR)
        );

        return response()->redirectTo($request->input('redirect_uri') . '?code=' . urlencode($code) . '&state=' . urlencode($state));
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

        $code = rescue(fn () => json_decode(base64_decode($safe['code'], true), true, 10, JSON_THROW_ON_ERROR));
        throw_unless($code, ValidationException::withMessages(['code' => 'Parámetro `code` malformado']));


        [$rut, $dv] = str($code['rut'])->upper()->remove('.')->explode('-');

        $user = [
            'RolUnico' => [
                'numero' => $rut,
                'DV' => $dv,
            ],
            'name' => [
                'nombres' => str($code['nombres'])->split('/\s+/'),
                'apellidos' => str($code['apellidos'])->split('/\s+/'),
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

        return json_decode(base64_decode(str($request->header('Authorization'))->after('Bearer ')->toString(), true), true, 10, JSON_THROW_ON_ERROR);
    }
}
