<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\WalletAccountController;
use Illuminate\Support\Facades\Route;


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Series | ROUTES
*/
Route::put('/series/{id}', [SerieController::class, 'update'])->name('series.update');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Series | ROUTES
*/
Route::put('/promociones/{id}', [PromotionController::class, 'update'])->name('promotions.update');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Agreements | ROUTES
*/
Route::put('/convenios/{id}', [AgreementController::class, 'update'])->name('agreements.update');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Institution | ROUTES
*/
Route::post('/instituciones/{id}', [InstitutionController::class, 'update'])->name('institutions.update');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Events | ROUTES
*/
Route::post('/eventos-gestion/{id}', [EventController::class, 'update'])->name('event.management.update');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Wallets | ROUTES
*/
Route::put('/monederos/{id}', [WalletAccountController::class, 'update'])->name('wallets.update');
