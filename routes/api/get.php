<?php

use App\Http\Controllers\Api\GlobalCardPaymentTypeController;
use App\Http\Controllers\Api\GlobalPaymentTypeController;
use App\Http\Controllers\Api\PlatformSettingController;
use App\Http\Controllers\Api\WalletRechargeAmountController;
use App\Http\Controllers\Api\WalletTransactionController;
use App\Http\Controllers\Api\WalletTransactionTypeController;
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

Route::get('/configuraciones', [PlatformSettingController::class, 'index'])->name('platform.settings.index');

Route::get('/monederos/tipos-transaccion', [WalletTransactionTypeController::class, 'getAll'])->name('wallets.transaction.types');

Route::get('/monederos/tipos-recargas', [WalletRechargeAmountController::class, 'getAll'])->name('wallets.recharge.amount');

Route::get('/monederos/tipos-pago', [GlobalPaymentTypeController::class, 'getAll'])->name('wallets.payment.type');

Route::get('/monederos/tipos-tarjetas', [GlobalCardPaymentTypeController::class, 'getAll'])->name('wallets.card.payment.type');

Route::get('/caja-registradora/monederos/recargas/historial', [WalletTransactionController::class, 'getRechargeAmountHistoryByCashRegister'])->name('wallet.recharge.history');
