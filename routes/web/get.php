<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSeatCatalogPromotionController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\SeatCatalogueController;
use App\Http\Controllers\SeatCatalogueStatusController;
use App\Http\Controllers\TicketOfficeController;
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
* |Promotion | ROUTES
*/
Route::get('/promociones', [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/promociones-por-estadio/{id}', [PromotionController::class, 'getAllByStadium'])->name('promotion.all.by.stadium');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Agreements | ROUTES
*/
Route::get('/convenios', [AgreementController::class, 'index'])->name('agreements.index');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Institution | ROUTES
*/
Route::get('/instituciones', [InstitutionController::class, 'index'])->name('institutions.index');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |POS | ROUTES
*/
Route::get('/eventos', [EventController::class, 'index'])->name('events.index');
Route::get('/eventos-gestion', [EventController::class, 'indexManagement'])->name('event.management.indexManagement');
Route::get('/eventos-gestion/{id}', [EventController::class, 'showManagement'])->name('event.management.showManagement');
Route::post('/asientos-por-zona', [EventController::class, 'getEventSeatCatalogues'])->name('event.get.seat-catalogues');


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

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Seat Catalog Statuses | ROUTES
*/
Route::get('/block-and-reservation-statuses', [SeatCatalogueStatusController::class, 'blockAndReservationStatuses'])->name('block.and.reservation.statuses');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Events | ROUTES
*/
Route::get('/promociones-asientos', [EventSeatCatalogPromotionController::class, 'index'])->name('event.seat.catalog.promotion.index');
Route::get('/save-all-seats-for-stadium', [SeatCatalogueController::class, 'saveAllSeatsForStadium'])->name('save.all.seats.for.stadium');
