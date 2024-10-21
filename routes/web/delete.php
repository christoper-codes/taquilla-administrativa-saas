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
Route::delete('/series/{id}', [SerieController::class, 'destroy'])->name('series.destroy');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Events | ROUTES
*/
Route::delete('/eventos-gestion/{id}', [EventController::class, 'destroy'])->name('event.management.destroy');
