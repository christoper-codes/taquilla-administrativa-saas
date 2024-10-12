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

            return Inertia::render('Pos/Event', [
                'event' => $response['event'],
                'a_zone' => $response['a_zone'],
                'b_zone' => $response['b_zone'],
                'c_zone' => $response['c_zone'],
                'user_roles' => $response['user_roles'],
                'global_payment_types' => $response['global_payment_types'],
                'global_card_payment_types' => $response['global_card_payment_types'],
            ]);
        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el evento');
        }
    }

    public function success()
    {
        return Inertia::render('Pos/Success');
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
