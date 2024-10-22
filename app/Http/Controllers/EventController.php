<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\Event;
use App\Models\GlobalCardPaymentType;
use App\Models\GlobalPaymentType;
use App\Models\PriceCatalogue;
use App\Models\PriceTypeSeatCatalogue;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EventController extends Controller
{
    protected $event_service;

    public function __construct(EventService $event_service)
    {
        $this->event_service = $event_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      try {
            $events = $this->event_service->getAll();

            return Inertia::render('Pos/Events', [
                'events' => $events,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug, $id)
    {
        try {
            $response = $this->event_service->getById($id);
            $user = Auth::user();

            return Inertia::render('Pos/Event', [
                'event' => $response['event'],
                'a_zone' => $response['a_zone'],
                'b_zone' => $response['b_zone'],
                'c_zone' => $response['c_zone'],
                'user' => $user,
                'user_roles' => $response['user_roles'],
                'global_payment_types' => $response['global_payment_types'],
                'global_card_payment_types' => $response['global_card_payment_types'],
            ]);
        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el evento');
        }
    }

    /* *
    * Show the complete purchase
    */
    public function success()
    {
        return Inertia::render('Pos/Success');
    }

    /**
    *  Reserve seats to buy
    */
    public function reserveSeatsToBuy(Request $request)
    {
        $request->validate([
            'seats' => 'required',
            'event_id' => 'required',
            'member_user_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $response = $this->event_service->reserveSeatsToBuy($request->all());

            DB::commit();

            return WebResponseHelper::sendResponse($response, 'Asientos reservados correctamente', null, false);

        } catch (\Exception $e) {
            DB::rollBack();
            return WebResponseHelper::rollback($e, 'Opps! Algo salió mal al reservar los asientos');
        }
    }

    /**
     * Confirm the purchase of the seats
     */
    public function confirmSeatsPurchase(Request $request)
    {

        $request->validate([
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
        ]);

        DB::beginTransaction();

        try {

            $response = $this->event_service->confirmSeatsPurchase($request->all());

            DB::commit();

            return WebResponseHelper::sendResponse($response, 'Compra de asientos confirmada correctamente', null, false);

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
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
