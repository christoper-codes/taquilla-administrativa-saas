<?php

namespace App\Http\Controllers;

use App\Helpers\WebResponseHelper;
use App\Models\TicketOffice;
use App\Models\User;
use App\Services\EventService;
use App\Services\TicketOfficeService;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TicketOfficeController extends Controller
{

    protected $ticket_office_service;
    protected $event_service;

    public function __construct(TicketOfficeService $ticket_office_service, EventService $event_service)
    {
        $this->ticket_office_service = $ticket_office_service;
        $this->event_service = $event_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewVendorTopics', Auth::user());

        try {
            $ticket_offices = $this->ticket_office_service->getAll();

            return Inertia::render('App/Pos/TicketOffices', [
                'ticket_offices' => $ticket_offices,
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar las taquillas');
        }
    }

    public function check()
    {
        try {

            return Inertia::render('App/Pos/CheckTickets');

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar');
        }
    }

    public function share()
    {
        try {

            $user = Auth::user()->load('globalImages');
            $users = User::all();

            $tickets = $user->EventSeatCatalogues()
                ->with('event', 'seatCatalogue', 'seatCatalogueStatus')
                ->whereHas('seatCatalogueStatus', function ($query) {
                    $query->where('name', 'vendido');
                })
                ->get();
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

            return Inertia::render('App/Pos/ShareTickets', [
                'user' => $user,
                'users' => $users,
                'events_with_tickets' => $eventsWithTickets,
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar');
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
    public function show(TicketOffice $ticketOffice)
    {
        Gate::authorize('viewVendorTopics', Auth::user());

        try {

            $ticket_office = $this->ticket_office_service->getById($ticketOffice->id);
            $events = $this->event_service->getAll();
            $auth_user = Auth::user();
            $active_cash_register = $auth_user->cashRegisterActive($ticketOffice->id);

            return Inertia::render('App/Pos/TicketOffice', [
                'ticket_office' => $ticket_office,
                'events' => $events,
                'auth_user' => $auth_user,
                'active_cash_register' => $active_cash_register
            ]);

        } catch (\Exception $e) {
            WebResponseHelper::rollback($e, 'Opps! Algo salió mal al cargar la taquilla');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketOffice $ticketOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketOffice $ticketOffice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketOffice $ticketOffice)
    {
        //
    }
}
