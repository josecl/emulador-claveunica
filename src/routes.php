<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Josecl\EmuladorClaveunica\Http\Controllers\EmuladorClaveunicaController;

/*
|--------------------------------------------------------------------------
| Rutas públicas que requieren un Tramite activo
|--------------------------------------------------------------------------
*/
Route::as('emulador-claveunica.')->group(function () {
    Route::get('authorize', [EmuladorClaveunicaController::class, 'authorize'])->name('authorize');
    Route::post('token', [EmuladorClaveunicaController::class, 'token'])->name('token');
    Route::post('userinfo', [EmuladorClaveunicaController::class, 'userinfo'])->name('userinfo');
    // Route::post('{api}/{function?}', [ApiFakeController::class, 'handler'])->where(['function' => '[\w\d-]+'])->name('handler');
});
