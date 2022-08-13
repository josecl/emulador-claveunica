<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Josecl\ClaveUnica\Http\Controllers\ClaveUnicaController;

/*
|--------------------------------------------------------------------------
| Rutas públicas que requieren un Tramite activo
|--------------------------------------------------------------------------
*/
Route::as('claveunica.')->group(function () {
    Route::get('authorize', [ClaveUnicaController::class, 'authorize'])->name('authorize');
    Route::post('token', [ClaveUnicaController::class, 'token'])->name('token');
    Route::post('userinfo', [ClaveUnicaController::class, 'userinfo'])->name('userinfo');
    // Route::post('{api}/{function?}', [ApiFakeController::class, 'handler'])->where(['function' => '[\w\d-]+'])->name('handler');
});
