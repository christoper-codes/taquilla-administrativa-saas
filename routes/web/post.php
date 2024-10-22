<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SeatCatalogueController;
use App\Http\Controllers\TicketOfficeController;
use Illuminate\Support\Facades\Route;

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Cash registers Auth | ROUTES
*/
Route::middleware('auth')->group(function() {

    Route::post('/caja-registradora/store', [CashRegisterController::class, 'store'])->name('cash-registers.store');

});

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | POS Auth | ROUTES
*/
Route::middleware('auth')->group(function() {
    Route::post('/eventos/reservar-asientos-para-compra', [EventController::class, 'reserveSeatsToBuy'])->name('events.reserve-seats-to-buy');
    Route::post('/eventos/confirmar-compra-de-asientos', [EventController::class, 'confirmSeatsPurchase'])->name('events.confirm-seats-purchase');
});

