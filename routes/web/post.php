
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
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CyberSourceController;
use App\Http\Controllers\EventSeatCatalogPriceTypeController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\InstallmentPaymentHistoryController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PriceCatalogueController;
use App\Http\Controllers\SaleTicketController;
use App\Http\Controllers\WalletAccountController;

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Cash registers Auth | ROUTES
*/
Route::middleware('auth')->group(function() {

    Route::post('/caja-registradora/store', [CashRegisterController::class, 'store'])->name('cash-registers.store');
    Route::post('/caja-registradora/close', [CashRegisterController::class, 'closeCashRegister'])->name('cash-registers.close');
    Route::post('/caja-registradora/close-all', [CashRegisterController::class, 'closeTicketOfficeCashRegisters'])->name('cash-registers.close.all');
    Route::post('/caja-registradora/resumen', [CashRegisterController::class, 'getCashRegisterSummary'])->name('cash-registers.summary');
    Route::post('/estadios/caja-registradora/tickets/pendientes', [SaleTicketController::class, 'saleTicketStatusPendingDebtor'])->name('cash-registers.ticket-office.status.pending');
    Route::post('/estadios/caja-registradora/tickets/pagados', [SaleTicketController::class, 'saleTicketStatusPaidDebtor'])->name('cash-registers.tickets.with.installment.payments.completed');
    Route::post('/pago-a-plazos/guardar', [InstallmentPaymentHistoryController::class, 'store'])->name('installment.payment.store');
    Route::post('/cyber-source/pago-con-token-transitorio-flex', [CyberSourceController::class, 'paymentWithFlexTransientToken'])->name('cyber.source.payment.with.flex.transient.token');
    Route::post('/cyber-source/configuracion-de-autenticacion', [CyberSourceController::class, 'authenticationSetup'])->name('cyber.source.authentication.setup');
    Route::post('/cyber-source/enroll-con-token-transitorio', [CyberSourceController::class, 'enrollWithTransientToken'])->name('cyber.source.enroll.with.transient.token');
    Route::post('/cyber-source/validacion-de-autenticacion', [CyberSourceController::class, 'validateAuthentication'])->name('cyber.source.validate.authentication');
    Route::post('/cyber-source/pago', [CyberSourceController::class, 'paymentApi'])->name('cyber.source.payment');
});

    Route::post('/cyber-source/autenticacion-cliente', [CyberSourceController::class, 'clientAuthentication'])->name('cyber.client.authentication');
/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | POS Auth | ROUTES
*/
Route::middleware('auth')->group(function() {
    Route::post('/eventos/reservar-asientos-para-compra', [EventController::class, 'reserveSeatsToBuy'])->name('events.reserve-seats-to-buy');
    Route::post('/eventos/confirmar-compra-de-asientos', [EventController::class, 'confirmSeatsPurchase'])->name('events.confirm-seats-purchase');
    Route::post('/eventos/imprimir-ticket-venta', [EventController::class, 'printSaleTicket'])->name('events.print-sale-ticket');
    Route::post('/taquillas/share-ticket', [TicketOfficeController::class, 'change'])->name('ticket-offices.change');

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
Route::post('/payed-aut-setup', [PaymentController::class, 'setupCompletionWithFlexTransientToken'])->name('payment.payed.aut.setup');
Route::post('/crear-usuario-con-roles', [RegisteredUserController::class, 'createUserWithRoles'])->name('create.user.with.roles');
Route::post('/actualizar-usuario-con-roles', [RegisteredUserController::class, 'updateRolesUser'])->name('update.user.with.roles');


/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* |Seat Catalog Status | ROUTES
*/
Route::post('/catalogo-de-status-para-asientos', [SeatCatalogueStatusController::class, 'store'])->name('seat.catalog.status.store');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Wallets | ROUTES
*/
Route::post('/precios-de-estadio', [PriceCatalogueController::class, 'getAllForStadium'])->name('get.all.for.stadium');
Route::post('/precio-de-estadio', [PriceCatalogueController::class, 'firstOrCreate'])->name('first.or.create.for.stadium');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Wallets | ROUTES
*/
Route::post('/precio-de-asientos', [EventSeatCatalogPriceTypeController::class, 'update'])->name('update.seat.price');

/*
* |--------------------------------------------------------------------------
* | Web Routes
* |--------------------------------------------------------------------------
* | Wallets | ROUTES
*/
Route::post('/monederos', [WalletAccountController::class, 'store'])->name('wallets.store');

Route::post('/transito/{id}/liberar-asientos-reservados', [IndicatorController::class, 'releaseReservedSeats'])->name('events.release.reserved.seats');
Route::post('/print-ticket', function (Request $request) {
    if (!$request->hasFile('pdf')) {
        return response()->json(['error' => 'No se envió el archivo PDF.'], 400);
    }

    $pdfFile = $request->file('pdf');
    $pdfPath = storage_path('app/temp/' . $pdfFile->getClientOriginalName());

    $pdfFile->move(storage_path('app/temp'), $pdfFile->getClientOriginalName());

    //$printerName = 'Star BSC10 (Copiar 1)';

    $process = new Process([
        'node',
        base_path('resources/js/print-pdf.js'),
        $pdfPath,
      //  $printerName
    ]);

    $process->run();

    if (!$process->isSuccessful()) {
        return response()->json(['error' => $process->getErrorOutput()], 500);
    }

    unlink($pdfPath);

    return response()->json(['message' => 'PDF recibido e impreso.']);
})->name('print');
