<?php

namespace App\Http\Controllers;

use App\Enums\PaymentInstallments;
use App\Enums\PurchaseTypes;
use App\Helpers\WebResponseHelper;
use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Models\EventSeatCatalog;
use App\Models\EventVisibilityType;
use App\Models\GlobalCardPaymentType;
use App\Models\GlobalPaymentType;
use App\Models\Institution;
use App\Models\PriceCatalogue;
use App\Models\PriceTypeSeatCatalogue;
use App\Models\SeatCatalogue;
use App\Models\User;
use App\Services\EventSeatCatalogueService;
use App\Services\EventService;
use App\Services\EventTypeService;
use App\Services\GlobalImageService;
use App\Services\GlobalSeasonService;
use App\Services\ReasonAgreementService;
use App\Services\SaleDebtorService;
use App\Services\SerieService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;


class EventController extends Controller
{
    protected $event_service;
    protected $event_type_service;
    protected $serie_service;
    protected $global_season_service;
    protected $global_image_service;
    protected $event_seat_catalogue_service;
    protected $event_repository;
    protected $reason_agreement_service;
    protected $sale_debtor_service;


    public function __construct(EventService $event_service, EventTypeService $event_type_service, SerieService $serie_service, GlobalSeasonService $global_season_service,
                                GlobalImageService $global_image_service, EventSeatCatalogueService $event_seat_catalogue_service, EventRepositoryInterface $event_repository,
                                ReasonAgreementService $reason_agreement_service, SaleDebtorService $sale_debtor_service)
    {
        $this->event_service = $event_service;
        $this->event_type_service = $event_type_service;
        $this->serie_service = $serie_service;
        $this->global_season_service = $global_season_service;
        $this->global_image_service = $global_image_service;
        $this->event_seat_catalogue_service = $event_seat_catalogue_service;
        $this->event_repository = $event_repository;
        $this->reason_agreement_service = $reason_agreement_service;
        $this->sale_debtor_service = $sale_debtor_service;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      try {
            $events = $this->event_service->getAllActive();
            $user_roles = null;
            if(Auth::user()){
                $user_roles = Auth::user()->userRoles;
            }
            return Inertia::render('App/Pos/Events', [
                'events' => $events,
                'user_roles' => $user_roles
            ]);
      } catch (\Exception $e) {
        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');
      }
    }

