<?php

namespace Josecl\ClaveUnica\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClaveUnicaController
{
    public function authorize(Request $request): RedirectResponse
    {
        logger('authorize', $request->all());

        $request->validate([
            'client_id' => ['required', 'string'],
            'redirect_uri' => ['required', 'url'],
            'state' => ['required', 'string'],
            'scope' => ['required', 'string', 'in:openid run name'],
            'response_type' => ['required', 'string', 'in:code'],
        ]);

        $state = $request->input('state');
        $code = md5($state);

        return response()->redirectTo($request->input('redirect_uri')."?code=$code&state=$state");
    }

    public function token(Request $request): array
    {
        logger('token', $request->all());

        $request->validate([
            'grant_type' => ['required', 'string', 'in:authorization_code'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'code' => ['required', 'string'],
            'redirect_uri' => ['required', 'url'],
        ]);

        $user = [
            'RolUnico' => [
                'numero' => '44444444',
                'DV' => '4',
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
        logger('userinfo Authorization: '.$request->header('Authorization'));

        Validator::validate([
            'authorization' => $request->header('Authorization'),
        ], [
            'authorization' => ['required', 'string', 'starts_with:Bearer '],
        ]);

        return json_decode(base64_decode(str($request->header('Authorization'))->after('Bearer ')), true, 10, JSON_THROW_ON_ERROR);
    }
}
