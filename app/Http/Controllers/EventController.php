<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\Event;
use App\Models\GlobalCardPaymentType;
use App\Models\GlobalPaymentType;
use App\Models\PriceCatalogue;
use App\Models\PriceTypeSeatCatalogue;
use App\Models\User;
use App\Services\EventSeatCatalogueService;
use App\Services\EventService;
use App\Services\EventTypeService;
use App\Services\GlobalImageService;
use App\Services\GlobalSeasonService;
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


class EventController extends Controller
{
    protected $event_service;
    protected $event_type_service;
    protected $serie_service;
    protected $global_season_service;
    protected $global_image_service;
    protected $event_seat_catalogue_service;

    public function __construct(EventService $event_service, EventTypeService $event_type_service, SerieService $serie_service, GlobalSeasonService $global_season_service,
                                GlobalImageService $global_image_service, EventSeatCatalogueService $event_seat_catalogue_service)
    {
        $this->event_service = $event_service;
        $this->event_type_service = $event_type_service;
        $this->serie_service = $serie_service;
        $this->global_season_service = $global_season_service;
        $this->global_image_service = $global_image_service;
        $this->event_seat_catalogue_service = $event_seat_catalogue_service;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      try {
            $events = $this->event_service->getAll();

            return Inertia::render('App/Pos/Events', [
                'events' => $events,
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

            $user = Auth::user()->load('globalImages');

            $event_types = $this->event_type_service->getAll();
            $series = $this->serie_service->getAll();
            $global_seasons = $this->global_season_service->getAll();
            $events_for_type = $this->event_service->getAll()->groupBy('event_type_id');

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
                'serie_id' => 'required|exists:series,id',
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'is_active' => 'required|boolean'
            ]);

            $request->merge([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($request->end_date)->format('Y-m-d')
            ]);

            $data = $request->only(['event_type_id','serie_id','name','slug','description','start_date', 'end_date','is_active']);

            $event = $this->event_service->save( $data );

            if($request->global_image){

                $global_image = $this->global_image_service->save($request->all(), 'event_images');

                $event->global_image_id = $global_image->id;

                $event->save();
            }

            $this->event_seat_catalogue_service->saveInBulk($event->id);

            return WebResponseHelper::sendResponse($event, "Evento guardada con éxito", null, false);

        } catch (\Exception $e) {

            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al guardar el evento');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug, $id)
    {

        try {
            $response = $this->event_service->getById($id);
            $user = Auth::user();
            $users = User::all();

            return Inertia::render('App/Pos/Event', [
                'isEventsShow' => true,
                'event' => $response['event'],
                'a_zone' => $response['a_zone'],
                'b_zone' => $response['b_zone'],
                'c_zone' => $response['c_zone'],
                'f_zone' => $response['f_zone'],
                'user' => $user,
                'users' => $users,
                'user_roles' => $response['user_roles'],
                'global_payment_types' => $response['global_payment_types'],
                'global_card_payment_types' => $response['global_card_payment_types'],
                'purchase_types' => $response['purchase_types'],
            ]);
        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el evento');
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
                'a_zone' => $response['a_zone'],
                'b_zone' => $response['b_zone'],
                'c_zone' => $response['c_zone'],
                'f_zone' => $response['f_zone']
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
            'event_id' => 'required',
            'cash_register_id' => 'nullable',
            'member_user_id' => 'nullable',
            'seller_user_id' => 'required',
            'price_type_id' => 'nullable',
            'seats' => 'required',
            'amount_received' => 'nullable',
            'total_amount' => 'nullable',
            'total_returned' => 'nullable',
            'global_payment_types' => 'nullable|array',
            'is_online' => 'nullable|boolean',
            'purchase_type' => 'nullable|string',
            'serie_id' => 'nullable',
            'is_transfer' => 'nullable|boolean',
            'user_to_transfer' => 'nullable',
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

            $pdf_response = Pdf::loadView('pdfs.hdx.saleTicketPaper', ['pdf_data' => $response]);
            $pdfContent = $pdf_response->output();

            return response()->json([
                'data' => $response,
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

    /**
     * Confirm the purchase of the seats
     */
    public function confirmSeatsPurchase(Request $request)
    {

        /* $request->validate([
            'event_id' => 'required',
            'cash_register_id' => 'required',
            'member_user_id' => 'nullable',
            'seller_user_id' => 'required',
            'price_type_id' => 'required',
            'seats' => 'required',
            'amount_received' => 'required',
            'total_amount' => 'required',
            'total_returned' => 'required',
            'global_payment_types' => 'required|array',
            'is_online' => 'required|boolean',
        ]); */

        $request->validate([
            'event_id' => 'required',
            'cash_register_id' => 'nullable',
            'member_user_id' => 'nullable',
            'seller_user_id' => 'required',
            'price_type_id' => 'nullable',
            'seats' => 'required',
            'amount_received' => 'nullable',
            'total_amount' => 'nullable',
            'total_returned' => 'nullable',
            'global_payment_types' => 'nullable|array',
            'is_online' => 'nullable|boolean',
            'purchase_type' => 'nullable|string',
            'serie_id' => 'nullable',
            'is_transfer' => 'nullable|boolean',
            'user_to_transfer' => 'nullable',
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

            //return WebResponseHelper::sendResponse($response, 'Compra de asientos confirmada correctamente', null, false);

        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponseHelper::rollback($e, 'Opps! Algo salió mal al confirmar la compra de los asientos');
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
                'slug' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'is_active' => 'required|boolean'
            ]);

            $request->merge([
                'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($request->end_date)->format('Y-m-d')
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

}
