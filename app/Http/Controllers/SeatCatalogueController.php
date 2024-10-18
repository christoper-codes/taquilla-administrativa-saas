<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\SeatCatalogue;
use App\Services\EventService;
use App\Services\SeatCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SeatCatalogueController extends Controller
{

    /*
    * Inject the SeatCatalogue service into the controller
    */
    protected $seat_catalogue_service;
    protected $event_service;

    public function __construct(SeatCatalogueService $seat_catalogue_service, EventService $event_service)
    {
        $this->seat_catalogue_service = $seat_catalogue_service;
        $this->event_service = $event_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

            $user = Auth::user()->load('globalImages');
            $flash = $user->is_new ? 'is_new_user' : null;
            $tickets = $user->EventSeatCatalogues()->with('event', 'seatCatalogue', 'seatCatalogueStatus')->get();
            $events = $this->event_service->getAll();
            $eventsWithTickets = [];

            foreach ($events as $event) {
                $eventsWithTickets[$event->id] = [
                    'event' => $event,
                    'tickets' => $tickets->filter(function($ticket) use ($event) {
                        return $ticket->event_id == $event->id;
                    })->values()
                ];
            }

            return Inertia::render('App/Dashboard', [
                'user' => $user,
                'flash' => $flash,
                'events_with_tickets' => $eventsWithTickets,
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar el catálogo de asientos');
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
    public function show(SeatCatalogue $seatCatalogue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeatCatalogue $seatCatalogue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeatCatalogue $seatCatalogue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeatCatalogue $seatCatalogue)
    {
        //
    }
}
