<?php

use App\Http\Controllers\Api\PlatformSettingController;
use App\Http\Controllers\WalletAccountController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Wallets | ROUTES
*/
Route::get('/monederos/numero-cuenta', [WalletAccountController::class, 'showByAccountNumber'])->name('wallets.by.account.number');
Route::get('/usuarios/monederos', [WalletAccountController::class, 'showByUser'])->name('users.wallets.by.user');
Route::get('/monederos/historial', [WalletAccountController::class, 'showHistoryByAccountNumber'])->name('wallets.history.by.account.number');


Route::get('/configuraciones', [PlatformSettingController::class, 'getAll'])->name('configs');
Route::get('/configuraciones/clave', [PlatformSettingController::class, 'getByKey'])->name('config.key');
