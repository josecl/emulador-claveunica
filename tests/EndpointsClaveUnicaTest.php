<?php

test('authorize', function () {
    $redirect_uri = 'https://example.com/auth/claveunica/callback';
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';
    $response = $this
        ->getJson("openid/authorize?client_id=c9eb635576b74382b1b933000cd5a524&redirect_uri=$redirect_uri&scope=openid+run+name&response_type=code&state=$state")
        ->assertRedirect();

    $response->assertRedirectContains($redirect_uri);
    $response->assertRedirectContains($state);
});

test('token', function () {
    $response = $this->postJson('openid/token', [
        'grant_type' => 'authorization_code',
        'client_id' => 'c9eb635576b74382b1b933000cd5a524',
        'client_secret' => 'b93de677942b0494d66b9a027a57403f89d8da79',
        'code' => 'iCKgqnpuskSomsjiCKgqnpuskSomsj',
        'redirect_uri' => 'https://sem.docker.zecovery.com/api/usuarios/auth/claveunica/callback',
    ])->assertOk();

    expect($response->json())
        ->access_token->toBeString()
        ->token_type->toBe('Bearer')
        ->expires_in->toBeNumeric();
});

test('userinfo', function () {
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

    $response = $this->postJson('openid/userinfo', headers: [
        'Authorization' => "Bearer $token",
    ])->assertOk();

    expect($response->json())->toMatchArray($user);
});
