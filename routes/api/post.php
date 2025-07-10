<?php

use App\Http\Controllers\Api\PlatformSettingController;
use App\Http\Controllers\Api\WalletTransactionController;
use Illuminate\Support\Facades\Route;


Route::post('/monederos/compra', [WalletTransactionController::class, 'storePurchase'])->name('wallets.purchase');
Route::post('/monederos/recarga', [WalletTransactionController::class, 'storeRecharge'])->name('wallets.recharge');
Route::post('/monederos/transacciones/cancelar', [WalletTransactionController::class, 'storeTransactionCancel'])->name('wallets.transaction.cancel');
