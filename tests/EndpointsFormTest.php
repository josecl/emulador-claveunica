<?php

declare(strict_types=1);

beforeEach(function () {
    config(['emulador-claveunica.autologin' => false]);
});

test('authorize_form', function () {
    config(['emulador-claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    $redirect_uri = 'https://example.com/auth/emulador-claveunica/callback';
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';

    test()->getJson(route('emulador-claveunica.authorize', [
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'scope' => 'openid run name',
        'response_type' => 'code',
        'state' => $state,
    ]))
        ->assertOk()
        ->assertSee('input');
});

test('authorize_form_submit', function () {
    config(['emulador-claveunica.client_id' => $client_id = 'c9eb635576b74382b1b933000cd5a524']);
    $redirect_uri = 'https://example.com/auth/emulador-claveunica/callback';
    $state = '8AzNFqEikDRwwptAX93pIVzZNnl2qz6EDN3ab7Ug';

    test()->postJson(route('emulador-claveunica.authorize', [
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'scope' => 'openid run name',
        'response_type' => 'code',
        'state' => $state,
        'rut' => '44.444.444-4',
        'nombres' => 'José Antonio',
        'apellidos' => 'Rodríguez Valderrama',
    ]))
        ->assertRedirectContains($redirect_uri);
});
