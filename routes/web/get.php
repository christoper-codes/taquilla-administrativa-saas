<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\WalletAccountRoleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CyberSourceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSeatCatalogPromotionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\InstallmentPaymentHistoryController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\SeatCatalogueController;
use App\Http\Controllers\SeatCatalogueStatusController;
use App\Http\Controllers\TicketOfficeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletAccountTemporalController;
use App\Http\Controllers\PriceTypeController;
use App\Http\Controllers\SeasonTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilController;
use App\Http\Controllers\WalletAccountController;
use App\Http\Controllers\SaleTicketController;
use Inertia\Inertia;

/*
Route::get('/print-reporte', function () {
    try {
        $pdfPath = storage_path('app/public/95e292bc-0535-4c4c-a89d-f37c1987a7f0.pdf');
        $printerName = 'Star BSC10';

        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'El archivo PDF no existe en: ' . $pdfPath], 404);
        }

        $process = new Process([
            'node',
            base_path('resources/js/print-pdf.js'),
            $pdfPath,
            $printerName
        ]);

        $process->run();


        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput()], 500);
        }

        return response()->json(['message' => 'PDF enviado a la impresora.']);
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
})->name('imprimir.test');
*/

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Utils | ROUTES
*/

Route::get('/migrate', [UtilController::class, 'migrate'])->name('utils.migrate');
Route::get('/limpiar-cache', [UtilController::class, 'cleanAll'])->name('utils.cleanAll');
Route::get('/refrescar-cache', [UtilController::class, 'refreshCaches'])->name('utils.refreshCaches');
Route::get('/copiar-storage', [UtilController::class, 'storageCopy'])->name('utils.storage.copy');



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
Route::get('/taquillas/search-ticket', [TicketOfficeController::class, 'search'])->name('ticket-offices.search');
Route::get('/taquillas/search-ticket-event/{id}', [TicketOfficeController::class, 'searchWithEvent'])->name('ticket-offices.search.event');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | POS Auth | ROUTES
*/
Route::middleware('auth')->group(function() {
    Route::get('/eventos/{slug}/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/eventos/disponiblidad', [EventController::class, 'getSeatAvailabilityByZone'])->name('events.availability');
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

Route::get('/create-users', [RegisteredUserController::class, 'createUser'])->name('create.users');

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

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Digital cards | ROUTES
*/
Route::get('/mis-tarjetas', [WalletAccountTemporalController::class, 'index'])->name('wallet-account.index');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Indicator for stadium | ROUTES
*/
Route::get('/indicadores-generales', [IndicatorController::class, 'index'])->name('indicators.index');
Route::get('/indicadores-evento/{slug}/{id}', [IndicatorController::class, 'show'])->name('indicators.show');
Route::get('/transito', [IndicatorController::class, 'getAllEventsWithTraffic'])->name('events.with.traffic');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Prices Type | ROUTES
*/
Route::get('/tipos-de-precio', [PriceTypeController::class, 'getAll'])->name('get.all.price.types');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Prices Type | ROUTES
*/
Route::get('/tipos-de-precio', [PriceTypeController::class, 'getAll'])->name('get.all.price.types');
Route::get('/eventos/abonados/{id}/recibo', [EventController::class, 'printSubscriber'])->name('events.printSubscriber');
Route::get('/eventos/ticket/{id}/recibo-pago-plazos', [InstallmentPaymentHistoryController::class, 'printSubscriberInstallmentReceipt'])->name('events.subscribers.installment.receipt');


Route::get('/cyber-source/captura-de-contexto', [CyberSourceController::class, 'getCaptureContext'])->name('cyber.source.capture.context');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Terms and Conditions | ROUTES
*/
Route::get('/politicas-de-privacidad', function () {
     return Inertia::render('Guest/PrivacyPolicies');
})->name('privacy.and.policies');

Route::get('/terminos-y-condiciones', function () {
     return Inertia::render('Guest/TermsAndConditions');
})->name('terms.and.conditions');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Wallets | ROUTES
*/
Route::get('/monederos', [WalletAccountController::class, 'index'])->name('wallet.index');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | SeasonTickets | ROUTES
*/
Route::get('/boletos-por-temporada/{id}', [SeasonTicketController::class, 'showTicketsBySeasonId'])->name('show.tickets.by.season');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Users | ROUTES
*/
Route::get('/usuarios', [UserController::class, 'showAllUsers'])->name('show.all.users');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Users | ROUTES
*/
Route::get('/roles-de-cuentas',[WalletAccountRoleController::class,'index'])->name('wallet.account.roles');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Export | ROUTES
*/
Route::middleware('auth')->controller(ExportController::class)->group(function () {
    Route::get('indicadores/export-summary-by-tickets', 'exportSummaryByTickets')->name('indicadores.export.summary.tickets');
});

Route::get('estadios/{id_stadium}/tickets/deudores/pendientes/exportar', [SaleTicketController::class, 'exportSaleTicketStatusPendingDebtor'])->name('stadium.tickets.pending_debtor.exportar');
Route::get('estadios/{id_stadium}/tickets/deudores/pagado/exportar', [SaleTicketController::class, 'exportSaleTicketStatusPaidDebtor'])->name('stadium.tickets.paid_debtor.exportar');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Export | ROUTES
*/


Route::get('/getCp', [UtilController::class, 'getCP'])->name('get.cp');
