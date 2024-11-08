<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSeatCatalogController;
use App\Http\Controllers\EventSeatCatalogPromotionController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SeatCatalogueController;
use App\Http\Controllers\SeatCatalogueStatusController;
use App\Http\Controllers\SerieController;
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

    Route::post('/pdf-test', [EventController::class, 'testPdf'])->name('pdf-test');

});

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Series | ROUTES
*/
Route::post('/series', [SerieController::class, 'store'])->name('series.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Promociones | ROUTES
*/
Route::post('/promociones', [PromotionController::class, 'store'])->name('promotions.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Promociones | ROUTES
*/
Route::post('/catalogo-de-asientos-para-evento', [EventSeatCatalogPromotionController::class, 'store'])->name('event.seat.catalog.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Agreements | ROUTES
*/
Route::post('/convenios', [AgreementController::class, 'store'])->name('agreements.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Institutions | ROUTES
*/
Route::post('/instituciones', [InstitutionController::class, 'store'])->name('institutions.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Events | ROUTES
*/
Route::post('/eventos', [EventController::class, 'store'])->name('event.management.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Seat Catalog Status | ROUTES
*/
Route::post('/catalogo-de-status-para-asientos', [SeatCatalogueStatusController::class, 'store'])->name('seat.catalog.status.store');
