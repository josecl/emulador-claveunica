<?php

declare(strict_types=1);


beforeEach(function () {
    config(['emulador-claveunica.autologin' => true]);
});



test('authorize_error', function () {
    config(['emulador-claveunica.client_id' => null]);
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';

    $this->getJson(route('emulador-claveunica.authorize', [
        'client_id' => 'c9eb635576b74382b1b933000cd5a524',
        'redirect_uri' => 'https://example.com/auth/emulador-claveunica/callback',
        'scope' => 'openid run name',
        'response_type' => 'code',
        'state' => $state,
    ]))->assertUnauthorized();
});

test('authorize_ok', function () {
    config(['emulador-claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    $redirect_uri = 'https://example.com/auth/emulador-claveunica/callback';
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';

    $this->getJson(route('emulador-claveunica.authorize', [
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'scope' => 'openid run name',
        'response_type' => 'code',
        'state' => $state,
    ]))
        ->assertRedirect()
        ->assertRedirectContains($redirect_uri)
        ->assertRedirectContains($state);
});

test('token_ok', function () {
    config(['emulador-claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    config(['emulador-claveunica.client_secret' => $client_secret = 'b93de677942b0494d66b9a027a57403f89d8da79']);

    $code = base64_encode(json_encode([
        'rut' => '44444444-4',
        'nombres' => 'José Antonio',
        'apellidos' => 'Rodríguez Valderrama',
    ], JSON_THROW_ON_ERROR));

    $response = $this->postJson(route('emulador-claveunica.token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $code,
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/emulador-claveunica/callback',
    ]))->assertOk();

    expect($response->json())
        ->access_token->toBeString()
        ->token_type->toBe('Bearer')
        ->expires_in->toBeNumeric();
});

test('token_error_client_id', function () {
    config(['emulador-claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    config(['emulador-claveunica.client_secret' => $client_secret = 'b93de677942b0494d66b9a027a57403f89d8da79']);

    $this->postJson(route('emulador-claveunica.token', [
        'grant_type' => 'authorization_code',
        'client_id' => '::crdencial-invalida::',
        'client_secret' => $client_secret,
        'code' => 'iCKgqnpuskSomsjiCKgqnpuskSomsj',
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/emulador-claveunica/callback',
    ]))->assertUnauthorized();
});

test('token_error_client_secret', function () {
    config(['emulador-claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    config(['emulador-claveunica.client_secret' => $client_secret = 'b93de677942b0494d66b9a027a57403f89d8da79']);

    $this->postJson(route('emulador-claveunica.token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => '::crdencial-invalida::',
        'code' => 'iCKgqnpuskSomsjiCKgqnpuskSomsj',
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/emulador-claveunica/callback',
    ]))->assertUnauthorized();
});

test('userinfo_ok', function () {
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
    $token = base64_encode(json_encode($user, JSON_THROW_ON_ERROR));

    $response = $this->postJson(
        route('emulador-claveunica.userinfo'),
        headers: ['Authorization' => "Bearer {$token}"]
    )->assertOk();

    expect($response->json())->toMatchArray($user);
});
