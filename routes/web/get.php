<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSeatCatalogPromotionController;
use App\Http\Controllers\IndicatorController;
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
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/migrate-fresh', function () {

    Artisan::call('migrate:fresh');

    return "migrate-fresh";

});

Route::get('/db-seed', function () {

    Artisan::call('db:seed');

    return "db-seed";

});

Route::get('/storage-link', function () {

    Artisan::call('storage:link');

    return "storage-link";

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
Route::get('/saleTicketPaperPdf', function () {
    // Datos que quieres pasar a la vista
    $data = [
        [
            "event_name" => "Halcones vs Tezcadas",
            "event_start_date" => "2025-03-27 06:30:00",
            "seat_code" => "CA5",
            "zone_type" => "C",
            "row" => "A",
            "seat" => "5",
            "seat_type" => "purpura",
            "percentage_cashback"=> 3,
            "qr_img" => "data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUAAAAFdCAIAAAD9uiO4AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAS70lEQVR4nO3df2hVZRzH8WdrzU2mm9NsrtuyMacsUxshtx9kmBGskiEaolIrpGJaSJRYWgyRMhHLFDGRJcPEbIiKhYTECLVVoqYmQ2LJ1JuMIbLsYvO62x9Pndbd2d25O\/c853y39+uvup7zPM89u589d+d7nnMy4vG4AiBTpt8DADBwBBgQjAADghFgQDACDAhGgAHBCDAgGAEGBCPAgGAEGBCMAAOCEWBAMAIMCEaAAcEIMCBYVqo7ZGRkeDEON2yXNNuO0\/niZ+e7e7Gl8yE558WQbHnxCXE+eC92N2YAR54ZGBCMAAOCEWBAMAIMCEaAAcEIMCBYymUkW8buTevy1L+x3V1uaXs8jdWBbBkbp7\/VHSmfZAszMCAYAQYEI8CAYAQYEIwAA4IRYECw9JSRbHmxesYlY3ULfws5\/pamvKgY+VuFCuAn2cIMDAhGgAHBCDAgGAEGBCPAgGAEGBDMwzKSv7y4DZot2zaN3W\/Niy1dMnbkoZiBAdEIMCAYAQYEI8CAYAQYEIwAA4IN2jKSc\/4WnFwy1ru\/K3Jcrq8axJiBAcEIMCAYAQYEI8CAYAQYEIwAA4J5WEby94S+yzuzeVH2cMnYs5G8eO9e3JHPmCCXppiBAcEIMCAYAQYEI8CAYAQYEIwAA4Klp4xk7IZpXjBW4XDZprEtbQ2+925L3CeZGRgQjAADghFgQDACDAhGgAHBCDAgWMplpCCvzPCasdqSyy1tGVvlM5TbNI8ZGBCMAAOCEWBAMAIMCEaAAcEIMCBYhncn0714mo4X62y8GJKx3Z2T8uPw4gNp7MFO5p8gxQwMCEaAAcEIMCAYAQYEI8CAYAQYECzl1Uhe1ANcPk1HCpdFF2OFMVvGqlAB7N1lR7bS1TszMCAYAQYEI8CAYAQYEIwAA4IRYECwlFcjBXCVj\/M2bRlbUmOsAueyTecdSVkx5lwAh5QcMzAgGAEGBCPAgGAEGBCMAAOCEWBAMNM3tZNSnrFlbIGUsQPivE1b\/t7UzouSj3MB+SwxAwOCEWBAMAIMCEaAAcEIMCAYAQYES\/mmdraMLUBxefI9gEtqbNs0tnbHlrFqmcs2vfi5O9\/d5ZbpOp7MwIBgBBgQjAADghFgQDACDAhGgAHB0lNGMrbGyOWWXvB38KKfIBXAepXLipH5I88MDAhGgAHBCDAgGAEGBCPAgGAEGBAs5TKS+fUW\/fbusogl7nE4\/XbkbwXO2NIffw+yLfO1JWZgQDACDAhGgAHBCDAgGAEGBCPAgGApl5H8rQd4cRu0IN+yrN82\/X1cU0CeDzSwjlzuHpDCGDMwIBgBBgQjwIBgBBgQjAADghFgQLCMtJy19\/f+dc47ckn0k5mMjdN57\/4O3uXu3NQOgFsEGBCMAAOCEWBAMAIMCEaAAcECcVO7IJ+m95qx2lIAK3DOe3f+3qV8QtL18WYGBgQjwIBgBBgQjAADghFgQDACDAiWchnJlhdrTQK4TCeAS2q8eLCTLWM3dnPOWGEsyLc9ZAYGBCPAgGAEGBCMAAOCEWBAMAIMCJbyTe2M3cfMZZterHDy9\/lAAbwFnLE2nXfkL\/NHnhkYEIwAA4IRYEAwAgwIRoABwQgwIJiHq5G8aNOLdSEuGVtnI6U4ZOwpSv7WFF1iNRIAAgxIRoABwQgwIBgBBgQjwIBgKa9G8mQQplY4BXBNjLFb1Rkrpfh7rzl\/77Nnvl7FDAwIRoABwQgwIBgBBgQjwIBgBBgQLD1lJH+rEbakrN0xtvDI36KLvz8OY8wPnhkYEIwAA4IRYEAwAgwIRoABwQgwIFggViO5JOUZOcaWv9gK4JIvKQu5nPduy9PlWczAgGAEGBCMAAOCEWBAMAIMCEaAAcFSLiMZKyf4++SbAK7yCeAaI5ekvKMArtmyMAMDghFgQDACDAhGgAHBCDAgGAEGBMtKSyterF\/x915z\/u7ushrhvKMAPmvKWJvO36axhVwDwAwMCEaAAcEIMCAYAQYEI8CAYAQYECw9ZSRjXBZyvNjSZc3GuQDeu8+LwpjLo+TyR+zyHfFsJAApIMCAYAQYEIwAA4IRYEAwAgwIZvqmdv6udDG2HsgYLwbv7w\/O35+RlIVxFmZgQDACDAhGgAHBCDAgGAEGBCPAgGApl5H8Zf7ZMwNjrBZi7CFMLtt0KSA1m4G16ekSJWZgQDACDAhGgAHBCDAgGAEGBCPAgGCmVyM5b9PYM4eMLb7x92FRoutVzklZosRqJAAEGJCMAAOCEWBAMAIMCEaAAcHS82wkf8se\/j5zaIhUTbzo3Vhly4sjH5B7ITIDA4IRYEAwAgwIRoABwQgwIBgBBgRLTxnJlhfrgaQ8Csj57s7fpr9Hyd96lS1\/l2fZMl9bYgYGBCPAgGAEGBCMAAOCEWBAMAIMCOZhGcnlWf6ArPbwmsvikL8rnLwooTnvSMonxNMncjEDA4IRYEAwAgwIRoABwQgwIBgBBgRLuYwUwDuJ2fJiPZBLXnRkbDFTAEm57aGnmIEBwQgwIBgBBgQjwIBgBBgQjAADgqVcRgrgKXUvlqp4sVDGOS9qdV7c1M6LtxnA2wm65GlHzMCAYAQYEIwAA4IRYEAwAgwIRoABwTJSPZ3t7\/oVYwt6bBlbOeTF055cDsnY7l6QshppAONkBgYEI8CAYAQYEIwAA4IRYEAwAgwIZvrZSM4FsDjkbxHL3za96N3Y0h9\/DwjPRgJgjwADghFgQDACDAhGgAHBCDAgmIdlpADyomLk8ulEznu35cViJi+et+SyI5cFPHG3qnOOGRgQjAADghFgQDACDAhGgAHBCDAg2NAqI9ky9jQdf2shXpRn\/L1Nnxe9+9vRADADA4IRYEAwAgwIRoABwQgwIBgBBgTzsIxkfmXGwHo3tqTGCy7H6XxLY7WlAD6uyYtSX7o+NszAgGAEGBCMAAOCEWBAMAIMCEaAAcHSU0by99kzLvn7xCN\/bwFnbDnREPmEeHGHw+SYgQHBCDAgGAEGBCPAgGAEGBCMAAOCZfi7ZgiAG8zAgGAEGBCMAAOCEWBAMAIMCEaAAcEIMCAYAQYEI8CAYAQYEIwAA4IRYEAwAgwIRoABwQgwIBgBBgQjwDZee+21jIyMp59+2n1TGzdunDBhwv79+903FVi\/\/\/77hx9++MQTT4wbN27YsGG5ubnjxo2bMWPGypUrf\/755772cnKQL1++nNG3t99+24N3I00c\/xeNRgsKCpRSmZmZFy5ccNna5MmTlVJz5sxJy9gCaO3atdnZ2X19uvLy8q5du9Z7L4cH+cSJE0k+uitWrPDyncnADJxo9+7d165dC4VC3d3d27dvd9ladXV1Xl5edXV1Wsbm3IwZM+bNm+dpFzdv3nz22WdXrFjR1dVVWVlZX1\/f2toajUaj0WhLS0t9fX1VVdXSpUvz8\/N77+vwIHd0dCilHn300ZidDz74wMO3J4Xfv0ECJxwOK6X27NmjlCouLo7FYn6PKGXt7e2ZmZlz5871tJc333xTf4TWrFmT6r4OD\/LOnTuVUl6\/EdGYgf\/nzJkzzc3NoVBo3rx5lZWVkUjk4MGDfg8qZY2Njd3d3Z52cfTo0Q0bNiilVqxYsXLlypT2dX6Q29vblVJjx451P+DBigD\/z9atW5VSc+fOVUo9\/\/zzSqlt27bZbnnr1q3PPvvsySefvPPOO2+\/\/fY77rhj6tSpL7744v79+\/\/66y9rs3nz5mVkZLz77ru9W\/jtt9+WLFkyYcKE3Nzc\/Pz8hx56aNOmTTdv3kzYTLdw5syZmzdvbtq06ZFHHhk1atSwYcPuueeehQsXnjx5sufGR48efeqpp5YuXaqUamxstM73TJgwIaHZixcvvvXWW1OnTs3Pz8\/Nzb333ntfeOGFH374weGB2rBhQ3d3dygUWr16tcNdLM4Psg5wUVFRql0MIX5\/BQiQaDQ6cuRIpdTp06fj8XhHR0d2dnZmZmZbW1vCll1dXTNnztQHcMyYMeXl5fqUjFIqKyur5\/b6Y7pq1aqEFhobG3NycvT2ZWVlxcXFevdwOHz16tWeW+oWtm7d+uCDDyqlsrOzQ6GQHqfefd++fdbGW7ZseeaZZ8rKypRSRUVFz\/zr5Zdf7tnmnj17hg8frlsYO3ZsKBTKyvrnOZVOzgzduHFD715XV+fs0P7H+UGOx+OLFy9WSm3bti3VXoYOAvwffTZl+vTp1is6PO+9917ClnoOKS0tPXHihPViR0fHjh071q5d23NL2wAfP35cn7mtra214nr8+PHy8nLV608+3UJ2dnZhYeHOnTuj0ah+\/fTp05WVlUqp4uLirq6unrusWrWqdzuWI0eO6LjW1NRYsens7Fy\/fr0eVcJb6M06OdzU1JR8y96cH+R4PD579mylVEVFxfjx44cPH56VlTV27NiZM2d+8skn169fT7XrQYkA\/2f69OkJv++\/\/vprpVQoFEo4y6K\/oy5fvrzfNm0D\/NhjjymlqqqqEjZubm7Wwej5e0G3YJuWlpaWzMxMpdSxY8d6vp48wPptzp8\/v\/c\/1dfXK6VycnKuXLmS5E3t27dPD8l22kzO+UGOx+Ovvvqq7mjkyJElJSVFRUX6\/erfnmfPnk2198GHAP\/j1KlTSqnhw4d3dnZaL8ZisVAopJQ6cOBAz43Xr1+vP0P9foJ7B\/jChQu9U2qZNm1awldT3cK0adNs29fD27VrV88XkwT4\/PnzuveWlhbbBvXX7w0bNiR5U7t379aN2NZ4k0jpIGunT5\/u+TdFZ2fnzp07S0pK9FePVAcw+HAS6x\/WmZURI0ZYL9522222Z1kWL15cUVHR2to6adKkJUuWJJxJSu7YsWNKqZEjRz7wwAO9\/7W0tFQpde7cuYTX9R\/AvekTPNFo1GHvepIvKiqaOHGi7QaPP\/64tVlf8vLy9H9cv37dYb9aSgdZu\/\/++0eNGmX974gRIxYuXHjkyJGCgoJIJLJ58+aUBjD4EGCllPrzzz937dqllGpoaEi4Xu\/9999XSh06dOjixYvW9vn5+c3Nza+\/\/rpSasuWLZWVlffdd99HH330xx9\/9NvXlStXlFKdnZ22lwfu3btXKXXt2rWEvcaMGZOWd6rP61rnzHrTk6EeZF+suk5bW5vzrlM9yEncfffdNTU1SqlvvvnG+QAGJQKslFK7d+\/u7OxMskEsFtN\/H1pGjBixcePGSCSydevWcDh87ty5N954o7S09Msvv0zel67Q5uXlhfs2adKkhL2ss8Qu6XZcVoknT56sT3fpbxMODeAgJx+D6u8XzVBAgJX696vd+vXrbS\/Z03\/x1tfX37p1K2HH\/Pz8V1555fvvvz916lRVVVVHR8eCBQuSf6PWc2lRUdH3fdu4caNH71RPsJFIpK8NLl26pPq7diI3N1efi9qxY4fzrgd8kG11dXUppaxy2pBFgNXJkyd1XaempuY2OzU1NTk5OW1tbYcOHeqrkalTp3711VezZs2KxWLWOR5b+irC1tbWy5cvp\/\/N9GA7zYbD4czMzPb29l9++cV2r6amJvXvIJOora1VSp09e\/bTTz91Mpi0HOSe9OSv5+GhjAD\/MzPMmTNn9OjRthuMHj1anwru64Ihy5QpU5RSyb8oTpw4MRwOd3d3L1++fIAj7o8+yWSd7u7prrvu0peg2F5B9fnnn\/\/666\/Z2dnz589P3sVzzz2nJ+Fly5Z98cUX\/Q5pAAe590Vplp9++kn\/llywYEG\/XQ9yfp8G99n169f117Bvv\/02yWbfffedUiorK+vSpUvxeLyurq62tvb48ePWBrFY7MCBA4WFhUqpPXv2WK\/b1oGbm5v135CzZ8\/+8ccfdf0zFou1tbU1NDRUV1frXpK0YNFnp7dv397zRWsSa2hosIZn\/WtLS4u+jqqmpiYSiegXo9Ho5s2b9cVhffWVoLW1VX8hV0pVVVU1NjZGIpFYLHbjxo22trbDhw+vWbNGXyU2sIPc1NT08MMPb9++vbW11domEomsW7dO\/4ZikUOcOrD+fV9WVtbvlhUVFUqp1atXx+PxZcuW6Q9uXl7epEmTysvLrcpKwgUSfcXv4MGD1tWX+ior6xIFpVTPJbIDCLD1ulKquLi4vLy8sLCw578ePnxY\/ymemZlZUlJSVlamo6uUqq2tdb4AKxKJWJeU2tILlQZ2kPWXeS0nJ6eoqMg6YkqpBQsW3Lhxw+E4B7GhHmB9NeK6dev63fLjjz9WSpWUlMTj8ba2tnfeeSccDhcWFmZlZWVnZ5eUlFRXV+\/duzdhryTxa29vr6ursxrRvwtqamoSrmcYWICvXr1aW1urL3IuLCycNWtWwgZXrlxZvXp1ZWVlQUFBTk7O+PHjFy1adOTIkX6PQ29NTU1Lly6dMmXKmDFjsrKyCgoKSktLZ86cWVdXd\/78+fhAD3I8Hj9w4MCiRYsqKiry8vKsQ\/TSSy8NbJyDUkY8Hk\/2DRtAgHESCxCMAAOCEWBAMAIMCEaAAcEIMCAYAQYEI8CAYAQYEOxvUJBIqTcDJusAAAAASUVORK5CYII=",
            "qr" => "qr_evento_2_asiento_CA5_ticket_7_key_67e5e67bbfab4",
            "final_price"=> 100,
            "ticket_id"=> 7,
            "seller_user"=> [
                "id" => 1,
                "user_gender_id" => 4,
                "first_name" => "usuario hdx online",
                "last_name" => "usuario hdx online",
                "middle_name" => "usuario hdx online",
                "username" => "usuario-hdx-online",
                "birthdate" => "2025-03-27",
                "email" => "usuario.hdx.online@gmail.com",
                "email_verified_at" => null,
                "phone_number" => null,
                "color" => "purple",
                "is_active" => 1,
                "created_at" => "2025-03-27T19:05:49.000000Z",
                "updated_at" => "2025-03-27T19:05:49.000000Z",
                "is_new" => false
            ],
            "ticket_created_at" => "2025-03-27T23:59:54.000000Z",
            "cash_register_type" => 1,
            "is_owner" => "No",
            "description" => null,
            "holder_name" => null,
            "holder_last_name" => null,
            "holder_middle_name" => null,
            "holder_zip_code" => null,
            "holder_phone" => null,
            "holder_email" => null
        ]
    ];


    $pdf_response = Pdf::loadView('pdfs.hdx.saleTicketPaper', ['pdf_data' => $data]);

    return $pdf_response->stream('archivo.pdf');
});

Route::get('/saleTicketPaperPdfAbonado', function () {

    $data = [
        "pdf_data" => [
            [
                "event_name" => "123123",
                "event_start_date" => "2025-03-27 18:30:00",
                "seat_code" => "CA8",
                "zone_type" => "C",
                "row" => "A",
                "seat" => "8",
                "seat_type" => "purpura",
                "percentage_cashback" => 3,
                "qr_img"=> "data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUAAAAFdCAIAAAD9uiO4AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAS5klEQVR4nO3df0hV9x\/H8Y\/XOzNRJybOnIiIM4mwJqNd+sPCiTAXIyJGxIg7iG24NiJabC2GhIw2JFIiXAuJqNFMRosWY8SQkCYS\/aJFbCHNlYsQCWkXJ3ee7x+fdna\/9x5v53rv+fHW5+OPMe4995xzP52Xn3PP+3zOJ8swDAVApoDXOwBg7ggwIBgBBgQjwIBgBBgQjAADghFgQDACDAhGgAHBCDAgGAEGBCPAgGAEGBCMAAOCEWBAsGCqH8jKynJiP9Lh2pBmy+9uf+v2m85ynd5u3f46Xft4mubHkUwPDAhGgAHBCDAgGAEGBCPAgGAEGBAs5TKSJdGX\/u0XM9L8mt4+wdd+aSrNRhZdWxJ3JNMDA4IRYEAwAgwIRoABwQgwIBgBBgTLTBnJUpoXytO8oJ9mccj+x9Nc0pITRRcfDr6xz4fHkmtbT44eGBCMAAOCEWBAMAIMCEaAAcEIMCCYg2UkH3KttmTJ2wfQeVvZSnOdmA09MCAYAQYEI8CAYAQYEIwAA4IRYECweVtGkvIMN0tO1Fec2E\/X1mnJiVKfOPTAgGAEGBCMAAOCEWBAMAIMCEaAAcEcLCNJuXYvuhrh7XAi+x93rZGd+Dfy4b+7iR4YEIwAA4IRYEAwAgwIRoABwQgwIFhmykg+nHfHtcmNpCxpydut2+daacqHR3Jy9MCAYAQYEIwAA4IRYEAwAgwIRoABwbL8PNIi41wrEjgxEZG3T5Cz5EQZKc2Sz4I6nhU9MCAaAQYEI8CAYAQYEIwAA4IRYEAwt+dGcqKUYv\/j3pY9fDh2x5Jr00qJLvk48TjBOaAHBgQjwIBgBBgQjAADghFgQDACDAiWchnJ28eLuVaJseTEs9FceyqdfT4c9uTD6Zq8PRRN9MCAYAQYEIwAA4IRYEAwAgwIRoABwVJ+qJ0PawyuPUEuzV3ylg8bxNtKobdFwUwdIfTAgGAEGBCMAAOCEWBAMAIMCEaAAcFSHo0k5Sq\/a0WCNNfp2obSrFt4u0s+GfoTyyeVQnpgQDACDAhGgAHBCDAgGAEGBCPAgGBuP9TOkhNFAm9HTblWcHKCt4PDXOPtAZap45MeGBCMAAOCEWBAMAIMCEaAAcEIMCBYyg+1s16LW\/UVH86N5Np4IEtSngfo7UPtfDipFQ+1A0CAAckIMCAYAQYEI8CAYAQYECzl0Uj2eVsxcqJu4egkN0\/dkH2uNZ0TBbwFUjFiNBIAAgxIRoABwQgwIBgBBgQjwIBgKY9GcmKsiRMbco1r32ghN519Ppwoy1H0wIBgBBgQjAADghFgQDACDAhGgAHBHCwjpblO+7wdD+TD8ox93j5BTjSfFJzogQHBCDAgGAEGBCPAgGAEGBCMAAOCZWZuJOtVu3Wd3YmajU9mvpkbKXMOudbIPpy7i7mRABBgQDICDAhGgAHBCDAgGAEGBEt5biT718SdqFv48CFsaU4a5EThwYl5oRbyoCvXRuAxNxKwsBBgQDACDAhGgAHBCDAgGAEGBEu5jGTJtdqSazMJpcm1r+naLrnGhwO5XPv4HNADA4IRYEAwAgwIRoABwQgwIBgBBgTLzEPtnCjkuP98sAxuSMoD\/bwtd7lW6nOtkZ045pOjBwYEI8CAYAQYEIwAA4IRYEAwAgwI5uDcSNbb87QeYMmHNTBvxy2luSHXSilSJnZytCBKDwwIRoABwQgwIBgBBgQjwIBgBBgQLOUykmsPoHNtnI0Pn\/YmZdiTtyPGLEkZr5Yp9MCAYAQYEIwAA4IRYEAwAgwIRoABwVKeGynNC+XeTm7k7c47Me+O\/Q15+\/g7b8ct2efDyaKSowcGBCPAgGAEGBCMAAOCEWBAMAIMCOaLuZHsr9MJPpwfSMrQH0vzb4CUn2tg9MCAYAQYEIwAA4IRYEAwAgwIRoABwVIejeTEkBpLrtUY7HPt404sKWXIl2vsN4i3syglRw8MCEaAAcEIMCAYAQYEI8CAYAQYECwzcyP5cBokSz6ctseSt5NFucbbSqEThTHXjmQTPTAgGAEGBCPAgGAEGBCMAAOCEWBAsMzMjeRExciHNQYnhpWkuaRrLe9Ee3o7wsm16qOjdUp6YEAwAgwIRoABwQgwIBgBBgQjwIBgmXmonWvT9jgxesb+Ol0bzOTahpwoYqXJtbqaJdeG0GUKPTAgGAEGBCPAgGAEGBCMAAOCEWBAsJTLSPY5MSbGh1UobzfkWh3I\/j9HmpUYb5+a6MT0V44WBemBAcEIMCAYAQYEI8CAYAQYEIwAA4Jlpozk7TAdS94OanFi\/IqUqYBcGyRknxNFLJ+gBwYEI8CAYAQYEIwAA4IRYEAwAgwI5uBoJCd4W+Hwtrbk\/kiXp3Jtl1wrONlfp7fHkokeGBCMAAOCEWBAMAIMCEaAAcEIMCBYVqpXrp0YF+LE1p0YD+TaA9N8OLrLPtfqK97OTuTEkUwZCVhYCDAgGAEGBCPAgGAEGBCMAAOCpVxGksK1AkmanKgY+fDxd\/b5cMYjH850ZaIHBgQjwIBgBBgQjAADghFgQDACDAjm9mgkJ\/hwMh5LrlXsvK0DWfJ2fJUPhz1l6rvTAwOCEWBAMAIMCEaAAcEIMCAYAQYEy8zcSFIKJPYrB64VHrwtd3n7kED7\/FzI8XZD9MCAYAQYEIwAA4IRYEAwAgwIRoABwTJTRrIkZRYl++zvkrc779rwrDQ\/7loRy4dVKOZGAkCAAckIMCAYAQYEI8CAYAQYEMzBMpIP+bC64+1T1Fwr5Lg2usuJEpolb6ukJnpgQDACDAhGgAHBCDAgGAEGBCPAgGDztozkw\/KMt\/MD2ZfmLjlRyHHtAXSWfPgwQxM9MCAYAQYEI8CAYAQYEIwAA4IRYEAwB8tI3l76t+TtLnk7Hsj+Oi259gS5NGs2Tnwjb1s+OXpgQDACDAhGgAHBCDAgGAEGBCPAgGCZKSM5McrHh6TULXw4EsuSa8OzXJvHyLVvZKIHBgQjwIBgBBgQjAADghFgQDACDAiW5cMxQwBsogcGBCPAgGAEGBCMAAOCEWBAMAIMCEaAAcEIMCAYAQYEI8CAYAQYEIwAA4IRYEAwAgwIRoABwQgwIBgBjvf+++9nZWW99tpr6a+qq6vrhRde+O6779JflW\/9+eefn3\/++SuvvLJ06dJFixYtXrx46dKla9eu\/eSTT65fvz7bp+w38smTJ1999dXnnnvumWeeKSgoWLly5Ycffnj\/\/v2MfgnJDMSIRCJFRUVKqUAgcPfu3TTXtmLFCqXUxo0bM7JvPrR\/\/\/6cnJzZDq38\/PxHjx4lfspmI0cikZaWFr2q4uLi+vr6ysrKQCCg1\/zjjz86+c3EIMD\/p7e3VylVUVGhlNq7d2+aa9u7d29+fv7x48czsm\/2NTY2btq0ydFNTE9Pr1+\/XqeroaGht7d3ZGQkEolEIpHbt2\/39va2trZ+9NFHlp+12ci7du1SShUWFn777bfmi3fu3NGpLikpmZyczPwXk4YA\/59QKKSU6uvrU0qVl5dHo1Gv9yhlDx8+DAQCTgdYp0sp1dHRkepnbTZyeXm5Uqqnpyfu9cePH+sO\/MyZM3PZ9fmFAP\/nxo0bumcwDKOhoUHoIXL48GGllKMBHhwc1Keys\/WxSdhv5Ly8PKXU5cuXE99avXq1UurYsWOpbn3+IcD\/aWtrU0rt2LHDMIyDBw8qpVpbWy2XjEajvb29zc3NpaWlwWCwpKSkvr4+HA6fOXNmamrKXGzTpk2znSWOjIy0tbXV1NTk5uYWFhaGQqHu7u7p6em4xfQabty4MT093d3dvWbNmqKiopycnMrKyi1btly5ciV24cHBwZaWFh2tWDU1NXGrHR0d3bVrV319fWFhYW5ublVV1datW4eGhmw21MaNG3UIE3f4qew38qpVq5L3wJbZXmgI8BORSKSwsFCnxTCM8fHxnJycQCAwOjoat+T09HRTU5PORklJSW1trT6elFLBYDB2+dkC3N\/fn5ubq5evqanR54pKqVAoNDExEbukXkNPT89LL72klMrJyamoqND7qT8e230dPnx4\/fr1NTU1SqmysrL1\/3r77bdj19nX16c7N6VUaWlpRUVFMPhkkko7PerU1JT+eHt7u72m\/Y\/9RjYMo6enR7fwtWvXzBej0ejWrVuTxH6hIcBPHD16VCm1evVq8xUdnk8\/\/TRuSX1gVVdXx3aA4+Pjx44d279\/f+ySlgG+fPmyvnLb1tZmxvXy5cu1tbWJp756DTk5OcXFxSdOnIhEIvr1Gzdu6PPP8vLyuG5w7969SU6hBwcHdVzD4bAZm8nJyc7OTr1XcV8h0ZUrV3TaBwYGki+ZyH4ja5s3b9Zff\/v27Xfu3Dl37pz+1mvWrBkfH0916\/MSAX5C\/6w6cuSI+cr58+f1iWLcVZbt27crpXbv3v3UdVoGuLGx0bIDGRoa0sGI\/bug12CZltu3b+uz5UuXLsW+njzA+mtu3rw58S19cTg3N\/fBgwdJvtSZM2f0Lll2m8nZb2TTwYMHzTMOpVReXl53d7fEi4sO4UYOpZS6fv368PBwXl6e\/pOvtbS0VFRU3Lt3Tx9kpqqqKqVUf3\/\/H3\/8keqGfv\/994sXLyqlOjo64t56+eWX9a++s2fPxr21atWqtWvXxr24bNkyfe599+5dm1v\/7bffhoeHlVLt7e2J77711ls1NTVTU1Nff\/11kpVMTU3p\/4nNlR0pNbL2119\/jY+PR6NRpZQ+b49EIv39\/Tdv3kxp0\/MYAVZKKX1WvGnTpoKCAvPF7Oxs\/XPryJEjsQtv27Zt+fLlIyMjdXV177333tWrV+1v6NKlS0qpwsLCF198MfHd6upqpdStW7fiXtc\/gBOVlZUppSKRiM2t606+rKxs2bJllgusW7fOXGw2+fn5+n8eP35sc7taSo2slLp\/\/34oFOro6Kivr7906dLExMSJEydWrFhx8eLF1atXf\/XVVyltfd7y+hTAe48fP07emcRdmjIMY3Jy8oMPPjAvBS1fvvzAgQOJ9xUknkIfOHDgqf8iLS0tcWuY7dqSDvbRo0djX0xyCt3Z2amUamhomK0pdM\/c2Ng4e2sZug9XCafuyc2hkfX5yLZt22JPmKPR6GeffaaUCgQCP\/30k\/0dmK\/ogdWpU6cmJyeTLKCLRrGvFBQUdHV1jY2N9fT0hEKhW7du7dy5s7q6+vTp08m3NTMzo5TKz88Pza6uri7uU+ZV4jTp9eh9mLMVK1boy136bMKmVBv59OnT165dq6qqOnToUHZ2tvl6dnb2xx9\/vHPnzpmZGf2naoEjwE9O7To7O6NWdK\/V29v7zz\/\/xH3w2Weffeedd37++edr1661traOj49v2bIl+Rl1SUmJUqqsrOzn2XV1dTn0TfXdi2NjY7MtcO\/ePaVUaWlpkpUsXrzYvI\/C\/qZTbWTdzzc1NS1atChxbeFwWCllXg9fyBZ6gK9evarrOuFwONtKOBzOzc0dHR394YcfZlvJypUrv\/\/+++bm5mg0eurUqSSb03cRjoyMOD2exrKbDYVCgUDg4cOHv\/zyi+WnBgYG1L87mYS+GePmzZtffvmlnZ2ZQyPrS2W6Wp5IX9bS\/13gFnqAdc+wcePGJUuWWC6wZMkS\/UM08SpLnPr6eqVU8hPFZcuWhUKhmZmZ3bt3z3GPn0ZfZLK8NP3888\/rW1D27duX+O7Jkyfv3LmTk5MTe5XY0htvvKE74R07dnzzzTdP3aU5NLJuzPPnz\/\/999+Jy+u\/krNd21tYvP4R7iXzykryyyG68BMMBu\/du2cYRnt7e1tbW+x9fNFo9OzZs8XFxUqpvr4+83XLOvDQ0JD+Dfn6668PDw\/rKzTRaHR0dPT48eMbNmzQW0myBpPlRSyzEzNHQcVeBLp9+7a+9hYOh8fGxvSLkUjk0KFDuruzOQZrZGREn5ArpVpbW\/v7+8fGxqLR6NTU1Ojo6IULFzo6OvRdYnNr5EePHukz+XXr1g0PD5uLTUxM7NmzRxfAJd6pnnELOsD6733ircKJli9frpTat2+fYRg7duzQB25+fn5dXV1tba1ZWYm7QWK2+J07d868+1LfZRV7A3PsENk5BNh8XSlVXl5eW1tbXFwc++6FCxf0T\/FAIFBZWanvx9bLt7W12b9HYmxszLyl1JIeqDS3RjYMY2hoyPw1XlJSsmrVqqqqKn0dLhgMdnZ22tzP+W1BB1jfl\/fFF188dUl9231lZaVhGKOjo3v27AmFQsXFxcFgUA8t2LBhQ+ywVS1J\/B4+fNje3m6uRP8tCIfDZ8+etbkGY\/YAT0xMtLW16Zuci4uLm5ub4xZ48ODBvn37GhoaioqK9GCGN998c3Bw8KntkGhgYGD79u319fUlJSXBYLCoqKi6urqpqam9vf3XX3815trI5hfZv39\/Y2OjXnleXl5dXd27776rb6WGYRhZhmHYOtUG4D8L\/SIWIBoBBgQjwIBgBBgQjAADghFgQDACDAhGgAHBCDAg2P8A9n+dPBYMIkgAAAAASUVORK5CYII=",
                "qr" => "qr_evento_1_asiento_CA8_ticket_9_key_67e63b27400e2",
                "final_price" => 3490,
                "ticket_id" => 9,
                "seller_user" => [
                    "id" => 1,
                    "user_gender_id" => 4,
                    "first_name" => "Juan",
                    "last_name" => "Hernández",
                    "middle_name" => "Carlos",
                    "username" => "juan-carlos-hdz",
                    "birthdate" => "2025-03-28",
                    "email" => "juan.carlos.hdz@example.com",
                    "email_verified_at" => null,
                    "phone_number" => null,
                    "color" => "purple",
                    "is_active" => 1,
                    "created_at" => "2025-03-28T04:55:50.000000Z",
                    "updated_at" => "2025-03-28T04:55:50.000000Z",
                    "is_new" => false
                ],
                "ticket_created_at" => "2025-03-28T06:01:09.000000Z",
                "cash_register_type" => 1,
                "is_owner" => true,
                "description" => "122",
                "holder_name" => "zurielda",
                "holder_last_name" => "diaz",
                "holder_middle_name" => "dias",
                "holder_zip_code" => "12123",
                "holder_phone" => "122123345",
                "holder_email" => "ewqwqew@gmail.com",
                "holder_jersey_type" => "Femenino",
                "holder_jersey_size" => "S",
                "global_payment_types" => [
                    [
                        "id" => 2,
                        "name" => "tarjeta (credito)",
                        "description" => "Pago con tarjeta",
                        "is_active" => 1,
                        "created_at" => "2025-03-28T04:55:51.000000Z",
                        "updated_at" => "2025-03-28T04:55:51.000000Z",
                        "pivot" => [
                            "sale_ticket_id" => 9,
                            "global_payment_type_id" => 2,
                            "global_card_payment_type_id" => 1,
                            "reason_agreement_id" => null,
                            "amount" => "3490.0000",
                            "original_amount" => "3490.0000",
                            "reason_courtesy" => null,
                            "payment_date" => null,
                            "is_active" => 1,
                            "created_at" => "2025-03-28T06:01:09.000000Z",
                            "updated_at" => "2025-03-28T06:01:09.000000Z"
                        ]
                    ]
                ]
            ]
        ],
        "generic_data" => [
            "sale_date" => "28/03/2025",
            "folio" => 9,
            "payment_in_installments" => null,
            "total_amount" => 3490,
            "global_payment_types" => [
                [
                    "id" => 2,
                    "name" => "tarjeta (credito)",
                    "description" => "Pago con tarjeta",
                    "is_active" => 1,
                    "created_at" => "2025-03-28T04:55:51.000000Z",
                    "updated_at" => "2025-03-28T04:55:51.000000Z",
                    "pivot" => [
                        "sale_ticket_id" => 9,
                        "global_payment_type_id" => 2,
                        "global_card_payment_type_id" => 1,
                        "reason_agreement_id" => null,
                        "amount" => "3490.0000",
                        "original_amount" => "3490.0000",
                        "reason_courtesy" => null,
                        "payment_date" => null,
                        "is_active" => 1,
                        "created_at" => "2025-03-28T06:01:09.000000Z",
                        "updated_at" => "2025-03-28T06:01:09.000000Z"
                    ]
                ]
            ],
            "seller_user" => [
                "id" => 1,
                "user_gender_id" => 4,
                "first_name" => "Juan",
                "last_name" => "Hernández",
                "middle_name" => "Carlos",
                "username" => "juan-carlos-hdz",
                "birthdate" => "2025-03-28",
                "email" => "juan.carlos.hdz@example.com",
                "email_verified_at" => null,
                "phone_number" => null,
                "color" => "purple",
                "is_active" => 1,
                "created_at" => "2025-03-28T04:55:50.000000Z",
                "updated_at" => "2025-03-28T04:55:50.000000Z",
                "is_new" => false
            ]
        ]
    ];

    $pdf_response = Pdf::loadView('pdfs.hdx.saleTicket', $data);

    return $pdf_response->stream('archivo.pdf');
});

Route::get('/closeCashRegister', function () {

    $data = [
        "pdf_data" => [
            "ticket_office" => [
                "id" => 2,
                "stadium_id" => 1,
                "global_image_id" => null,
                "global_address_id" => null,
                "name" => "taquilla el nido del halcon",
                "description" => "taquilla del estadio el nido del halcon en la entrada principal",
                "is_active" => 1,
                "created_at" => "2025-03-28T04:55:51.000000Z",
                "updated_at" => "2025-03-28T04:55:51.000000Z"
            ],
            "cash_register" => [
                "id" => 3,
                "ticket_office_id" => 2,
                "cash_register_type_id" => 1,
                "seller_user_opening_id" => 1,
                "seller_user_closing_id" => 1,
                "description" => "apertura de caja registradora por user_id: 1",
                "is_open" => 1,
                "confirmed_closure" => 0,
                "batch_cash_register" => 2,
                "batch_code" => "67e640d0bcab2",
                "opening_balance" => "1000.0000",
                "current_balance" => "11470.0000",
                "closing_balance" => "11470.0000",
                "opening_time" => "2025-03-28 06:25:20",
                "closing_time" => "2025-03-28T07:15:50.582539Z",
                "created_at" => "2025-03-28T06:25:20.000000Z",
                "updated_at" => "2025-03-28T06:37:38.000000Z",
                "sellerUserOpening" => [
                    "id" => 1,
                    "user_gender_id" => 4,
                    "first_name" => "Juan",
                    "last_name" => "Hernandez",
                    "middle_name" => "Carlos",
                    "username" => "juan-carlos-hdz",
                    "birthdate" => "2025-03-28",
                    "email" => "juan.carlos.hdz@example.com",
                    "email_verified_at" => null,
                    "phone_number" => null,
                    "color" => "purple",
                    "is_active" => 1,
                    "created_at" => "2025-03-28T04:55:50.000000Z",
                    "updated_at" => "2025-03-28T04:55:50.000000Z",
                    "is_new" => false
                ],
                "ticket_office" => [
                    "id" => 2,
                    "stadium_id" => 1,
                    "global_image_id" => null,
                    "global_address_id" => null,
                    "name" => "taquilla el nido del halcon",
                    "description" => "taquilla del estadio el nido del halcon en la entrada principal",
                    "is_active" => 1,
                    "created_at" => "2025-03-28T04:55:51.000000Z",
                    "updated_at" => "2025-03-28T04:55:51.000000Z"
                ]
            ],
            "sale_tickets" => [
                [
                    "id" => 11,
                    "stadium_id" => 1,
                    "ticket_office_id" => 2,
                    "seller_user_id" => 1,
                    "cash_register_id" => 3,
                    "sale_ticket_status_id" => 2,
                    "price_type_id" => null,
                    "sale_debtor_id" => null,
                    "amount_received" => "3490.0000",
                    "total_amount" => "3490.0000",
                    "total_returned" => "0.0000",
                    "payment_in_installments" => null,
                    "promotion_id" => null,
                    "promotion_quantity" => null,
                    "is_transfer" => 0,
                    "is_online" => 0,
                    "created_at" => "2025-03-28T06:37:37.000000Z",
                    "updated_at" => "2025-03-28T06:37:37.000000Z",
                    "sale_ticket_status" => [
                        "id" => 2,
                        "name" => "pagado",
                        "description" => "venta pagada",
                        "is_active" => 1,
                        "created_at" => "2025-03-28T04:55:51.000000Z",
                        "updated_at" => "2025-03-28T04:55:51.000000Z"
                    ],
                    "global_payment_types" => [
                        [
                            "id" => 2,
                            "name" => "tarjeta (credito)",
                            "description" => "Pago con tarjeta",
                            "is_active" => 1,
                            "created_at" => "2025-03-28T04:55:51.000000Z",
                            "updated_at" => "2025-03-28T04:55:51.000000Z",
                            "pivot" => [
                                "sale_ticket_id" => 11,
                                "global_payment_type_id" => 2,
                                "global_card_payment_type_id" => 1,
                                "amount" => "3000.0000",
                                "original_amount" => "3000.0000",
                                "is_active" => 1,
                                "created_at" => "2025-03-28T06:37:37.000000Z",
                                "updated_at" => "2025-03-28T06:37:37.000000Z"
                            ]
                        ],
                        [
                            "id" => 1,
                            "name" => "efectivo",
                            "description" => "Pago en efectivo",
                            "is_active" => 1,
                            "created_at" => "2025-03-28T04:55:51.000000Z",
                            "updated_at" => "2025-03-28T04:55:51.000000Z",
                            "pivot" => [
                                "sale_ticket_id" => 11,
                                "global_payment_type_id" => 1,
                                "amount" => "490.0000",
                                "original_amount" => "490.0000",
                                "is_active" => 1,
                                "created_at" => "2025-03-28T06:37:37.000000Z",
                                "updated_at" => "2025-03-28T06:37:37.000000Z"
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    $pdf_response = Pdf::loadView('pdfs.hdx.closeCashRegister', $data);

    return $pdf_response->stream('archivo.pdf');
});
