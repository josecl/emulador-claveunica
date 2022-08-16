<?php

test('authorize_error', function () {
    config(['claveunica.client_id' => null]);
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';

    $this->getJson(route('claveunica.authorize', [
        'client_id' => 'c9eb635576b74382b1b933000cd5a524',
        'redirect_uri' => 'https://example.com/auth/claveunica/callback',
        'scope' => 'openid run name',
        'response_type' => 'code',
        'state' => $state,
    ]))->assertUnauthorized();
});

test('authorize_ok', function () {
    config(['claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    $redirect_uri = 'https://example.com/auth/claveunica/callback';
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';

    $this->getJson(route('claveunica.authorize', [
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
    config(['claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    config(['claveunica.client_secret' => $client_secret = 'b93de677942b0494d66b9a027a57403f89d8da79']);

    $response = $this->postJson(route('claveunica.token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => 'iCKgqnpuskSomsjiCKgqnpuskSomsj',
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/claveunica/callback',
    ]))->assertOk();

    expect($response->json())
        ->access_token->toBeString()
        ->token_type->toBe('Bearer')
        ->expires_in->toBeNumeric();
});

test('token_error_client_id', function () {
    config(['claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    config(['claveunica.client_secret' => $client_secret = 'b93de677942b0494d66b9a027a57403f89d8da79']);

    $this->postJson(route('claveunica.token', [
        'grant_type' => 'authorization_code',
        'client_id' => '::crdencial-invalida::',
        'client_secret' => $client_secret,
        'code' => 'iCKgqnpuskSomsjiCKgqnpuskSomsj',
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/claveunica/callback',
    ]))->assertUnauthorized();
});

test('token_error_client_secret', function () {
    config(['claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    config(['claveunica.client_secret' => $client_secret = 'b93de677942b0494d66b9a027a57403f89d8da79']);

    $this->postJson(route('claveunica.token', [
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => '::crdencial-invalida::',
        'code' => 'iCKgqnpuskSomsjiCKgqnpuskSomsj',
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/claveunica/callback',
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
        route('claveunica.userinfo'),
        headers: ['Authorization' => "Bearer $token"]
    )->assertOk();

    expect($response->json())->toMatchArray($user);
});
