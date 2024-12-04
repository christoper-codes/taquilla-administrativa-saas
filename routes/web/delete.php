<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SaleTicketController;
use App\Http\Controllers\SerieController;
use Illuminate\Support\Facades\Route;


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Series | ROUTES
*/
Route::delete('/series/{id}', [SerieController::class, 'destroy'])->name('series.destroy');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Series | ROUTES
*/
Route::delete('/promociones/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Agreements | ROUTES
*/
Route::delete('/convenios/{id}', [AgreementController::class, 'destroy'])->name('agreements.destroy');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Institution | ROUTES
*/
Route::delete('/instituciones/{id}', [InstitutionController::class, 'destroy'])->name('institutions.destroy');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Events | ROUTES
*/
Route::delete('/eventos-gestion/{id}', [EventController::class, 'destroy'])->name('event.management.destroy');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Sale tickets | ROUTES
*/
Route::delete('/cancelacion-tickte-venta', [SaleTicketController::class, 'cancellationSaleTickets'])->name('sale-ticket.cancelation-sale-ticket');