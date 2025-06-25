<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\WalletAccountRoleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CyberSourceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSeatCatalogPromotionController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\InstallmentPaymentHistoryController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\SeatCatalogueController;
use App\Http\Controllers\SeatCatalogueStatusController;
use App\Http\Controllers\TicketOfficeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletAccountTemporalController;
use App\Http\Controllers\PriceTypeController;
use App\Http\Controllers\SeasonTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletAccountController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventSeatCatalog;
use App\Models\SeasonTicket;
use Database\Seeders\WalletAccountRoleSeeder;
use Database\Seeders\WalletAccountTypeSeeder;
use Database\Seeders\WalletCurrencySeeder;
use Database\Seeders\WalletExchangeRateSeeder;
use Database\Seeders\WalletRechargeAmountSeeder;
use Database\Seeders\WalletTransactionStatusSeeder;
use Database\Seeders\WalletTransactionTypeSeeder;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

Route::get('/add-subscriber-to-event-seat-catalog', function (Request $request) {

    $event_seat_catalog_with_subscriber_test = EventSeatCatalog::where([
        ['event_id', 1],
        ['qr', '!=', NULL]
    ])->get();

    $event_seat_catalog_with_subscriber = EventSeatCatalog::where([
        ['event_id', 2],
        ['season_ticket_id','!=', NULL]
    ])->get()->merge($event_seat_catalog_with_subscriber_test);

    $season_ticket = SeasonTicket::whereNotIn('id', $event_seat_catalog_with_subscriber->pluck('season_ticket_id')->filter())->get();

    $event_seat_catalog_without_subscriber = EventSeatCatalog::where([
        ['event_id', 2],
        ['season_ticket_id', NULL],
        ['qr', '!=', NULL],
    ])->get();

    $result = $event_seat_catalog_without_subscriber->map(function ($item) use ($season_ticket) {
        return collect([
            'EventSeatCatalog' => $item,
            'Subscribers' => $season_ticket->filter(function ($subscriber) use ($item) {
                return $subscriber->created_at == $item->updated_at;
            })->values(),
        ]);
    });

    $simulatedUpdates = [];

    $result->each(function ($item) use (&$simulatedUpdates) {

        $subscriberTemp = $item['Subscribers']->filter(function ($subscriber){

            return !$subscriber->is_use;

        })->first();

        if ($subscriberTemp) {

            $simulatedUpdates[] = EventSeatCatalog::where('id', $item['EventSeatCatalog']['id'])->update(['season_ticket_id' => $subscriberTemp->id]);


            $item['Subscribers']->each(function ($subscriber) use ($subscriberTemp) {

                if ($subscriber['id'] == $subscriberTemp['id']) {
                    $subscriber['is_use'] = true;
                }
            });
        }
    });

    return $simulatedUpdates;
});


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

Route::get('/migrate', function () {

    Artisan::call('migrate');

    return "migrate";

});

Route::get('/db-seed-wallet', function () {
    $seeders = [
        WalletCurrencySeeder::class,
        WalletExchangeRateSeeder::class,
        WalletAccountTypeSeeder::class,
        WalletRechargeAmountSeeder::class,
        WalletTransactionStatusSeeder::class,
        WalletTransactionTypeSeeder::class,
        WalletAccountRoleSeeder::class,
    ];

    foreach ($seeders as $seeder) {
        Artisan::call('db:seed', ['--class' => $seeder]);
    }

    return 'Seeders ejecutados correctamente.';
});


Route::get('/db-seed', function () {

    Artisan::call('db:seed');

    return "db-seed";

});

Route::get('/storage-copy', function () {
    try {
        $source = storage_path('app/public');
        $destination = public_path('storage');

        // Crear destino si no existe
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        // Copiar archivos nuevos (no sobreescribir existentes)
        $files = File::allFiles($source);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $destPath = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if (!File::exists($destPath)) {
                // Crear directorios padre si no existen
                File::ensureDirectoryExists(dirname($destPath));
                File::copy($file->getPathname(), $destPath);
            }
        }

        return 'Storage copied with only new files.';
    } catch (\Throwable $e) {
        return response("Error: " . $e->getMessage(), 500);
    }
});

Route::get('/optimize', function () {

    Artisan::call('optimize:clear');

    return "optimize";

});


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
