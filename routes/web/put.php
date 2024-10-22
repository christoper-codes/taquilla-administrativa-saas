<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\SerieController;
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
* |Events | ROUTES
*/
Route::put('/eventos-gestion/{id}', [EventController::class, 'update'])->name('event.management.update');
