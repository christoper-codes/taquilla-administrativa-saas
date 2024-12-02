<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\SeatCatalogueController;
use App\Http\Controllers\TicketOfficeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Series | ROUTES
*/
Route::get('/series', [SerieController::class, 'index'])->name('series.index');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |POS | ROUTES
*/
Route::get('/eventos', [EventController::class, 'index'])->name('events.index');
Route::get('/eventos-gestion', [EventController::class, 'indexManagement'])->name('event.management.indexManagement');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |BLOG | ROUTES
*/
Route::get('/blog/{id}', [BlogController::class, 'index'])->name('blogs.show');
Route::get('/taquillas/check-ticket', [TicketOfficeController::class, 'check'])->name('ticket-offices.check');
Route::get('/taquillas/share-ticket', [TicketOfficeController::class, 'share'])->name('ticket-offices.share');
/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | POS Auth | ROUTES
*/
Route::middleware('auth')->group(function() {
    Route::get('/eventos/{slug}/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/pago-exitoso', [EventController::class, 'success'])->name('events.success');
    Route::get('/taquillas', [TicketOfficeController::class, 'index'])->name('ticket-offices.index');
    Route::get('/taquillas/{ticketOffice}', [TicketOfficeController::class, 'show'])->name('ticket-offices.show');
});


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Auth | dashboard
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [SeatCatalogueController::class, 'index'])->name('dashboard');

});

Route::get('/capture-context', [PaymentController::class, 'getCaptureContext'])->name('capture.context');

Route::get('/saveAllSeatsForStadium', [SeatCatalogueController::class, 'saveAllSeatsForStadium'])->name('saveAllSeatsForStadium');