    /**
     * Display a listing of the resource.
     */
    public function indexManagement()
    {
        try {

            $user = Auth::user()->load('globalImages', 'userRoles');

            $event_types = $this->event_type_service->getAll();
            $series = $this->serie_service->getAll();
            $global_seasons = $this->global_season_service->getAll();
            $events_for_type = $this->event_service->getAll()->groupBy('event_type_id');
            $events_visibility_types = EventVisibilityType::all();

            $missingEventTypeIds = $event_types->pluck('id')->diff($events_for_type->keys())->values();
            $missingEventTypeIds->each(fn (int $eventTypeId) => $events_for_type->put($eventTypeId, collect()));

            $events_for_type = $events_for_type->map(function ($events, int $key) use ($event_types){
                return [
                    "event_type_id" => $key,
                    "event_type" => $event_types->where('id', $key)->first()->name,
                    "events" => $events
                ];
            })->values();

            return Inertia::render('App/Administration/Event/Event', [
                'user' => $user,
                'event_types' => $event_types,
                'series' => $series,
                'global_seasons'=> $global_seasons,
                'events_for_type' => $events_for_type,
                'events_visibility_types' => $events_visibility_types,
            ]);

      } catch (\Exception $e) {

        WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');
      }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'event_type_id' => 'required|exists:event_types,id',
                'global_season_id' => 'nullable',
                'serie_id' => 'nullable|exists:series,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required',
                'end_date' => 'required',
                'is_active' => 'required|boolean',
                'event_visibility_type_id' => 'required|exists:event_visibility_types,id',
            ]);

            $request->merge([
                'slug' => Str::slug($request->name, '-'),
            ]);

            $data = $request->only(['global_season_id','event_type_id','serie_id','name','slug','description','start_date', 'end_date','is_active', 'event_visibility_type_id']);

            $event = $this->event_service->save($data);

            if($request->global_image){

                $global_image = $this->global_image_service->save($request->all(), 'event_images');

                $event->global_image_id = $global_image->id;

                $event->save();
            }

            $this->event_seat_catalogue_service->saveInBulk($event);

            return WebResponseHelper::sendResponse($event, "Evento guardada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar el evento');

        }
    }

    public function show($slug, $id)
    {

        try {
            //$response = $this->event_service->getById($id);
            $event = Event::findOrFail($id);
            $event->globalImage;
            $event->serie->globalSeason;
            $payment_installments = [];
            $user = Auth::user();
            $users = User::all();
            $user_roles = Auth::user()->userRoles;
            $global_payment_types = GlobalPaymentType::where('is_active', true)->get();
            $global_card_payment_types = GlobalCardPaymentType::all();
            $sale_debtors = $this->sale_debtor_service->getAll(1);

            $purchase_types = [PurchaseTypes::MATCH->value];
            $events_by_serie = $this->event_repository->getEventsBySerie($event->serie_id);
            if ($events_by_serie->count() > 1) {
                $purchase_types[] = PurchaseTypes::SERIE->value;
            }
            if($event->enabled_for_season_tickets){
                $purchase_types[] = PurchaseTypes::SEASON_TICKET->value;
                $payment_installments = PaymentInstallments::toArray();
            }


            /*
            * get all reason agreement by stadium
            */
            $reason_agreements = $this->reason_agreement_service->getAllByStadium($event->stadium_id);

            /*
            * get institutions with its agreements and promotions by stadium
            */
            $institutions = Institution::where('stadium_id', $event->stadium_id)
                ->with(['agreements.promotions.promotionType'])
                ->get();

            return Inertia::render('App/Pos/Event', [
                'isEventsShow' => true,
                'event' => $event,
                'a_zone' => [],
                'b_zone' => [],
                'c_zone' => [],
                'f_zone' => [],
                'user' => $user,
                'users' => $users,
                'user_roles' => $user_roles,
                'global_payment_types' => $global_payment_types,
                'global_card_payment_types' => $global_card_payment_types,
                'purchase_types' => $purchase_types,
                'payment_installments' => $payment_installments,
                'reason_agreements' => $reason_agreements,
                'institutions' => $institutions,
                'sale_debtors' => $sale_debtors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opps! Algo salió mal al reservar los asientos',
                'success' => false
            ], 500);
        }
    }

    /*
    * Get seat availablility by zone
    */
    public function getSeatAvailabilityByZone(Request $request)
    {
        try {

            $request->validate([
                'event_id' => 'required'
            ]);

            $response = $this->event_service->getAvailability($request->all());

            return response()->json([
                'data' => $response,
                'message' => 'Las disponibilidad de los asientos se a obtenido con exito!!',
                'success' => true
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function getEventSeatCatalogues(Request $request)
    {
        try {

            $request->validate([
                'zone' => 'required',
                'event_id' => 'required'
            ]);

            $seat_catalogues = SeatCatalogue::where('zone', $request->zone)->get();
            $event_seat_catalogues = $seat_catalogues->map(function ($seat_catalogue) use ($request) {
                $actual_event_seat_catalogue = EventSeatCatalog::where([
                    ['event_id', $request->event_id],
                    ['seat_catalogue_id', $seat_catalogue->id],
                ])->with([
                    'seatCatalogue.seatType',
                    'priceTypes',
                    'seatCatalogueStatus',
                    'promotions.promotionType'
                ])->first();

                return $actual_event_seat_catalogue ? $actual_event_seat_catalogue : null;

            })->filter()->values();

            return response()->json([
                'data' => $event_seat_catalogues,
                'message' => 'Exito al optener los asientos',
                'success' => true
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opps! Algo salió mal al reservar los asientos',
                'success' => false
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function showManagement($id)
    {
        try {

            $response  = $this->event_service->getById($id);

            $data = [
                'event' => $response['event'],
                'c_zone' => $response['c_zone'],
                'a_zone' => $response['a_zone'],
                'b_zone' => $response['b_zone'],
                'e_zone' => $response['e_zone'],
                'f_zone' => $response['f_zone'],
                'h_zone' => $response['h_zone']
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el evento');

        }
    }



    /* *
    * Show the complete purchase
    */
    public function success()
    {
        return Inertia::render('App/Pos/Success');
    }

    /**
    *  Reserve seats to buy
    */
    public function reserveSeatsToBuy(Request $request)
    {

        $request->validate([
            'stadium_id' => 'required',
            'ticket_office_id' => 'required',
            'event_id' => 'required',
            'cash_register_id' => 'nullable',
            'member_user_id' => 'nullable',
            'seller_user_id' => 'required',
            'price_type_id' => 'nullable',
            'seats' => 'required',
            'amount_received' => 'nullable',
            'total_amount' => 'nullable',
            'total_returned' => 'nullable',
            'payment_in_installments' => 'nullable',
            'global_payment_types' => 'nullable|array',
            'is_online' => 'nullable|boolean',
            'purchase_type' => 'nullable|string',
            'serie_id' => 'nullable',
            'is_transfer' => 'nullable|boolean',
            'user_to_transfer' => 'nullable',
            'holder_name' => 'nullable',
            'holder_last_name' => 'nullable',
            'holder_middle_name' => 'nullable',
            'holder_email' => 'nullable',
            'holder_phone' => 'nullable',
            'holder_zip_code' => 'nullable',
            'description' => 'nullable',
            'is_owner' => 'nullable',
            'final_promotion' => 'nullable',
            'sale_debtor' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $response = $this->event_service->reserveSeatsToBuy($request->all());
            DB::commit();

            if($request->is_online) {
                 return response()->json([
                    'data' => $response,
                    'message' => 'Asientos reservados correctamente',
                    'success' => true
                ], 200);
            }

            if($request->purchase_type == PurchaseTypes::SEASON_TICKET->value) {

                $generic_data = [
                    'sale_date' => Carbon::now()->format('d/m/Y'),
                    'folio' => $response[0]['ticket_id'],
                    'payment_in_installments' => $request->payment_in_installments ?? null,
                    'total_amount' => $request->total_amount,
                    'global_payment_types' => $response[0]['global_payment_types'],
                    'seller_user' => $response[0]['seller_user'],
                    'payment_in_installments' => $response[0]['payment_in_installments'],
                    'promotion_ticket' => $response[0]['promotion_ticket'],
                    'sale_debtor' => $response[0]['sale_debtor'],
                    'installment_payment_histories'=>$response[0]['installment_payment_histories']
                ];

                $pdf_response = Pdf::loadView('pdfs.hdx.saleTicket', [
                    'pdf_data' => $response,
                    'generic_data' => $generic_data
                ]);
                $pdfContent = $pdf_response->output();

                return response()->json([
                    'data' => $response,
                    'subscriber' => true,
                    'message' => 'Asientos reservados y comprados correctamente',
                    'success' => true,
                    'pdf' => base64_encode($pdfContent)
                ], 200);
            }

            $generic_data = [];
            if($response[0]['promotion_ticket']){
                $generic_data = [
                    'promotion_ticket' => $response[0]['promotion_ticket'],
                ];
            }
            $pdf_response = Pdf::loadView('pdfs.hdx.saleTicketPaper', ['pdf_data' => $response, 'generic_data' => $generic_data]);
            $pdfContent = $pdf_response->output();

            return response()->json([
                'data' => $response,
                'message' => 'Asientos reservados y comprados correctamente',
                'success' => true,
                'subscriber' => false,
                'pdf' => base64_encode($pdfContent)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opps! Algo salió mal al reservar los asientos',
                'success' => false
            ], 500);
        }
    }

    /**
     * Confirm the purchase of the seats
     */
    public function confirmSeatsPurchase(Request $request)
    {

        $request->validate([
            'stadium_id' => 'required',
            'ticket_office_id' => 'required',
            'event_id' => 'required',
            'cash_register_id' => 'nullable',
            'member_user_id' => 'nullable',
            'seller_user_id' => 'required',
            'price_type_id' => 'nullable',
            'seats' => 'required',
            'amount_received' => 'nullable',
            'total_amount' => 'nullable',
            'total_returned' => 'nullable',
            'payment_in_installments' => 'nullable',
            'global_payment_types' => 'nullable|array',
            'is_online' => 'nullable|boolean',
            'purchase_type' => 'nullable|string',
            'serie_id' => 'nullable',
            'is_transfer' => 'nullable|boolean',
            'user_to_transfer' => 'nullable',
            'holder_name' => 'nullable',
            'holder_last_name' => 'nullable',
            'holder_middle_name' => 'nullable',
            'holder_email' => 'nullable',
            'holder_phone' => 'nullable',
            'holder_zip_code' => 'nullable',
            'description' => 'nullable',
            'is_owner' => 'nullable',
            'final_promotion' => 'nullable',
            'sale_deptor' => 'nullable',
            'online_payment_transaction' => 'nullable',
        ]);

        DB::beginTransaction();

        try {

            $response = $this->event_service->confirmSeatsPurchase($request->all());

            DB::commit();

            if($request->is_online) {
                return response()->json([
                    'data' => $response,
                    'message' => 'Compra de asientos confirmada correctamente',
                    'success' => true
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponseHelper::rollback($e, 'Opps! Algo salió mal al confirmar la compra de los asientos');
        }
    }

    /**
    * Print sale ticket
    */
    public function printSaleTicket(Request $request)
    {
        try {

            $response =  $this->event_service->printSaleTicket($request->sale_ticket_id);

            $pdf_response = Pdf::loadView('pdfs.hdx.saleTicketPaper', ['pdf_data' => $response]);
            $pdfContent = $pdf_response->output();

            return response()->json([
                'data' => $response,
                'message' => 'Ticket impreso con exito',
                'success' => true,
                'pdf' => base64_encode($pdfContent)
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opp! Algo salio mal al imprimir el ticket',
                'success' => false
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            $request->validate([
                'event_type_id' => 'required|exists:event_types,id',
                'serie_id' => 'required|exists:series,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'is_active' => 'required|boolean'
            ]);

            $request->merge([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                'slug' => Str::of($request->name)->slug('_')
            ]);

            if($request->global_image){

                $global_image = $this->global_image_service->save($request->only('global_image'), 'event_images');

                $request->merge([ 'global_image_id' => $global_image->id ]);
            }

            $data = $request->only(['event_type_id','serie_id', 'global_image_id','name','slug','description','start_date', 'end_date','is_active']);

            $event = $this->event_service->update($request->id, $data );

            return WebResponseHelper::sendResponse($event, "Evento actualizada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar los eventos');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $event = $this->event_service->delete( $id );

            return WebResponseHelper::sendResponse($event, "Evento eliminada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al eliminar el evento');

        }
    }

    /**
     * Test PDF
     */
    public function testPdf()
    {
        try {

            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: 'Custom QR code contents',
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                labelText: 'qr code',
                labelFont: new OpenSans(20),
                labelAlignment: LabelAlignment::Center
            );

            $result = $builder->build();

            $img = $result->getDataUri();

            $pdf = PDF::loadView('pdfs.hdx.saleTicket', compact('img'));

            return $pdf->stream();

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el pdf');
        }
    }


    public function printSubscriber(Request $request)
    {
        try {
            $response = $this->event_service->printSubscriber($request->id);

            if($request->purchase_type == PurchaseTypes::SEASON_TICKET->value) {

                $generic_data = [
                    'sale_date' => Carbon::now()->format('d/m/Y'),
                    'folio' => $response[0]['ticket_id'],
                    'total_amount' => $response[0]['sale_ticket']['total_amount'],
                    'global_payment_types' => $response[0]['global_payment_types'],
                    'seller_user' => $response[0]['seller_user'],
                    'payment_in_installments' => $response[0]['payment_in_installments'],
                    'promotion_ticket' => $response[0]['promotion_ticket'],
                    'sale_debtor' => $response[0]['sale_debtor'],
                    'installment_payment_histories'=>$response[0]['installment_payment_histories']
                ];

                $pdf_response = Pdf::loadView('pdfs.hdx.saleTicket', [
                    'pdf_data' => $response,
                    'generic_data' => $generic_data
                ]);

                $pdfContent = $pdf_response->output();

                return response()->json([
                    'data' => $response,
                    'subscriber' => true,
                    'message' => 'Asientos reservados y comprados correctamente',
                    'success' => true,
                    'pdf' => base64_encode($pdfContent)
                ], 200);


            }

            $generic_data = [];
            if($response[0]['promotion_ticket']){
                $generic_data = [
                    'promotion_ticket' => $response[0]['promotion_ticket'],
                ];
            }
            $pdf_response = Pdf::loadView('pdfs.hdx.saleTicketPaper', ['pdf_data' => $response, 'generic_data' => $generic_data]);
            $pdfContent = $pdf_response->output();

            return response()->json([
                'data' => $response,
                'subscriber' => false,
                'message' => 'Asientos reservados y comprados correctamente',
                'success' => true,
                'pdf' => base64_encode($pdfContent)
            ], 200);



        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'data' => null,
                'message' => $e->getMessage() ?? 'Opps! Algo salió mal al reservar los asientos',
                'success' => false
            ], 500);
        }
    }
}
