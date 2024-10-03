<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\SeatCatalogueController;
use Illuminate\Support\Facades\Route;


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |POS | ROUTES
*/
Route::get('/eventos', [EventController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{slug}/{id}', [EventController::class, 'show'])->name('eventos.show');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Auth | dashboard
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [SeatCatalogueController::class, 'index'])->name('dashboard');

});
