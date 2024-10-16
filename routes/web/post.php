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
